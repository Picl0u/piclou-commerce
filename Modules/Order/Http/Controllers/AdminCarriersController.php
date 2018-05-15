<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Order\Entities\Carriers;
use Modules\Order\Entities\CarriersPrices;
use Modules\Order\Entities\Countries;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;
use Modules\Order\Http\Requests\Carriers as RequestCarriers;

class AdminCarriersController extends Controller
{

    /**
     * @var string
     */
    protected $viewPath = 'order::admin.carriers.';
    /**
     * @var string
     */
    protected $route = 'orders.carriers.';

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
        $countDefault = Carriers::where('default',1)->count();

        return view($this->viewPath.'index', compact('countDefault'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $data = new Carriers();
        $countries = Countries::where('activated', 1)->orderBy('name','ASC')->get();

        return view($this->viewPath . 'create', compact('data', 'countries'));
    }


    /**
     * @param RequestCarriers $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RequestCarriers $request)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $published = 0;
        if ($request->published == "on") {
            $published = 1;
        }
        $default = 0;
        if ($request->default == "on") {
            $default = 1;
        }
        $free = 0;
        if ($request->free == "on") {
            $free = 1;
        }
        $weight = 0;
        $price = 0;
        if($request->type_shipping == 'price'){
            $price = 1;
        }else{
            $weight = 1;
        }
        $insertImage = '';
        if($request->hasFile('image')){
            $insertImage = uploadImage('carriers', $request->image);
        }

        $carrier = Carriers::create([
            'uuid' => Uuid::uuid4()->toString(),
            'published' => $published,
            'default' => $default,
            'free' => $free,
            'price' => $price,
            'weight' => $weight,
            'name' => $request->name,
            'delay' => $request->delay,
            'url' => $request->url,
            'image' => $insertImage,
            'max_weight' => $request->max_weight,
            'max_width' => $request->max_width,
            'max_height' => $request->max_height,
            'max_length' => $request->max_length,
            'default_price' => $request->default_price,
        ]);

        //CarriersPrices::where('carriers_id', $carrier->id)->delete();
        if(isset($request->availableCountry) && !empty($request->availableCountry)) {
            foreach ($request->availableCountry as $key => $available) {
                if (is_null($request->priceMax[$key])) {
                    $priceMax = 0;
                } else {
                    $priceMax = $request->priceMax[$key];
                }
                if (is_null($request->priceMin[$key])) {
                    $priceMin = 0;
                } else {
                    $priceMin = $request->priceMin[$key];
                }
                foreach ($available as $country => $value) {
                    $insert = [
                        'uuid' => Uuid::uuid4()->toString(),
                        'carriers_id' => $carrier->id,
                        'price' => (is_null($request->countries[$key][$country])) ? 0 : $request->countries[$key][$country],
                        'country_id' => $country,
                        'price_min' => $priceMin,
                        'price_max' => $priceMax,
                        'key' => $key
                    ];

                    CarriersPrices::create($insert);
                }

            }
        }
        session()->flash('success',"Votre transporteur a bien été créé.");
        return redirect()->route($this->route . 'index');
    }


    /**
     * @param string $uuid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(string $uuid)
    {
        $data = Carriers::where('uuid', $uuid)->firstorFail();
        $countries = Countries::where('activated', 1)->orderBy('name','ASC')->get();

        return view(
            $this->viewPath . 'edit',
            compact('data', 'countries')
        );
    }

    /**
     * @param Request $request
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $uuid)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $carrier = Carriers::where('uuid', $uuid)->firstOrFail();

        $published = 0;
        if ($request->published == "on") {
            $published = 1;
        }
        $default = 0;
        if ($request->default == "on") {
            $default = 1;
        }
        $free = 0;
        if ($request->free == "on") {
            $free = 1;
        }
        $weight = 0;
        $price = 0;
        if($request->type_shipping == 'price'){
            $price = 1;
        }else{
            $weight = 1;
        }
        $insertImage = $carrier->image;
        if($request->hasFile('image')){
            $insertImage = uploadImage('carriers', $request->image);
            if(!empty($carrier->image)) {
                unlink($carrier->image);
            }
        }

        Carriers::where('id', $carrier->id)->update([
            'published' => $published,
            'default' => $default,
            'free' => $free,
            'price' => $price,
            'weight' => $weight,
            'name' => $request->name,
            'delay' => $request->delay,
            'url' => $request->url,
            'image' => $insertImage,
            'max_weight' => $request->max_weight,
            'max_width' => $request->max_width,
            'max_height' => $request->max_height,
            'max_length' => $request->max_length,
            'default_price' => $request->default_price,
        ]);

        CarriersPrices::where('carriers_id', $carrier->id)->delete();

        if(isset($request->availableCountry) && !empty($request->availableCountry)) {
            foreach ($request->availableCountry as $key => $available) {
                if (is_null($request->priceMax[$key])) {
                    $priceMax = 0;
                } else {
                    $priceMax = $request->priceMax[$key];
                    if (!is_numeric($priceMax)) {
                        $priceMax = 0;
                    }
                }
                if (is_null($request->priceMin[$key])) {
                    $priceMin = 0;
                } else {
                    $priceMin = $request->priceMin[$key];
                }
                foreach ($available as $country => $value) {
                    $insert = [
                        'uuid' => Uuid::uuid4()->toString(),
                        'carriers_id' => $carrier->id,
                        'price' => (is_null($request->countries[$key][$country])) ? 0 : $request->countries[$key][$country],
                        'country_id' => $country,
                        'price_min' => $priceMin,
                        'price_max' => $priceMax,
                        'key' => $key
                    ];

                    CarriersPrices::create($insert);
                }

            }
        }
        session()->flash('success',"Votre transporteur a bien été modifié.");
        return redirect()->route($this->route . 'index');

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

        $carriers = Carriers::where('uuid', $uuid)->FirstOrFail();

        Carriers::where('id', $carriers->id)->delete();
        if(!empty($carriers->image)) {
            unlink($carriers->image);
        }
        session()->flash('success',"Le transporteur a bien été supprimé.");
        return redirect()->route($this->route . 'index');
    }


    /**
     * @return mixed
     */
    private function dataTable()
    {
        $carriers = Carriers::select(['id','uuid','default','published','name','image','delay','updated_at']);
        return DataTables::of($carriers)
            ->addColumn('actions', $this->getTableButtons())
            ->editColumn('default', 'admin.datatable.default')
            ->editColumn('published', 'admin.datatable.published')
            ->editColumn('image', 'admin.datatable.image')
            ->editColumn('updated_at', 'admin.datatable.updatedAt')
            ->rawColumns(['actions','published','image','default'])
            ->make(true);
    }

    /**
     * @return string
     */
    private function getTableButtons(): string
    {
        $html = '<a href="{{ route(\''.$this->route.'.edit\', [\'uuid\' => $uuid]) }}" class="table-button edit-button"><i class="fas fa-pencil-alt"></i> Editer</a>';
        $html .= '<a href="{{ route(\''.$this->route.'delete\', [\'uuid\' => $uuid]) }}" class="table-button delete-button confirm-alert"><i class="fas fa-trash"></i> Supprimer</a>';
        return $html;
    }
}
