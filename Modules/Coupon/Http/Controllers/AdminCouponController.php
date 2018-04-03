<?php

namespace Modules\Coupon\Http\Controllers;

use Modules\User\Entities\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Coupon\Entities\Coupon;
use Modules\Coupon\Entities\CouponProduct;
use Modules\Coupon\Entities\CouponUser;
use Modules\Coupon\Http\Requests\AdminCoupon;
use Modules\Product\Entities\Product;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class AdminCouponController extends Controller
{

    /**
     * @var string
     */
    protected $viewPath = 'coupon::admin.';

    /**
     * @var string
     */
    protected $route = 'admin.coupon.';

    /**
     * @return string
     */
    public function getViewPath(): string
    {
        return $this->viewPath;
    }

    /**
     * @param string $viewPath
     */
    public function setViewPath(string $viewPath)
    {
        $this->viewPath = $viewPath;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute(string $route)
    {
        $this->route = $route;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->dataTable();
        }

        return view($this->viewPath.'index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $data = new Coupon();
        $users = User::where('role','user')->orderBy('email', 'ASC')->get();
        $products = Product::orderBy('name','ASC')->get();

        return view($this->viewPath . 'create', compact('data', 'users', 'products'));
    }


    /**
     * @param AdminCoupon $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AdminCoupon $request)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $coupon = Coupon::create([
            'uuid' => Uuid::uuid4()->toString(),
            'name' =>  $request->name,
            'coupon' => $request->coupon,
            'percent' => $request->percent,
            'price' => $request->price,
            'use_max' => $request->use_max,
            'amount_min' => $request->amount_min,
            'begin' => $request->begin,
            'end' => $request->end
        ]);

        if (!empty($request->users)) {
            $insertUser = [];
            foreach ($request->users as $user) {
                $insertUser[] = [
                    'uuid' => Uuid::uuid4()->toString(),
                    'coupon_id' => $coupon->id,
                    'user_id' => $user,
                ];
            }
            $coupon->couponUsers()->createMany($insertUser);
        }

        if (!empty($request->products)) {
            $insertProduct = [];
            foreach ($request->products as $product) {
                $insertProduct[] = [
                    'uuid' => Uuid::uuid4()->toString(),
                    'coupon_id' => $coupon->id,
                    'product_id' => $product,
                ];
            }
            $coupon->couponProducts()->createMany($insertProduct);
        }

        session()->flash('success', __("coupon::admin.create"));
        return redirect()->route($this->route . 'index');

    }

    /**
     * @param string $uuid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(string $uuid)
    {
        $data = Coupon::where('uuid', $uuid)->FirstOrFail();
        $users = User::where('role','user')->orderBy('email', 'ASC')->get();
        $products = Product::orderBy('name','ASC')->get();

        return view($this->viewPath . 'edit', compact('data','users', 'products'));
    }


    /**
     * @param AdminCoupon $request
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AdminCoupon $request, string $uuid)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $coupon = Coupon::where('uuid', $uuid)->FirstOrFail();

        Coupon::where('id', $coupon->id)->update([
            'name' =>  $request->name,
            'coupon' => $request->coupon,
            'percent' => $request->percent,
            'price' => $request->price,
            'use_max' => $request->use_max,
            'amount_min' => $request->amount_min,
            'begin' => $request->begin,
            'end' => $request->end
        ]);

        $couponsUser = $coupon->CouponUsers;
        CouponUser::where('coupon_id', $coupon->id)->delete();
        if (!empty($request->users)) {
            $usersInsert = [];
            foreach ($request->users as $key => $user) {
                $usersInsert[$key] = [
                    'coupon_id' => $coupon->id,
                    'user_id' => $user
                ];
                foreach ($couponsUser as $cu) {
                    if ($cu->coupon_id == $coupon->id && $cu->user_id == $user) {
                        $usersInsert[$key]['use'] =  $cu->use;
                    }
                }
            }
            $coupon->couponUsers()->createMany($usersInsert);
        }


        CouponProduct::where('coupon_id', $coupon->id)->delete();
        if (!empty($request->products)) {
            $productsInsert = [];
            foreach ($request->products as $key => $product) {
                $productsInsert[$key] = [
                    'coupon_id' => $coupon->id,
                    'product_id' => $product
                ];
            }
            $coupon->couponProducts()->createMany($productsInsert);
        }
        session()->flash('success', __("coupon::admin.edit"));
        return redirect()->route($this->route. 'index');

    }


    /**
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $uuid)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $coupon = Coupon::where('uuid', $uuid)->FirstOrFail();

        Coupon::where('id', $coupon->id)->delete();
        CouponProduct::where('coupon_id', $coupon->id)->delete();
        CouponUser::where('coupon_id', $coupon->id)->delete();

        session()->flash('success', __("coupon::admin.delete"));
        return redirect()->route($this->route . 'index');
    }

    /**
     * @return mixed
     */
    private function dataTable()
    {
        $coupons = Coupon::select(['id','uuid','coupon','name','percent','price','updated_at']);
        return DataTables::of($coupons)
            ->editColumn('updated_at', 'admin.datatable.updatedAt')
            ->addColumn('reduce', function(Coupon $coupon){
                if(!empty($coupon->percent)){
                    return '-' . $coupon->percent . '%';
                } else{
                    return '-' . $coupon->price . '€';
                }
            })
            ->addColumn('actions', $this->getTableButtons())
            ->rawColumns(['actions', 'reduce'])
            ->make(true);
    }

    /**
     * @return string
     */
    private function getTableButtons(): string
    {
        $html = '<a href="{{ route(\''.$this->route.'edit\', [\'uuid\' => $uuid]) }}" class="table-button edit-button"><i class="fas fa-pencil-alt"></i> '.__('admin::actions.edit').'</a>';
        $html .= '<a href="{{ route(\''.$this->route.'delete\', [\'uuid\' => $uuid]) }}" class="table-button delete-button confirm-alert"><i class="fas fa-trash"></i> '.__("admin::actions.delete").'</a>';
        return $html;
    }
}
