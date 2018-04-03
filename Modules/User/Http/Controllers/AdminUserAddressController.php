<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\User\Entities\User;
use Modules\User\Entities\UsersAdresses;
use Illuminate\Http\Request;
use Modules\Order\Entities\Countries;
use Modules\User\Http\Requests\AddressRequest;
use Modules\User\Http\Requests\UserRequest;
use Modules\User\Http\Requests\UserUpdateRequest;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class AdminUserAddressController extends Controller{


    /**
     * @var string
     */
    protected $viewPath = 'user::admin.addresses.';

    protected $route = 'admin.addresses.';

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
        $data = new UsersAdresses();
        $users = User::where('role','user')->get();
        $countries = Countries::where('activated', 1)->get();
        return view($this->viewPath . 'create', compact('data', 'users', 'countries'));
    }


    /**
     * @param AddressRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AddressRequest $request)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $billing = 0;
        if($request->billing == 'on') {
            $billing = 1;
        }

        UsersAdresses::create([
            'uuid' => Uuid::uuid4()->toString(),
            'user_id' => $request->user_id,
            'gender' => $request->gender,
            'delivery' => 1,
            'billing' => $billing,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'address' => $request->address,
            'additional_address' => $request->additional_address,
            'zip_code' => $request->zip_code,
            'city' => $request->city,
            'phone' => $request->phone,
            'country_id' => $request->country_id
        ]);

        session()->flash('success',"L'adresse a bien été créée.");
        return redirect()->route($this->route . 'index');
    }


    /**
     * @param string $uuid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(string $uuid)
    {
        $data = UsersAdresses::where('uuid', $uuid)->firstOrFail();
        $users = User::where('role','user')->get();
        $countries = Countries::where('activated', 1)->get();

        return view($this->viewPath . 'edit', compact('data', 'users', 'countries'));
    }

    /**
     * @param AddressRequest $request
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AddressRequest $request, string $uuid)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $address = UsersAdresses::where('uuid', $uuid)->firstOrFail();

        $billing = 0;
        if($request->billing == 'on') {
            $billing = 1;
        }

        UsersAdresses::where('id', $address->id)->update([
            'user_id' => $request->user_id,
            'gender' => $request->gender,
            'delivery' => 1,
            'billing' => $billing,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'address' => $request->address,
            'additional_address' => $request->additional_address,
            'zip_code' => $request->zip_code,
            'city' => $request->city,
            'phone' => $request->phone,
            'country_id' => $request->country_id
        ]);


        session()->flash('success',"L'adresse a bien été modifiée.");
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

        $address = UsersAdresses::where('uuid', $uuid)->firstOrFail();
        UsersAdresses::where('id', $address->id)->delete();

        session()->flash('success',"L'adresse a bien été supprimée.");
        return redirect()->route($this->route . 'index');
    }

    /**
     * @return mixed
     */
    private function dataTable()
    {
        $addresses = UsersAdresses::select([
            'id',
            'uuid',
            'firstname',
            'lastname',
            'phone',
            'address',
            'additional_address',
            'zip_code',
            'city',
            'updated_at'
        ]);
        return DataTables::of($addresses)
            ->editColumn('address', function(UsersAdresses $address){
                return $address->address.' '.$address->additional_address.' - '.$address->zip_code.' '.$address->city;
            })
            ->addColumn('actions', $this->getTableButtons())
            ->editColumn('updated_at', 'admin.datatable.updatedAt')
            ->rawColumns(['actions', 'address'])
            ->make(true);
    }

    /**
     * @return string
     */
    private function getTableButtons(): string
    {
        $html = '<a href="{{ route(\''.$this->route.'edit\', [\'uuid\' => $uuid]) }}" class="table-button edit-button"><i class="fas fa-pencil-alt"></i> Editer</a>';
        $html .= '<a href="{{ route(\''.$this->route.'delete\', [\'uuid\' => $uuid]) }}" class="table-button delete-button confirm-alert"><i class="fas fa-trash"></i> Supprimer</a>';
        return $html;
    }

}