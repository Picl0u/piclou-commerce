<?php

namespace Modules\Newsletter\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Newsletter\Entities\Newsletters;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Newsletter\Http\Requests\Register;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class AdminNewsletterController extends Controller
{
    /**
     * @var string
     */
    protected $viewPath = 'newsletter::admin.newsletter.';
    /**
     * @var string
     */
    protected $route = 'admin.newsletter.';

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
        $data = new Newsletters();

        return view($this->viewPath . 'create', compact('data'));
    }


    /**
     * @param Register $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Register $request)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $activated = 0;
        if ($request->active == "on") {
            $activated = 1;
        }

        Newsletters::create([
            'uuid' => Uuid::uuid4()->toString(),
            'active' => $activated,
            'email' => $request->email,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
        ]);

        session()->flash('success',"L'inscription a bien été créée.");
        return redirect()->route($this->route . 'index');
    }

    public function edit(string $uuid)
    {
        $data = Newsletters::where('uuid', $uuid)->FirstOrFail();

        return view($this->viewPath . 'edit', compact('data'));

    }

    public function update(Register $request, string $uuid)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $newsletter = Newsletters::where('uuid', $uuid)->FirstOrFail();

        $activated = 0;
        if ($request->active == "on") {
            $activated = 1;
        }

        Newsletters::where('id', $newsletter->id)->update([
            'active' => $activated,
            'email' => $request->email,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
        ]);

        session()->flash('success',"L'inscription a bien été modifiée.");
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

        $newsletter = Newsletters::where('uuid', $uuid)->FirstOrFail();

        Newsletters::where('id', $newsletter->id)->delete();

        session()->flash('success',"L'inscription a bien été supprimée.");
        return redirect()->route($this->route . 'index');
    }

    /**
     * @return mixed
     */
    public function export()
    {
        $newsletters = Newsletters::where('active', 1)
            ->select('firstname','lastname','email')
            ->orderBy('id','desc')
            ->get()
            ->toArray();

        return Excel::create('export-newsletter'.now(), function($excel) use ($newsletters) {
            $excel->setTitle('Export Newsletter'.date('d/m/Y à H:i'));

            $excel->sheet('Sheetname', function($sheet) use ($newsletters) {
                    $sheet->fromArray($newsletters);
            });
        })->download('csv');
    }


    /**
     * @return mixed
     */
    private function dataTable()
    {
        $newsletters = Newsletters::select(['id','uuid','active','firstname','lastname','email','updated_at']);
        return DataTables::of($newsletters)
            ->addColumn('actions', $this->getTableButtons())
            ->editColumn('active', 'admin.datatable.active')
            ->editColumn('updated_at', 'admin.datatable.updatedAt')
            ->rawColumns(['actions','active'])
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
