<?php

namespace Modules\Vat\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Vat\Http\Requests\Vats;
use Modules\Vat\Entities\Vat;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class AdminVatController extends Controller
{
    /**
     * @var string
     */
    protected $viewPath = 'vat::admin.';

    protected $route = 'shop.vats.';

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
        $data = new Vat();
        return view($this->viewPath.'create', compact('data'));
    }

    /**
     * @param Vats $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Vats $request)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        Vat::create([
            'uuid' => Uuid::uuid4()->toString(),
            'name' => $request->name,
            'percent' => $request->percent
        ]);

        session()->flash('success',"La taxe a bien été ajoutée.");
        return redirect()->route($this->route . 'index');

    }

    /**
     * @param string $uuid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(string $uuid)
    {
        $data = Vat::where('uuid',$uuid)->FirstOrFail();
        return view($this->viewPath . 'edit', compact('data'));
    }

    /**
     * @param Vats $request
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Vats $request, string $uuid)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $vat = Vat::where('uuid',$uuid)->FirstOrFail();
        Vat::where('id',$vat->id)->update([
            'name' => $request->name,
            'percent' => $request->percent
        ]);
        session()->flash('success',"La taxe a bien été modifiée.");
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

        $vat = Vat::where('uuid',$uuid)->FirstOrFail();
        Vat::where('id',$vat->id)->delete();
        session()->flash('success',"La taxe a bien été supprimée.");
        return redirect()->route($this->route . 'index');
    }

    /**
     * @return mixed
     */
    private function datatable()
    {
        $categories = Vat::select(['id','uuid','name','percent','updated_at']);
        return DataTables::of($categories)
            ->addColumn('actions', $this->getTableButtons())
            ->editColumn('percent','{{$percent}}%')
            ->editColumn('updated_at', 'admin.datatable.updatedAt')
            ->rawColumns(['actions'])
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
