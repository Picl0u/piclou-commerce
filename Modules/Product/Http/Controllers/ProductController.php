<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Picl0u\Share;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\Content\Entities\Content;
use Illuminate\Support\Facades\Input;
use Modules\Product\Emails\SendCommentToAdmin;
use Modules\Product\Entities\Comment;
use Modules\Product\Entities\Product;
use Modules\Product\Http\Requests\CommentRequest;
use Modules\Product\Entities\ShopCategory;
use Ramsey\Uuid\Uuid;

class ProductController extends Controller
{
    protected $viewPath = 'product::';

    /**
     * @param string $slug
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function lists(string $slug, int $id)
    {
        $lang =  config('app.locale');
        $category = ShopCategory::select('id','slug','name','parent_id','imageList')
            ->where('published', 1)
            ->where('id', $id)
            ->FirstOrFail();

        if($category->getTranslation('slug', config('app.locale')) != $slug) {
            return redirect(
                Route('product.list',[
                    'slug' => $category->getTranslation('slug', config('app.locale')),
                    'id' => $category->id
                ]),
                301
            );
        }

        /* Catégorie parente */
        $parent = $category->parentCategory($category->parent_id);
        /* Catégories */
        $associatedCategories = ShopCategory::where('published', 1)->orderBy('order','asc')->get();
        $categories = [];
        foreach($associatedCategories as $assoc) {
            $categories[] = [
                'id' => $assoc->id,
                'name' => $assoc->getTranslation('name', config('app.locale')),
                'slug' => $assoc->getTranslation('slug', config('app.locale')),
                'order' => $assoc->order,
                'parent_id' => $assoc->parent_id,
                'imageList' => $assoc->imageList
            ];
        }

        /* Order des produits */
        $orderBy = setting('products.orderField');
        $orderDir = setting('products.orderDirection');
        $order="pertinence";
        if(Input::has('orderField')){
            $order = Input::get('orderField');
            if($order == 'name_asc') {
                $orderBy = 'name';
                $orderDir = 'ASC';
            }
            if($order == 'name_desc') {
                $orderBy = 'name';
                $orderDir = 'DESC';
            }
            if($order == 'price_asc') {
                $orderBy = 'price_ttc';
                $orderDir = 'ASC';
            }
            if($order == 'price_desc') {
                $orderBy = 'price_ttc';
                $orderDir = 'DESC';
            }
        }

        /* Liste des produits */
        $products = Product::join('products_has_categories', 'products.id', '=', 'products_has_categories.product_id')
            ->select('products.*')
            ->where('published', 1)
            ->where('products_has_categories.shop_category_id',$category->id)
            ->orderBy('products.'.$orderBy, $orderDir)
            ->paginate(setting('products.paginate'))->appends('order',$order);

        /* Fil d'arianne */
        $arianne = [
            trans('generals.home') => '/',
        ];
        /* Parents */
        if(!is_null($parent)){
            $arianne[$parent->name] = Route('product.list',[
                'slug' => $parent->getTranslation('slug', config('app.locale')),
                'id' => $parent->id
            ]);
            if($parent->id != $category->parent_id){
                $parentCat = ShopCategory::where('id', $category->parent_id)->First();
                if(!empty($parentCat))  {
                    $arianne[$parentCat->name] = Route(
                        'product.list',
                        [
                            'slug' => $parentCat->getTranslation('slug', config('app.locale')),
                            'id' => $parentCat->id
                        ]
                    );
                }
            }
        }
        $arianne[$category->name] = Route('product.list',[
            'slug' => $category->getTranslation('slug', config('app.locale')),
            'id' => $category->id
        ]);

