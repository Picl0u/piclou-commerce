<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Picl0u\FormTranslate\FormTranslate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Modules\Order\Entities\Status;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;
use Modules\Order\Http\Requests\Status as StatusRequest;

class AdminStatusController extends Controller
{

    /**
     * @var string
     */
    private $viewPath = 'order::admin.status.';

    private $route = 'orders.status.';

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
        $data = new Status();

        return view($this->viewPath . 'create', compact('data'));
    }


    /**
     * @param StatusRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StatusRequest $request)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }


        $orderAccept = 0;
        if ($request->order_accept == "on") {
            $orderAccept = 1;
        }
        $orderRefuse = 0;
        if ($request->order_refuse == "on") {
            $orderRefuse = 1;
        }

        $status = Status::create([
            "uuid" => Uuid::uuid4()->toString(),
            'color' => $request->color,
            'order_accept' => $orderAccept,
            'order_refuse' => $orderRefuse,
        ]);

        $status->setTranslation('name', config('app.locale'), $request->name)
            ->update();

        session()->flash('sucess',"Votre statut pour les commandes a bien été créé.");
        return redirect()->route($this->route . 'index');
    }

    /**
     * @param string $uuid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(string $uuid)
    {
        $data = Status::where('uuid', $uuid)->firstOrFail();
        return view($this->viewPath . 'edit',compact('data'));
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

        $status = Status::where('uuid', $uuid)->firstOrFail();

        $orderAccept = 0;
        if ($request->order_accept == "on") {
            $orderAccept = 1;
        }
        $orderRefuse = 0;
        if ($request->order_refuse == "on") {
            $orderRefuse = 1;
        }
        Status::where('id', $status->id)->update([
            'color' => $request->color,
            'order_accept' => $orderAccept,
            'order_refuse' => $orderRefuse,
        ]);


        $status->setTranslation('name', config('app.locale'), $request->name)
            ->update();

        session()->flash('sucess',"Votre statut pour les commandes a bien été modifié.");
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

        $status = Status::where('uuid', $uuid)->firstOrFail();
        Status::where('id', $status->id)->delete();

        session()->flash('sucess',"Votre statut pour les commandes a bien été supprimé.");
        return redirect()->route($this->route . 'index');
    }

    /**
     * Traductions
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function translate(Request $request)
    {
        return (new FormTranslate(Status::class))->formRequest($request);
    }

    /**
     * @return mixed
     */
    private function dataTable()
    {
        $status = Status::select(['id','uuid','name','color','updated_at']);
        return DataTables::of($status)
            ->addColumn('actions', $this->getTableButtons())
            ->editColumn('name', function(Status $status){
                return $status->getTranslation('name', config('app.locale'));
            })
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
