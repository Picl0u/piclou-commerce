<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\RoleRequest;
use Ramsey\Uuid\Uuid;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RolesController extends Controller
{
    /**
     * @var string
     */
    protected $viewPath = 'admin::roles.';

    /**
     * @var string
     */
    protected $route = 'admin.roles.';

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
        $data = new Role();

        return view($this->viewPath . 'create', compact('data'));
    }

    /**
     * @param RoleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RoleRequest $request)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        Role::create([
            'uuid' => Uuid::uuid4()->toString(),
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);

        session()->flash('success',"Le rôle a bien été créé.");
        return redirect()->route($this->route . 'index');
    }

    /**
     * @param string $uuid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(string $uuid)
    {
        $data = Role::where('uuid', $uuid)->FirstOrFail();

        return view($this->viewPath . 'edit', compact('data'));

    }

    /**
     * @param RoleRequest $request
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RoleRequest $request, string $uuid)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }
        $role = Role::where('uuid', $uuid)->FirstOrFail();

        Role::where('id', $role->id)->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);

        session()->flash('success',"Le role a bien été modifié.");
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
        $role = Role::where('uuid', $uuid)->FirstOrFail();

        Role::where('id', $role->id)->delete();

        session()->flash('success',"Le rôle a bien été supprimé.");
        return redirect()->route($this->route . 'index');
    }


    /**
     * @return mixed
     */
    private function dataTable()
    {
        $role = Role::select(['id','uuid','name','updated_at']);
        return DataTables::of($role)
            ->addColumn('actions', $this->getTableButtons())
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
