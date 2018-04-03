<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Order\Entities\Countries;
use Yajra\DataTables\DataTables;

class AdminCountriesController extends Controller
{
    /**
     * @var string
     */
    protected $viewPath = 'order::admin.countries.';
    /**
     * @var string
     */
    protected $route = 'orders.countries.';

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
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activate(int $id)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $country = Countries::where('id', $id)->firstOrFail();
        Countries::where('id', $country->id)->update([
           "activated" => 1
        ]);
        session()->flash('success','Le pays a bien été activé pour vos livraisons.');
        return redirect()->route('orders.countries.index');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function desactivate(int $id)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $country = Countries::where('id', $id)->firstOrFail();
        Countries::where('id', $country->id)->update([
            "activated" => 0
        ]);
        session()->flash('success','Le pays a bien été désactivé pour vos livraisons.');
        return redirect()->route($this->route . '.index');
    }

    /**
     * @return mixed
     */
    private function dataTable()
    {
        $countries = Countries::select(['id','activated','name','iso_3166_2','currency_symbol']);
        return DataTables::of($countries)
            ->addColumn('actions', function(Countries $country) {
               return $this->getTableButtons($country);
            })
            ->editColumn('activated', 'admin.datatable.activated')
            ->rawColumns(['actions', 'activated'])
            ->make(true);
    }

    /**
     * @param $country
     * @return string
     */
    private function getTableButtons($country): string
    {
        if(empty($country->activated)){
            $html = '<a href="'.route($this->route . 'activate', ['id' => $country->id]) .'" 
                        class="table-button edit-button"
                    >
                        <i class="fas fa-check"></i> Activer
                    </a>';
        } else {
            $html = '<a href="'.route($this->route . 'desactivate', ['id' => $country->id]) .'" 
                        class="table-button delete-button"
                    >
                        <i class="fas fa-times"></i> Desactiver
                    </a>';
        }
        return $html;
    }

}
