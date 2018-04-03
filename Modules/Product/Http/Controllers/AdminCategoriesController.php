<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Picl0u\DeleteCache;
use App\Http\Picl0u\FormTranslate\FormTranslate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Modules\Product\Http\Requests\ShopCategories;
use Modules\Product\Entities\ShopCategory;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class AdminCategoriesController extends Controller
{

    use DeleteCache;
    /**
     * @var string
     */
    private $viewPath = 'product::admin.categories.';

    private $route = 'shop.categories.';

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
        $data = new ShopCategory();
        return view($this->viewPath.'create',compact('data'));
    }


    /**
     * @param ShopCategories $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ShopCategories $request)
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
        if ($request->published == "on") {
            $published = 1;
        }
        if ($request->on_homepage == "on") {
            $onHomepage = 1;
        }

        $insertImage = '';
        if($request->hasFile('image')){
            $insertImage = uploadImage('shop/categories', $request->image);
        }

        $insertImageList = '';
        if($request->hasFile('imageList')){
            $insertImageList = uploadImage('shop/categories', $request->imageList);
        }

        $category = ShopCategory::create([
            'uuid' => Uuid::uuid4()->toString(),
            'published' => $published,
            'on_homepage' => $onHomepage,
            'image' => $insertImage,
            'imageList' => $insertImageList,
            'order' => (shopCategory::count()+1)
        ]);

        $category
            ->setTranslation('name', config('app.locale'), $request->name)
            ->setTranslation('slug', config('app.locale'), $request->slug)
            ->setTranslation('description', config('app.locale'), $request->description)
            ->setTranslation('seo_title', config('app.locale'), $request->seo_title)
            ->setTranslation('seo_description', config('app.locale'), $request->seo_description)
            ->update();

        session()->flash('success',"La catégorie a bien été créée.");
        return redirect()->route($this->route . 'index');

    }

    /**
     * @param string $uuid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(string $uuid)
    {
        $data = ShopCategory::where('uuid', $uuid)->firstOrFail();
        return view($this->viewPath.'edit', compact('data'));
    }

    /**
     * @param ShopCategories $request
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ShopCategories $request, string $uuid)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $category = ShopCategory::where('uuid', $uuid)->firstOrFail();

        if(empty($request->slug)){
            $request->slug = str_slug($request->name);
        }else{
            $request->slug = str_slug($request->slug);
        }

        $published = 0;
        $onHomepage = 0;
        if ($request->published == "on") {
            $published = 1;
        }
        if ($request->on_homepage == "on") {
            $onHomepage = 1;
        }

        $insertImage = $category->image;
        if($request->hasFile('image')){
            $insertImage = uploadImage('shop/categories', $request->image);
            if(!empty($category->image) && file_exists($category->image)) {
                unlink($category->image);
            }
        }

        $insertImageList = '';
        if($request->hasFile('imageList')){
            $insertImageList = uploadImage('shop/categories', $request->imageList);
            if(!empty($category->imageList) && file_exists($category->imageList)) {
                unlink($category->imageList);
            }
        }

        $this->flush('product', $category->id);

        ShopCategory::where('id',$category->id)->update([
            'published' => $published,
            'on_homepage' => $onHomepage,
            'image' => $insertImage,
            'imageList' => $insertImageList
        ]);
        $category
            ->setTranslation('name', config('app.locale'), $request->name)
            ->setTranslation('slug', config('app.locale'), $request->slug)
            ->setTranslation('description', config('app.locale'), $request->description)
            ->setTranslation('seo_title', config('app.locale'), $request->seo_title)
            ->setTranslation('seo_description', config('app.locale'), $request->seo_description)
            ->update();

        session()->flash('success',"La catégorie a bien été modifiée.");
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

        $category = ShopCategory::where('uuid', $uuid)->firstOrFail();
        ShopCategory::where("id", $category->id)->delete();

        session()->flash('success',"La catégorie a bien été supprimée.");
        return redirect()->route('shop.categories.index');
    }

    public function imageDelete(int $id)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $category = ShopCategory::where('id', $id)->firstOrFail();
        $category->update([
            'image' => null
        ]);
        if(!empty($category->image) && file_exists($category->image)) {
            unlink($category->image);
        }
        session()->flash('success', 'Votre image a bien été supprimée.');
        return redirect()->route($this->route . 'edit',['uuid' => $category->uuid]);
    }

    public function imageListDelete(int $id)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $category = ShopCategory::where('id', $id)->firstOrFail();
        $category->update([
            'imageList' => null
        ]);
        if(!empty($category->imageList) && file_exists($category->imageList)) {
            unlink($category->imageList);
        }

        session()->flash('success', 'Votre image a bien été supprimée.');
        return redirect()->route($this->route . 'edit',['uuid' => $category->uuid]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function positions(){
        $categories = ShopCategory::orderBy('order','asc')->get();
        $datas = [];
        foreach($categories as $data){
            $datas[] = [
                'id' => $data->id,
                'name' => $data->getTranslation('name', config('app.locale')),
                'order' => $data->order,
                'parent_id' => $data->parent_id,
                'slug' => $data->getTranslation('slug', config('app.locale')),
            ];
        }
        return view($this->viewPath.'positions',compact('datas'));
    }

    public function positionsStore(Request $request)
    {
        if(config('ikCommerce.demo')) {
            return "Cette fonctionnalité a été désactivée pour la version démo.";
        }

        $datas = ShopCategory::all();
        $dataArray = [];
        foreach ($datas as $data) {
            $dataArray[$data->id] = [
                'parent_id' => $data->parent_id,
                'order' => $data->order
            ];
        }
        foreach ($request->orders as $key => $order) {
            if (!empty($order['id'])) {
                if ($dataArray[$order['id']]['order'] != $key) {
                    // Si la position est différente
                    ShopCategory::where('id', $order['id'])->update([
                        'order' => $key,
                        'parent_id' => $order['parent_id']
                    ]);
                } elseif ($dataArray[$order['id']]['parent_id'] != $order['parent_id']) {
                    // Si le parent est différent
                    ShopCategory::where('id', $order['id'])->update([
                        'order' => $key,
                        'parent_id' => $order['parent_id']
                    ]);
                }
            }
        }

        return "Les positions ont bien été mises à jours";
    }

    /**
     * Traductions
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function translate(Request $request)
    {
        return (new FormTranslate(ShopCategory::class))->formRequest($request);
    }

    /**
     * @return mixed
     */
    private function dataTable()
    {
        $categories = ShopCategory::select(['id','published','uuid','image','name','updated_at']);
        return DataTables::of($categories)
            ->editColumn('published', 'admin.datatable.published')
            ->editColumn('image', 'admin.datatable.image')
            ->editColumn('updated_at', 'admin.datatable.updatedAt')
            ->editColumn('name',function(ShopCategory $category) {
                return $category->getTranslation('name',config('app.locale'));
            })
            ->addColumn('actions', $this->getTableButtons())
            ->rawColumns(['actions','published','image'])
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
