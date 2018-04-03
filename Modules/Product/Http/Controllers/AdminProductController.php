<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Picl0u\DeleteCache;
use App\Http\Picl0u\FormTranslate\FormTranslate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductsAssociate;
use Modules\Product\Entities\ProductsAttribute;
use Modules\Product\Entities\ProductsHasCategory;
use Modules\Product\Entities\ProductsHasMedia;
use Modules\Product\Http\Requests\ProductImports;
use Modules\Product\Http\Requests\Products;
use Modules\Product\Entities\ShopCategory;
use Modules\Vat\Entities\Vat;
use paylineSDK\pl_recurring;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;
use \Maatwebsite\Excel\Facades\Excel;
use \File;

class AdminProductController extends Controller
{
    use DeleteCache;

    /**
     * @var string
     */
    private $viewPath = 'product::admin.products.';

    protected $route = 'shop.products.';

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
        $data = new Product();
        $vats = Vat::select('id','name','percent')->get();

        $products = Product::select('id','name','reference')
            ->orderBy("name",'asc')
            ->get();

        $shopCategories = ShopCategory::select('id','name','parent_id')->get();
        $categories_array = [];
        $categories = [];
        foreach ($shopCategories as $category) {
            $categories_array[$category->id] = $category->getTranslation('name',config('app.locale'));
            $categories[] = [
                'id' => $category->id,
                'name' => $category->getTranslation('name',config('app.locale')),
                'parent_id' => $category->parent_id
            ];
        }
        return view($this->viewPath . 'create', compact(
            'data', 'categories', 'vats', 'categories_array', 'products'
        ));
    }


    /**
     * @param Products $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Products $request)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        if (empty($request->slug)) {
            $request->slug = str_slug($request->name);
        } else {
            $request->slug = str_slug($request->slug);
        }

        $published = 0;
        if ($request->published == "on") {
            $published = 1;
        }

        $weekSelection = 0;
        if ($request->week_selection == "on") {
            $weekSelection = 1;
        }

        $product = Product::create([
            'uuid' => Uuid::uuid4()->toString(),
            'published' => $published,
            'shop_category_id' => $request->shop_category_id,
            'reference' => $request->reference,
            'isbn_code' => $request->isbn_code,
            'ean_code' => $request->ean_code,
            'upc_code' => $request->upc_code,
            'vat_id' => $request->vat_id,
            'price_ht' => $request->price_ht,
            'price_ttc' => $request->price_ttc,
            'reduce_date_begin' => $request->reduce_date_begin,
            'reduce_date_end' => $request->reduce_date_end,
            'reduce_price' => $request->reduce_price,
            'reduce_percent' => $request->reduce_percent,
            'stock_brut' => $request->stock_brut,
            'stock_available' => $request->stock_brut,
            'weight' => $request->weight,
            'height' => $request->height,
            'length' => $request->length,
            'width' => $request->width,
            'order' => (Product::count() + 1),
            'week_selection' => $weekSelection
        ]);


        if ($request->hasFile('image')) {
            $insertImage = $product->setMedias('image', [
                'src' => uploadImage('shop/products', $request->image),
                'alt' => $request->name
            ]);
            $product->update([
                'image' => $insertImage
            ]);
        }

        $product->setTranslation('name', config('app.locale'), $request->name)
            ->setTranslation('slug', config('app.locale'), $request->slug)
            ->setTranslation('summary', config('app.locale'), $request->summary)
            ->setTranslation('description', config('app.locale'), $request->description)
            ->setTranslation('seo_title', config('app.locale'), $request->seo_title)
            ->setTranslation('seo_description', config('app.locale'), $request->seo_description)
            ->update();

        foreach ($request->categories as $category) {
            if (!empty($category)) {
                ProductsHasCategory::create([
                    'shop_category_id' => intval($category),
                    'product_id' => $product->id,
                ]);
            }
        }
        if (!empty($request->associates)) {
            foreach ($request->associates as $associate) {
                if (!empty($associate)) {
                    ProductsAssociate::create([
                        'product_parent' => $product->id,
                        'product_id' => $associate
                    ]);
                }
            }
        }

        if($request->hasFile('images')){
            foreach($request->images as $key => $img){
                $insertImage = uploadImage('shop/products', $img);
                $productMedias = ProductsHasMedia::create([
                    'product_id' => $product->id,
                    'image' => $insertImage,
                    'order' => (ProductsHasMedia::where('product_id',$product->id)->count()+1)
                ]);
                $productMedias->update([
                    'image' => $productMedias->setMedias('image',[
                        'src' => $insertImage,
                        'alt' => $product->name,
                        'order' => $key
                    ])
                ]);
            }
        }

        session()->flash('success',"Le produit a bien été créé.");
        return redirect()->route($this->route . 'index');
    }

    /**
     * @param string $uuid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(string $uuid)
    {
        $data = Product::where('uuid',$uuid)->FirstOrFail();
        $products = Product::select('id','name','reference')
            ->where('id', '!=', $data->id)
            ->orderBy("name",'asc')
            ->get();

        $shopCategories = ShopCategory::select('id','name','parent_id')->get();
        $categories_array = [];
        $categories = [];
        foreach ($shopCategories as $category) {
            $categories_array[$category->id] = $category->getTranslation('name',config('app.locale'));
            $categories[] = [
                'id' => $category->id,
                'name' => $category->getTranslation('name',config('app.locale')),
                'parent_id' => $category->parent_id
            ];
        }
        $vats = Vat::select('id','name','percent')->get();
        return view(
            $this->viewPath . 'edit',
            compact('data', 'categories', 'vats', 'products')
        );
    }


    /**
     * @param Products $request
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Products $request, string $uuid)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $product = Product::where('uuid',$uuid)->FirstOrFail();

        if(empty($request->slug)){
            $request->slug = str_slug($request->name);
        }else{
            $request->slug = str_slug($request->slug);
        }

        $published = 0;
        if ($request->published == "on") {
            $published = 1;
        }

        $weekSelection = 0;
        if ($request->week_selection == "on") {
            $weekSelection = 1;
        }

        $vignette = $product->image;
        if($request->hasFile('image')){
            $vignette = $product->setMedias('image',[
               'src' => uploadImage('shop/products', $request->image),
               'alt' => $request->name
           ]);
        }

        Product::where("id",$product->id)->update([
            'published' => $published,
            'image' => $vignette,
            'shop_category_id' => $request->shop_category_id,
            'reference' => $request->reference,
            'isbn_code' => $request->isbn_code,
            'ean_code' => $request->ean_code,
            'upc_code' => $request->upc_code,
            'vat_id' => $request->vat_id,
            'price_ht' => $request->price_ht,
            'price_ttc' => $request->price_ttc,
            'reduce_date_begin' => $request->reduce_date_begin,
            'reduce_date_end' => $request->reduce_date_end,
            'reduce_price' => $request->reduce_price,
            'reduce_percent' => $request->reduce_percent,
            'stock_brut' => $request->stock_brut,
            'stock_available' => $request->stock_brut,
            'weight' => $request->weight,
            'height' => $request->height,
            'length' => $request->length,
            'width' => $request->width,
            'week_selection' => $weekSelection
        ]);

        $product->setTranslation('name', config('app.locale'), $request->name)
            ->setTranslation('slug', config('app.locale'), $request->slug)
            ->setTranslation('summary', config('app.locale'), $request->summary)
            ->setTranslation('description', config('app.locale'), $request->description)
            ->setTranslation('seo_title', config('app.locale'), $request->seo_title)
            ->setTranslation('seo_description', config('app.locale'), $request->seo_description)
            ->update();

        ProductsHasCategory::where("product_id",$product->id)->delete();
        foreach($request->categories as $category) {
            if(!empty($category)) {
                ProductsHasCategory::create([
                    'shop_category_id' => intval($category),
                    'product_id' => $product->id,
                ]);
            }
        }

        ProductsAssociate::where('product_parent', $product->id)->delete();
        if (!empty($request->associates)) {
            foreach ($request->associates as $associate) {
                if (!empty($associate)) {
                    ProductsAssociate::create([
                        'product_parent' => $product->id,
                        'product_id' => $associate
                    ]);
                }
            }
        }


        if($request->hasFile('images')){
            foreach($request->images as $key => $img){
                $insertImage = uploadImage('shop/products', $img);
                $productMedias = ProductsHasMedia::create([
                    'product_id' => $product->id,
                    'image' => $insertImage,
                    'order' => (ProductsHasMedia::where('product_id',$product->id)->count()+1)
                ]);
                $productMedias->update([
                    'image' => $productMedias->setMedias('image',[
                        'src' => $insertImage,
                        'alt' => $product->name,
                        'order' => $key
                    ])
                ]);
            }
        }

        $this->flush('product', $product->id);

        session()->flash('success',"Le produit a bien été modifié.");
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

        $product = Product::where('uuid',$uuid)->FirstOrFail();
        Product::where('id', $product->id)->delete();

        $this->flush('product', $product->id);

        session()->flash('success',"Le produit a bien été supprimé.");
        return redirect()->route($this->route . 'index');
    }

    public function positions(){

        $products = Product::OrderBy('order','asc')->get();
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

        $datas = Product::all();
        $dataArray = [];
        foreach ($datas as $data) {
            $dataArray[$data->id] = [
                'order' => $data->order
            ];
        }
        foreach ($request->orders as $key => $order) {
            if (!empty($order['id'])) {
                if ($dataArray[$order['id']]['order'] != $key) {
                    Product::where('id', $order['id'])->update([
                        'order' => $key,
                    ]);
                }
            }
        }

        return "Les positions ont bien été mises à jours";
    }

    public function imagesPositions(Request $request, int $id)
    {
        if(config('ikCommerce.demo')) {
            return "Cette fonctionnalité a été désactivée pour la version démo.";
        }

        $datas = ProductsHasMedia::where('product_id', $id)->get();
        $dataArray = [];
        foreach ($datas as $data) {
            $dataArray[intval($data->id)] = [
                'order' => $data->getMedias('image','order')
            ];
        }
        foreach ($request->orders as $key => $order) {
            if (isset($order['id'])) {
                $order['id'] = intval(str_replace("'","",$order['id']));
                if( !empty($order['id']) && !is_null($order['id'])) {
                    if ($dataArray[$order['id']]['order'] != $key) {
                        $data = ProductsHasMedia::where('id',$order['id'])->First();
                        $data->update([
                            'image' => $data->setMedias('image', ['order'=>$key]),
                        ]);
                    }
                }
            }
        }
        return "Les positions ont bien été mises à jours";
        exit();

    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function imageDelete(int $id)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->back();
        }

        $image = ProductsHasMedia::where('id',$id)->FirstOrFail();
        ProductsHasMedia::where('id', $image->id)->delete();
        if(file_exists($image->image)) {
            unlink($image->image);
        }
        session()->flash('success',"L'image a bien été supprimée.");
        return redirect()->back();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function import()
    {
        $directory = config('ikCommerce.fileUploadFolder') . '/' . config('ikCommerce.directoryImport');
        if(!file_exists($directory)){
            if(!mkdir($directory,0770, true)){
                dd('Echec lors de la création du répertoire : '.$directory);
            }
        }
        $files = File::allFiles($directory);

        return view($this->viewPath . 'import', compact('files'));
    }

    public function storeImport(ProductImports $request)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        if($request->hasFile('file')){
            $file = $request->file;
            $directory = config('ikCommerce.directoryImport');

            $dir = config('ikCommerce.fileUploadFolder') . DIRECTORY_SEPARATOR .$directory;
            if(!file_exists($dir)){
                if(!mkdir($dir,0770, true)){
                    dd('Echec lors de la création du répertoire : '.$dir);
                }
            }
            $fileName = $file->getClientOriginalName();
            $extension = getExtension($fileName);

            $fileNewName = time().str_slug(str_replace(".".$extension,"",$fileName)).".".strtolower($extension);
            $file->move($dir,$fileNewName);
            $targetPath = $dir. "/" . $fileNewName;
            if($file->getClientOriginalExtension() == 'csv' || $file->getClientOriginalExtension() == 'xls') {

                Excel::load($targetPath)->chunk(5, function($results){
                    $results->each(function($row) {
                        if(
                            !empty($row->ref) &&
                            !empty($row->price_ttc) &&
                            !is_null($row->ref) &&
                            !is_null($row->price_ttc)
                        ) {
                            $this->insertRow($row);
                        }
                    });
                    session()->flash('success',"L'importation s'est déroulée avec succès.");
                });
            } else {
                session()->flash('error',"L'extension de votre fichier n'est pas valide :" . $file->getClientOriginalExtension());
            }
        }
        return redirect()->route($this->route . 'imports');
    }

    /**
     * Traductions
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function translate(Request $request)
    {
        if(Input::get('id')) {
            $this->flush('product', Input::get('id'));
        }
        return (new FormTranslate(Product::class))->formRequest($request);
    }

    public function imageUpdate(Request $request, int $id)
    {
        if(config('ikCommerce.demo')) {
            return response("Cette fonctionnalité a été désactivée pour la version démo.",500)
                ->header('Content-Type','text/plain');
        }

        $product = Product::where('id',$id)->first();
        if($product && !empty($request->alt) && is_string($request->alt)) {
          $product->update([
             'image' => $product->setMedias('image',['alt' => $request->alt])
          ]);
            return response("Votre image a bien été mise à jours", 200)
                ->header('Content-Type', 'text/plain');
        }
        return response("Une erreur s'est produite", 500)
            ->header('Content-Type', 'text/plain');

    }

    public function imagesUpdate(Request $request, int $id)
    {
        if(config('ikCommerce.demo')) {
            return response("Cette fonctionnalité a été désactivée pour la version démo.",500)
                ->header('Content-Type','text/plain');
        }
        $product = ProductsHasMedia::where('id',$id)->first();
        if($product && !empty($request->alt) && is_string($request->alt)) {
            $product->update([
                'image' => $product->setMedias('image',['alt' => $request->alt])
            ]);
            return response("Votre image a bien été mise à jours", 200)
                ->header('Content-Type', 'text/plain');
        }
        return response("Une erreur s'est produite", 500)
            ->header('Content-Type', 'text/plain');
    }

    public function declinaison(int $id)
    {
        $product = Product::where('id', $id)->FirstOrFail();
        $title = 'Ajouter une déclinaison';
        $route = Route('admin.products.attribute.store',['id' => $product->id]);
        $data = new ProductsAttribute();
        return view(
            $this->viewPath. 'attributes.form',
            compact('data','title', 'route')
        );
    }

    public function declinaisonStore(Request $request, int $id)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }
        $product = Product::where('id', $id)->FirstOrFail();
        $product->update([
           "stock_brut" => $product->stock_brut + $request->stock_brut
        ]);
        $insert = [
            'uuid' => Uuid::uuid4()->toString(),
            'product_id' => $product->id,
            'declinaisons' => '{}',
            'stock_brut' => $request->stock_brut,
            'reference' => $request->reference,
            'ean_code' => $request->ean_code,
            'upc_code' => $request->upc_code,
            'isbn_code' => $request->isbn_code,
        ];

        $attribute = ProductsAttribute::create($insert);
        $insertAttributes = [];
        $reference = $request->reference;
        if(empty($request->reference)) {
            $reference = $product->reference;
        }
        foreach($request->attr as $key => $attr) {
            $insertAttributes[$attr] = $request->values[$key];
            $reference .= "-" . $request->values[$key];
        }
        $attribute->update([
            'declinaisons' => $attribute->setAttr('declinaisons', $insertAttributes),
            'reference' => $reference
        ]);

        return view($this->viewPath . 'attributes.line',compact('attribute'));

    }

    public function declinaisonEdit(int $id, string $uuid)
    {
        $data = ProductsAttribute::where('product_id', $id)
            ->where('uuid', $uuid)
            ->firstOrFail();
        $title = 'Editer la déclinaison';
        $route = Route('admin.products.attribute.update',['uuid' => $uuid]);
        return view(
            $this->viewPath. 'attributes.form',
            compact('data','title', 'route')
        );
    }
    public function declinaisonUpdate(Request $request, string $uuid)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }
        $attribute = ProductsAttribute::where('uuid', $uuid)->FirstOrFail();

        $product = Product::where('id',$attribute->product_id)->First();
        $product->update([
           "stock_brut" => $product->stock_brut + $request->stock_brut
        ]);

        $attribute->update([
            'declinaisons' => '{}',
            'stock_brut' => $request->stock_brut,
            'reference' => $request->reference,
            'ean_code' => $request->ean_code,
            'upc_code' => $request->upc_code,
            'isbn_code' => $request->isbn_code,
        ]);

        $insertAttributes = [];
        foreach($request->attr as $key => $attr) {
            $insertAttributes[$attr] = $request->values[$key];
        }
        $attribute->update([
            'declinaisons' =>  $attribute->setAttr('declinaisons', $insertAttributes)
        ]);

        return view($this->viewPath . 'attributes.cell',compact('attribute'));

    }

    public function declinaisonDelete(string $uuid)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }
        $attribute = ProductsAttribute::where('uuid', $uuid)->FirstOrFail();
        $attribute->delete();
        return '';
    }

    /**
     * @return mixed
     */
    private function dataTable()
    {
        $products = Product::select(['id','published','uuid','image','name','reference','price_ht','stock_available','updated_at']);
        return DataTables::of($products)
            ->editColumn('published', 'admin.datatable.published')
            ->editColumn('image', function(Product $product){
                return view('admin.datatable.image', [
                    'image' => $product->getMedias('image','src')
                ]);
            })
            ->editColumn('price_ht',function(Product $product) {
                return priceFormat($product->price_ht);
            })
            ->editColumn('name', function(Product $product){
                return $product->getTranslation('name', config('app.locale'));
            })
            ->editColumn('stock_available', 'admin.datatable.quantity')
            ->editColumn('updated_at', 'admin.datatable.updatedAt')
            ->addColumn('actions', $this->getTableButtons())
            ->rawColumns(['actions','published','image','price_ht','stock_available'])
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

    private function insertRow($row)
    {
        /* Cherche une catégorie si pas existante en créé une */
        $category = ShopCategory::where('name->'.config('app.locale'), $row->main_category)->First();
        if (empty($category)) {
            $category = ShopCategory::create([
                'uuid' => Uuid::uuid4()->toString(),
                'published' => 1,
                'slug' => str_slug($row->main_category),

            ]);
            $category->setTranslation('name', config('app.locale'), $row->main_category)
                ->setTranslation('slug', config('app.locale'), str_slug($row->main_category))
                ->update();
        }
        $mainCategoryId = $category->id;

        /* Vérification de la taxe si pas existante création */
        $vat = Vat::where('percent', $row->taxe_en)->First();
        if (empty($vat)) {
            $vat = Vat::create([
                'uuid' => Uuid::uuid4()->toString(),
                'name' => 'Taxe : ' . $row->taxe_en . '%',
                'percent' => $row->taxe_en
            ]);
        }
        // En ligne ?
        if ($row->published_1_ou_0 == 1.0) {
            $row->published_1_ou_0 = 1;
        } else {
            $row->published_1_ou_0 = 0;
        }
        // Selection de la semaine
        if ($row->week_selection_1_ou_0 == 1.0) {
            $row->week_selection_1_ou_0 = 1;
        } else {
            $row->week_selection_1_ou_0 = 0;
        }
        /* Formatage des dates */
        if (!empty($row->reduce_date_begin)) {
            $row->reduce_date_begin = Carbon::parse(str_replace('/', '-', $row->reduce_date_begin))
                ->format('Y-m-d H:i:s');
        }
        if (!empty($row->reduce_date_end)) {
            $row->reduce_date_end = Carbon::parse(str_replace('/', '-', $row->reduce_date_end))
                ->format('Y-m-d H:i:s');
        }
        /* Slug du produit */
        if (empty($row->slug)) {
            $slug = str_slug($row->name);
        } else {
            $slug = str_slug($row->slug);
        }
        /* Stock disponible */
        if (empty($row->stock_available)) {
            $row->stock_available = $row->stock_brut - $row->stock_booked;
        }

        $insert = [
            'reference' => $row->ref,
            'published' => $row->published_1_ou_0,
            'shop_category_id' => $mainCategoryId,
            'isbn_code' => $row->isbn_code,
            'upc_code' => $row->upc_code,
            'ean_code' => $row->ean_code,
            'vat_id' => $vat->id,
            'price_ht' => $row->price_ht,
            'price_ttc' => $row->price_ttc,
            'week_selection' => $row->week_selection_1_ou_0,
            'reduce_date_begin' => $row->reduce_date_begin,
            'reduce_date_end' => $row->reduce_date_end,
            'reduce_price' => $row->reduce_price,
            'reduce_percent' => $row->reduce_percent,
            'stock_brut' => intval($row->stock_brut),
            'stock_booked' => intval($row->stock_booked),
            'stock_available' => intval($row->stock_available),
            'weight' => $row->weight_kg,
            'height' => $row->height_cm,
            'length' => $row->length_cm,
            'width' => $row->width_cm,
        ];
        //dd($insert);
        /* Test si le produit existe */
        $product = Product::where('reference', $row->ref)->First();
        if (!empty($product)) {
            /* Update Produit */
            Product::where('id', $product->id)->update($insert);
            $product->setTranslation('name', config('app.locale'), $row->name)
                ->setTranslation('slug', config('app.locale'), $slug)
                ->setTranslation('summary', config('app.locale'), '<p>'.$row->summary.'</p>')
                ->setTranslation('description', config('app.locale'), '<p>'.$row->description.'</p>')
                ->setTranslation('seo_title', config('app.locale'), $row->seo_title)
                ->setTranslation('seo_description', config('app.locale'), $row->seo_description)
                ->update();
            $this->flush('product', $product->id);
        } else {
            /* Insert Produit */
            $insert['uuid'] = Uuid::uuid4()->toString();
            $product = Product::create($insert);
            $product->setTranslation('name', config('app.locale'), $row->name)
                ->setTranslation('slug', config('app.locale'), $slug)
                ->setTranslation('summary', config('app.locale'), '<p>'.$row->summary.'</p>')
                ->setTranslation('description', config('app.locale'), '<p>'.$row->description.'</p>')
                ->setTranslation('seo_title', config('app.locale'), $row->seo_title)
                ->setTranslation('seo_description', config('app.locale'), $row->seo_description)
                ->update();
        }
        /* Liste des catégories */
        $categories = explode("/", $row->categories);
        $categoriesID = [];
        foreach ($categories as $cat) {
            if (!empty($cat)) {
                $category = ShopCategory::where('name->'.config('app.locale'), $cat)->First();
                if (empty($category)) {
                    $category = ShopCategory::create([
                        'uuid' => Uuid::uuid4()->toString(),
                        'published' => 1,

                    ]);

                    $category->setTranslation('name', config('app.locale'), $cat)
                        ->setTranslation('slug', config('app.locale'), str_slug($cat))
                        ->update();
                }
                $categoriesID[] = $category->id;
            }
        }

        /* Insertion de toute les catégories */
        ProductsHasCategory::where("product_id", $product->id)->delete();
        foreach ($categoriesID as $category) {
            if (!empty($category)) {
                ProductsHasCategory::create([
                    'shop_category_id' => intval($category),
                    'product_id' => $product->id,
                ]);
            }
        }
    }

}
