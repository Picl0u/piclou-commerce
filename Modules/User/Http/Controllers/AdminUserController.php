<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\User\Entities\User;
use Illuminate\Http\Request;
use Modules\User\Http\Requests\UserRequest;
use Modules\User\Http\Requests\UserUpdateRequest;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class AdminUserController extends Controller{


    /**
     * @var string
     */
    protected $viewPath = 'user::admin.users.';
    protected $route = 'admin.users.';

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
        $data = new User();

        return view($this->viewPath . 'create', compact('data'));
    }


    /**
     * @param UserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request)
    {

        $online = 0;
        if ($request->online == "on") {
            $online = 1;
        }

        $newsletter = 0;
        if ($request->newsletter == "on") {
            $newsletter = 1;
        }

        User::create([
            'uuid' => Uuid::uuid4()->toString(),
            'online' => $online,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => str_slug($request->firstname."-".$request->lastname),
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'user',
            'gender' => $request->gender,
            'newsletter' => $newsletter,
        ]);


        session()->flash('success',"L'utilisateur a bien été créé.");
        return redirect()->route($this->route . 'index');
    }


    /**
     * @param string $uuid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(string $uuid)
    {
        $data = User::where('uuid', $uuid)->firstOrFail();
        return view($this->viewPath . 'edit',compact('data'));
    }

    /**
     * @param UserUpdateRequest $request
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserUpdateRequest $request, string $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();

        $online = 0;
        if ($request->online == "on") {
            $online = 1;
        }

        $newsletter = 0;
        if ($request->newsletter == "on") {
            $newsletter = 1;
        }

        $update = [
            'online' => $online,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => str_slug($request->firstname."-".$request->lastname),
            'email' => $request->email,
            'gender' => $request->gender,
            'newsletter' => $newsletter,
        ];

        if(!empty($request->password)) {
            $update['password'] = bcrypt($this->password);
        }

        User::where('id', $user->id)->update($update);


        session()->flash('success',"L'utilisateur a bien été modifié.");
        return redirect()->route($this->route . 'index');

    }

    /**
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        User::where('id', $user->id)->delete();

        session()->flash('success',"L'utilisateur a bien été supprimé.");
        return redirect()->route($this->route . 'index');
    }

    private function dataTable()
    {
        $user = User::select(['id','uuid','firstname','lastname','email','updated_at'])
            ->where('role','user');
        return DataTables::of($user)
            ->addColumn('orders',function(User $user){
                return '<span class="label success">' . count($user->Orders) . '</span>';
            })
            ->addColumn('actions', $this->getTableButtons())
            ->editColumn('updated_at', 'admin.datatable.updatedAt')
            ->rawColumns(['actions', 'orders'])
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