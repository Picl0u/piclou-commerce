<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\User\Entities\User;
use Illuminate\Http\Request;
use Modules\User\Http\Requests\UserRequest;
use Modules\User\Http\Requests\UserUpdateRequest;
use Ramsey\Uuid\Uuid;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class AdminUsersController extends Controller{


    /**
     * @var string
     */
    protected $viewPath = 'admin::users.';

    /**
     * @var string
     */
    protected $route = 'admin.admin.';

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
        $data = new User();
        $roles = Role::where('guard_name', '!=', 'super_admin')->get();
        return view($this->viewPath . 'create', compact('data', 'roles'));
    }


    /**
     * @param UserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request)
    {

        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $online = 0;
        if ($request->online == "on") {
            $online = 1;
        }

        $role = Role::where('id',$request->role_id)->first();

        $newsletter = 0;

        $insert = [
            'uuid' => Uuid::uuid4()->toString(),
            'online' => $online,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => str_slug($request->firstname."-".$request->lastname),
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'admin',
            'gender' => $request->gender,
            'newsletter' => $newsletter,
            'role_id' => $request->role_id,
            'guard_name' => $role->name
        ];

        $user = User::create($insert);
        $user->assignRole($role->name);

        session()->flash('success',"L'administrateur a bien été créé.");
        return redirect()->route($this->route . 'index');
    }


    /**
     * @param string $uuid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(string $uuid)
    {
        $data = User::where('uuid', $uuid)->firstOrFail();
        $roles = Role::where('guard_name', '!=', 'super_admin')->get();
        return view($this->viewPath . 'edit',compact('data', 'roles'));
    }

    /**
     * @param UserUpdateRequest $request
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserUpdateRequest $request, string $uuid)
    {

        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $user = User::where('uuid', $uuid)->firstOrFail();

        $online = 0;
        if ($request->online == "on") {
            $online = 1;
        }

        $role = Role::where('id',$request->role_id)->first();

        $update = [
            'online' => $online,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => str_slug($request->firstname."-".$request->lastname),
            'email' => $request->email,
            'gender' => $request->gender,
            'role_id' => $request->role_id,
            'guard_name' => $role->name
        ];

        if(!empty($request->password)) {
            $update['password'] = bcrypt($this->password);
        }

        User::where('id', $user->id)->update($update);
        $user->removeRole($role->guard_name);
        $user->assignRole($role->name);

        session()->flash('success',"L'administrateur a bien été modifié.");
        return redirect()->route($this->route  . 'index');

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

        $user = User::where('uuid', $uuid)->firstOrFail();
        User::where('id', $user->id)->delete();

        session()->flash('success',"L'administrateur a bien été supprimé.");
        return redirect()->route($this->route  . 'index');
    }

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

    private function dataTable()
    {
        $user = User::select(['id','uuid','firstname','lastname','email','updated_at'])
            ->where('role','admin')
            ->where('guard_name','!=','super_admin');
        return DataTables::of($user)
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
        $html = '<a href="{{ route(\''.$this->route .'edit\', [\'uuid\' => $uuid]) }}" class="table-button edit-button"><i class="fas fa-pencil-alt"></i> Editer</a>';
        $html .= '<a href="{{ route(\''.$this->route .'delete\', [\'uuid\' => $uuid]) }}" class="table-button delete-button confirm-alert"><i class="fas fa-trash"></i> Supprimer</a>';
        return $html;
    }

}