        return view($this->viewPath . 'list',
            compact('category','parent', 'arianne', 'products', 'categories', 'order')
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        if(!isset($request->keywords) && empty($request->keywords)) {
            return redirect('/');
        }
        $keywords = str_replace("+", " ",$request->keywords);
        $keywordsArray = explode(" ",$keywords);

        /* Order des produits */
        $orderBy = setting('products.orderField');
        $orderDir = setting('products.orderDirection');
        $order="pertinence";
        if($request->orderField){
            $order = $request->order;
            if($order == 'name_asc') {
                $orderBy = 'name';
                $orderDir = 'ASC';
            }
            if($order == 'name_desc') {
                $orderBy = 'name';
                $orderDir = 'DESC';
            }
            if($order == 'price_asc') {
                $orderBy = 'price_ttc';
                $orderDir = 'ASC';
            }
            if($order == 'price_desc') {
                $orderBy = 'price_ttc';
                $orderDir = 'DESC';
            }
        }
        /* Liste des produits */
        $products = Product::where('published', 1)
            ->where(function($query) use ($keywordsArray) {
                foreach($keywordsArray as $keywords) {
                    $query->orwhere('name->'.config('app.locale'), 'like', '%'.ucfirst($keywords).'%')
                        ->orWhere('name->'.config('app.locale'), 'like', '%'.$keywords.'%')
                        ->orWhere('reference', 'like', '%'.$keywords.'%');
                }
            })
            ->orderBy('products.'.$orderBy, $orderDir)
            ->paginate(setting('products.paginate'))->appends(['order' => $order, 'keywords' => $keywords]);
        if(count($products) < 1) {
            $category = ShopCategory::where('published', 1)
                ->where(function($query) use ($keywordsArray) {
                    foreach($keywordsArray as $keywords) {
                        $query->orwhere('name->'.config('app.locale'), 'like', '%'.ucfirst($keywords).'%')
                            ->orWhere('name->'.config('app.locale'), 'like', '%'.$keywords.'%');
                    }
                })->first();
            if(!empty($category)) {
                $products = Product::where('published', 1)
                    ->where('shop_category_id',$category->id)
                    ->orderBy('products.'.$orderBy, $orderDir)
                    ->paginate(setting('products.paginate'))->appends(['order' => $order, 'keywords' => $keywords]);
            }
        }
        /* Fil d'arianne */
        $arianne = [
            __('generals.home') => '/',
            'Recherche : ' . $request->keywords => route('product.search').'?keywords=' . $request->keywords
        ];
        return view($this->viewPath . 'search',
            compact('arianne', 'products', 'keywords', 'order')
        );

    }

    /**
     * @param string $slug
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function show(string $slug, int $id)
    {
        $product = Product::where('published', 1)
            ->where('id', $id)
            ->FirstOrFail();

        if($product->getTranslation('slug', config('app.locale')) != $slug) {
            return redirect(
                Route('product.show',[
                    'slug' => $product->getTranslation('slug', config('app.locale')),
                    'id' => $product->id
                ]),
                301
            );
        }

        /* Images */
        $images = $product->ProductsHasMedias;

        /* Déclinaisons */
        $attributes = $product->ProductsAttributes;
        $declinaisons = [];
        foreach ($attributes as $attribute) {
            $decl = $attribute->getValues('declinaisons');
            foreach($decl as $key => $value) {
                if(array_key_exists($key, $declinaisons)){
                    if(!in_array($value,$declinaisons[$key])){
                        $declinaisons[$key][] = $value;
                    }
                } else {
                    $declinaisons[$key][] = $value;
                }
            }
        }

        /* Fil d'arianne */
        $arianne = [
            trans('generals.home') => '/'
        ];
        $category = $product->shopCategory;
        /* Catégorie parente */
        $parent = $category->parentCategory($category->parent_id);
        if(!is_null($parent)) {
            $arianne[$parent->getTranslation('name', config('app.locale'))] = Route('product.list',[
                'slug' => $parent->getTranslation('slug', config('app.locale')),
                'id' => $parent->id
            ]);
        }
        $arianne[$category->getTranslation('name', config('app.locale'))] = Route('product.list',[
            'slug' => $category->getTranslation('slug', config('app.locale')),
            'id' => $category->id
        ]);
        $arianne[$product->getTranslation('name', config('app.locale'))] = Route('product.show',[
            'slug' => $product->getTranslation('slug', config('app.locale')),
            'id' => $product->id
        ]);

