<?php

namespace Modules\Content\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Picl0u\DeleteCache;
use App\Http\Picl0u\FormTranslate\FormTranslate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Modules\Content\Entities\Content;
use Modules\Content\Entities\ContentCategory;
use Modules\Content\Http\Requests\Contents;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class AdminContentsController extends Controller
{

    use DeleteCache;

    /**
     * @var string
     */
    private $viewPath = 'content::admin.contents.';

    /**
     * @var string
     */
    private $route = 'admin.pages.contents.';

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
        $data = new Content();

        $cats = ContentCategory::select('id','name')->get();

        $categories = [];
        foreach($cats as $category) {
            $categories[$category->id] = $category->name;
        }
        return view($this->viewPath . 'create', compact('data', 'categories'));
    }


    /**
     * @param Contents $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Contents $request)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        if(empty($request->slug)){
            $request->slug = str_slug($request->name);
        }else{
            $request->slug = str_slug($request->slug);
        }

        $published = 0;
        $onHomepage = 0;
        $onMenu = 0;
        $onFooter = 0;
        if ($request->published == "on") {
            $published = 1;
        }
        if ($request->on_homepage == "on") {
            $onHomepage = 1;
        }
        if ($request->on_menu == "on") {
            $onMenu = 1;
        }
        if ($request->on_footer == "on") {
            $onFooter = 1;
        }

        $insertImage = '';
        if($request->hasFile('image')){
            $insertImage = uploadImage('pages', $request->image);
        }

        $content = Content::create([
            'uuid' => Uuid::uuid4()->toString(),
            'published' => $published,
            'on_homepage' => $onHomepage,
            'on_menu' => $onMenu,
            'on_footer' => $onFooter,
            'content_category_id' => $request->content_category_id,
            'image' => $insertImage,
            'order' => (Content::count()+1)
        ]);

        $content
            ->setTranslation('name', config('app.locale'), $request->name)
            ->setTranslation('slug', config('app.locale'), $request->slug)
            ->setTranslation('summary', config('app.locale'), $request->summary)
            ->setTranslation('description', config('app.locale'), $request->description)
            ->setTranslation('seo_title', config('app.locale'), $request->seo_title)
            ->setTranslation('seo_description', config('app.locale'), $request->seo_description)
            ->update();

        session()->flash('success', __("content::admin.content_create_success"));
        return redirect()->route($this->route . 'index');
    }

    /**
     * @param string $uuid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(string $uuid)
    {
        $data = Content::where('uuid', $uuid)->FirstOrFail();

        $cats = ContentCategory::select('id','name')->get();

        $categories = [];
        foreach($cats as $category) {
            $categories[$category->id] = $category->name;
        }

        return view($this->viewPath . 'edit', compact('data','categories'));

    }

    /**
     * @param Contents $request
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Contents $request, string $uuid)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $content = Content::where('uuid', $uuid)->FirstOrFail();

        if(empty($request->slug)){
            $request->slug = str_slug($request->name);
        }else{
            $request->slug = str_slug($request->slug);
        }

        $published = 0;
        $onHomepage = 0;
        $onMenu = 0;
        $onFooter = 0;
        if ($request->published == "on") {
            $published = 1;
        }
        if ($request->on_homepage == "on") {
            $onHomepage = 1;
        }
        if ($request->on_menu == "on") {
            $onMenu = 1;
        }
        if ($request->on_footer == "on") {
            $onFooter = 1;
        }

        $insertImage = $content->image;
        if($request->hasFile('image')){
            $insertImage = uploadImage('pages', $request->image);
            if(!empty($content->image)) {
                unlink($content->image);
            }
        }

        Content::where('id', $content->id)->update([
            'published' => $published,
            'on_homepage' => $onHomepage,
            'on_menu' => $onMenu,
            'on_footer' => $onFooter,
            'content_category_id' => $request->content_category_id,
            'image' => $insertImage,
        ]);


        $content
            ->setTranslation('name', config('app.locale'), $request->name)
            ->setTranslation('slug', config('app.locale'), $request->slug)
            ->setTranslation('summary', config('app.locale'), $request->summary)
            ->setTranslation('description', config('app.locale'), $request->description)
            ->setTranslation('seo_title', config('app.locale'), $request->seo_title)
            ->setTranslation('seo_description', config('app.locale'), $request->seo_description)
            ->update();

        /* Supprimer le cache */
        $this->flush('content', $content->id);

        session()->flash('success', __("content::admin.content_edit_success"));
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

        $content = Content::where('uuid', $uuid)->FirstOrFail();

        /* Supprimer le cache */
        $this->flush('content', $content->id);

        Content::where('id', $content->id)->delete();

        if(!empty($content->image)) {
            unlink($content->image);
        }
        session()->flash('success', __("content::admin.content_delete_success"));
        return redirect()->route($this->route . 'index');
    }

    /**
     * Traductions
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function translate(Request $request)
    {
        return (new FormTranslate(Content::class))->formRequest($request);
    }


    public function positions(){

        $products = Content::OrderBy('order','asc')->get();
        $datas = [];
        foreach($products as $data){
            $datas[] = [
                'id' => $data->id,
                'name' => $data->getTranslation('name', config('app.locale')),
                'order' => $data->order,
                'parent_id' => 0,
                'slug' => '',
            ];
        }
        return view($this->viewPath.'positions',compact('datas'));
    }

    public function positionsStore(Request $request)
    {
        if(config('ikCommerce.demo')) {
            return "Cette fonctionnalité a été désactivée pour la version démo.";
        }

        $datas = Content::all();
        $dataArray = [];
        foreach ($datas as $data) {
            $dataArray[$data->id] = [
                'order' => $data->order
            ];
        }
        foreach ($request->orders as $key => $order) {
            if (!empty($order['id'])) {
                if ($dataArray[$order['id']]['order'] != $key) {
                    Content::where('id', $order['id'])->update([
                        'order' => $key,
                    ]);
                }
            }
        }

        return "Les positions ont bien été mises à jours";
    }


    /**
     * @return mixed
     */
    private function dataTable()
    {
        $contents = Content::select(['id','uuid','published','name','image','content_category_id','updated_at']);
        return DataTables::of($contents)
            ->addColumn('actions', $this->getTableButtons())
            ->editColumn('name', function(Content $contents){
                return $contents->getTranslation('name', config('app.locale'));
            })
            ->editColumn('published', 'admin.datatable.published')
            ->editColumn('image', 'admin.datatable.image')
            ->editColumn('updated_at', 'admin.datatable.updatedAt')
            ->editColumn('content_category_id',function(Content $content){
                if(!empty($content->content_category_id))
                {
                    if($content->ContentCategory){
                        return $content->ContentCategory->name;
                    } else {
                        return '';
                    }
                }
                return "";
            })
            ->rawColumns(['actions','published','image'])
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
