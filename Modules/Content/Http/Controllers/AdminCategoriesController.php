<?php

namespace Modules\Content\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Picl0u\FormTranslate\FormTranslate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Modules\Content\Entities\ContentCategory;
use Modules\Content\Http\Requests\ContentCategories;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class AdminCategoriesController extends Controller
{

    /**
     * @var string
     */
    protected $viewPath = 'content::admin.categories.';

    /**
     * @var string
     */
    protected $route = 'admin.pages.categories.';

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
        $data = new ContentCategory();
        return view($this->viewPath . 'create', compact('data'));
    }


    /**
     * @param ContentCategories $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ContentCategories $request)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $on_footer = 0;
        if($request->on_footer == 'on') {
            $on_footer = 1;
        }
        $category = ContentCategory::create([
            'uuid' => Uuid::uuid4()->toString(),
            'on_footer' => $on_footer
        ]);

        $category
            ->setTranslation('name', config('app.locale'), $request->name)
            ->update();

        session()->flash('success',__("content::admin.category_create_success"));
        return redirect()->route($this->route . 'index');
    }
    /**
     * @param string $uuid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(string $uuid)
    {
        $data = ContentCategory::where('uuid', $uuid)->FirstOrFail();
        return view($this->viewPath . 'edit', compact('data'));

    }


    /**
     * @param ContentCategories $request
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ContentCategories $request, string $uuid)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $category = ContentCategory::where('uuid', $uuid)->FirstOrFail();
        $on_footer = 0;
        if($request->on_footer == 'on') {
            $on_footer = 1;
        }
        ContentCategory::where('id', $category->id)->update([
            'on_footer' => $on_footer
        ]);
        $category
            ->setTranslation('name', config('app.locale'), $request->name)
            ->update();

        session()->flash('success',__("content::admin.category_edit_success"));
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

        $category = ContentCategory::where('uuid', $uuid)->FirstOrFail();

        ContentCategory::where('id', $category->id)->delete();
        session()->flash('success',__("content::admin.category_delete_success"));
        return redirect()->route($this->route . 'index');
    }

    /**
    * Traductions
    * @param Request $request
    * @return \Illuminate\Http\JsonResponse
    */
    public function translate(Request $request)
    {
        return (new FormTranslate(ContentCategory::class))->formRequest($request);
    }


    /**
     * @return mixed
     */
    private function dataTable()
    {
        $categories = ContentCategory::select(['id','uuid','name','updated_at']);
        return DataTables::of($categories)
            ->addColumn('actions', $this->getTableButtons())
            ->editColumn('name', function(ContentCategory $category){
                return $category->getTranslation('name', config('app.locale'));
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
        $html = '<a href="{{ route(\''.$this->route . 'edit\', [\'uuid\' => $uuid]) }}" class="table-button edit-button"><i class="fas fa-pencil-alt"></i> '.__("admin::actions.edit").'</a>';
        $html .= '<a href="{{ route(\''.$this->route . 'delete\', [\'uuid\' => $uuid]) }}" class="table-button delete-button confirm-alert"><i class="fas fa-trash"></i> '.__("admin::actions.delete").'</a>';
        return $html;
    }
}