        // Produits associés
        $productAssociates = $product->ProductsAssociates;
        $relatedProducts = [];
        if(count($productAssociates) < 1) {
            $relatedProducts = Product::select('id','price_ttc','reduce_price','reduce_percent','image','name','slug')
                ->where('published', 1)
                ->where('id','!=',$product->id)
                ->where('shop_category_id', $product->shop_category_id)
                ->inRandomOrder()
                ->get(6);
        }

        // Contenus page d'accueil
        $contents = Content::select('id','image','name','slug','summary','content_category_id','updated_at')
            ->where('published', 1)
            ->where('on_homepage', 1)
            ->orderBy('order','ASC')
            ->get();


        // Partage réseaux sociaux
        $share = [];
        if (!empty(setting('products.socialEnable'))) {
            $share = (new Share(url()->current(),
                $product->name . " - " . setting('generals.websiteName'),
                asset($product->getMedias('image','src')))
            )->render();
        }

        // Commentaires
        $comments = '';
        if(!empty(setting('products.commentEnable'))) {
          $comments = $product->Comments;
        }



        return view(
            $this->viewPath . "show",
            compact(
                'product',
                'arianne',
                'images',
                'contents',
                'relatedProducts',
                'share',
                'comments',
                'productAssociates',
                'declinaisons'
            )
        );
    }

    /**
     * Ajoute un commentaire au produit
     * @param CommentRequest $request
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addComment(CommentRequest $request, string $uuid)
    {
        $product = Product::where('uuid', $uuid)->firstOrFail();
        $user = Auth::user();
        $insert = [
            'uuid' => Uuid::uuid4()->toString(),
            'published' => 1,
            'product_id' => $product->id,
            'user_id' => $user->id,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'comment' => $request->comment
        ];
        Comment::create($insert);

        Mail::to(setting('generals.email'))
            ->send(new SendCommentToAdmin($product, $user, $request->comment));

        session()->flash('success',"Votre commentaire a bien été envoyé.");
        return redirect()->route('product.show',['slug' => $product->slug, 'id' => $product->id]);
    }

    /**
     * Ventes flash
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function flashSales()
    {
        /* Order des produits */
        $orderBy = 'reduce_date_end';
        $orderDir = 'asc';
        $order="pertinence";

        if(Input::has('order')){
            $order = Input::get('order');
            if($order == 'name_asc') {
                $orderBy = 'name';
                $orderDir = 'ASC';
            }
            if($order == 'name_desc') {
                $orderBy = 'name';
                $orderDir = 'DESC';
            }
            if($order == 'price_asc') {
                $orderBy = 'price_ttc';
                $orderDir = 'ASC';
            }
            if($order == 'price_desc') {
                $orderBy = 'price_ttc';
                $orderDir = 'DESC';
            }
        }

        $products = Product::select(
            'id',
            'stock_available',
            'reduce_date_end',
            'reduce_price',
            'reduce_percent',
            'price_ttc',
            'image',
            'name',
            'slug',
            'summary'
        )
            ->where('published',1)
            ->where('reduce_date_begin', '<=', date('Y-m-d H:i:s'))
            ->where('reduce_date_end', '>', date('Y-m-d H:i:s'))
            ->orderBy('products.'.$orderBy, $orderDir)
            ->paginate(setting('products.paginate'))->appends('order',$order);

        $contents = Content::select('id','image','name','slug','summary','content_category_id','updated_at')
            ->where('published', 1)
            ->where('on_homepage', 1)
            ->orderBy('order','ASC')
            ->get();

        $arianne = [
            __('generals.home') => '/',
            __('generals.flashSales') => route('product.flash')
        ];

        return view(
            $this->viewPath . 'flash',
            compact('products', 'contents', 'arianne', 'order')
        );
    }
}
