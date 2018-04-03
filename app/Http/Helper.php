<?php

/**
 * Redimentionnement des images
 * @param string $img
 * @param int|bool $width
 * @param int|bool $height
 * @param string $direction
 * @return string
 */
function resizeImage($img, $width = false, $height = false, string $direction = 'center'): string
{
    if(is_null($img) || empty($img)){
        return '/'. config('ikCommerce.imageNotFound');
    }
    $dir = config('ikCommerce.imageCacheFolder');
    $infos = pathinfo($img);
    $fileName = $infos['filename'];
    $extension = $infos['extension'];
    $dir .= '/' . $infos['dirname'];

    if (!file_exists($infos['dirname']. '/' . $fileName . "." .  $infos['extension'])) {
        return '/'. config('ikCommerce.imageNotFound');
    }
    if(!file_exists($dir)){
        if(!mkdir($dir,0770, true)){
            dd('Echec lors de la création du répertoire : '.$dir);
        }
    }

    if ($width && $height) {
        $cacheResize = "_".$width."_".$height;
    } elseif ($width && !$height) {
        $cacheResize = "_".$width;
    } else {
        $cacheResize = "_".$height;
    }

    if (file_exists('web' . "/" . $dir . "/" . $fileName.$cacheResize.".".$extension)) {
        return asset($dir. "/" . $fileName.$cacheResize.".".$extension);
    } else {
        $manager = new \Intervention\Image\ImageManager(['drive' => 'gd']);
        $image = $manager->make($img);
        if ($width && $height) {
            $image->fit($width, $height, function () {
            }, $direction);
        } elseif ($width && !$height) {
            $image->fit($width, null, function () {
            }, $direction);
        } else {
            $image->resize(null, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        $image->save(
            $dir . "/" . $fileName.$cacheResize.".".$extension,
            config('ikCommerce.imageQuality')
        );
        return asset("/".$dir . "/" . $fileName.$cacheResize.".".$extension);
    }
}


/**
 * Upload des images
 * @param string $directory
 * @param $file
 * @return string
 */
function uploadImage(string $directory, $file): string
{
    $directory = str_replace('\\', '/',$directory);

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

    $imageManager =  new \Intervention\Image\ImageManager();
    $img = $imageManager->make($targetPath);
    $width = $img->width();
    if ($width > config('ikCommerce.imageMaxWidth')) {
        $img->resize( config('ikCommerce.imageMaxWidth'), null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($targetPath, config('ikCommerce.imageQuality'));
    }
    return $targetPath;
}

/**
 * Upload des fichiers
 * @param string $directory
 * @param $file
 * @return string
 */
function uploadFile(string $directory, $file): string
{
    $directory = str_replace("\\","/",$directory);
    $file = str_replace("\\","/",$file);
    $dir = config('ikCommerce.fileUploadFolder') . "/" .$directory;
    if(!file_exists($dir)){
        if(!mkdir($dir,0770, true)){
            dd('Echec lors de la création du répertoire : '.$dir);
        }
    }
    $fileName = $file->getClientOriginalName();
    $extension = getExtension($fileName);

    $fileNewName = time().str_slug(str_replace(".".$extension,"",$fileName)).".".strtolower($extension);
    $file->move($dir,$fileNewName);
    $targetPath = $dir. DIRECTORY_SEPARATOR . $fileNewName;;

    return $targetPath;

}

/**
 * @param array|null $data
 * @return \App\Http\Picl0u\NestableExtends|object
 */
function nestableExtends(array $data = null)
{
    $nestable = new \App\Http\Picl0u\NestableExtends();

    if (is_array($data)) {
        $nestable = $nestable->make($data);
    }

    return $nestable;
}


/**
 * Navigation principal pour les catégories de la boutique
 * @return string
 */
function navigationShopCategories(): string
{
    $categories = \Modules\Product\Entities\ShopCategory::select('id','slug','name','order','parent_id', 'image')
        ->where('published',1)
        ->orderBy('order','asc')
        ->get();
    $menu = [];
    foreach($categories as $category) {
        $menu[] = [
            'id' => $category->id,
            'slug' => $category->getTranslation('slug', config('app.locale')),
            'name' => $category->getTranslation('name', config('app.locale')),
            'order' => $category->order,
            'parent_id' => $category->parent_id,
            'image' => $category->image
        ];
    }
    return NestableExtends($menu)
        ->firstUlAttr('class', 'first-ul')
        ->route(['product.list' => 'slug'])
        ->renderAsHtml();
}

/**
 * Retourne le lien vente flash si vente flash existante
 * @return string
 */
function navigationFlashSales(): string
{
    $flashSale = \Modules\Product\Entities\Product::where('products.published',1)
        ->where('products.reduce_date_begin', '<=', date('Y-m-d H:i:s'))
        ->where('products.reduce_date_end', '>', date('Y-m-d H:i:s'))
        ->count();

    if(!empty($flashSale)) {
        return '<li><a href="#">Vente Flash</a></li>';
    }
}

/**
 * @return string
 */
function navigationContents(): string
{
    $html = '';
    $datas = \Modules\Content\Entities\Content::select('id','slug','name')
        ->where('published',1)
        ->where('on_menu',1)
        ->orderBy('order','asc')
        ->get();
    foreach($datas as $data) {
        $link = Route('content.index',['slug' => $data->slug, 'id' => $data->id]);
        $html .= '<li><a href="' . $link . '">' . $data->getTranslation('name', config('app.locale')) . '</a></li>';
    }
    return $html;
}

/**
 * @return string
 */
function footerNavigation(): string
{
    $html = '';

    $categories = \Modules\Content\Entities\ContentCategory::select('id','name')
        ->where('on_footer',1)
        ->get();
    foreach($categories as $category) {
        $html .= '<ul class="col col-3">';
            $html .='<li class="category-title">' .
                $category->getTranslation('name',config('app.locale')) .
            '</li>';
            $contents = $category->ContentsFooter($category->id);
            foreach($contents as $content) {
                $link = Route('content.index',[
                    'slug' => $content->getTranslation('slug',config('app.locale')),
                    'id' => $content->id
                ]);
                $html .= '<li><a href="' . $link . '">' .
                    $content->getTranslation('name',config('app.locale')) .
                '</a></li>';
            }
        $html .= '</ul>';
    }
    $count = count($categories);
    /* A REVOIR */
    $contents = \Modules\Content\Entities\Content::select('id','name','slug')
        ->where('on_footer', 1)
        ->where('content_category_id', null)
        ->orderBy('order', 'asc')
        ->get();

    if(count($contents) > 0) {
        $count += 1;
        $html .= '<ul class="col col-3">';
            foreach ($contents as $content) {
                $link = Route('content.index',[
                    'slug' => $content->getTranslation('slug',config('app.locale')),
                    'id' => $content->id
                ]);
                $html .= '<li><a href="' . $link . '">' .
                    $content->getTranslation('name',config('app.locale')) .
                '</a></li>';
            }
        $html .= '</ul>';
    }

    $numberCol = 12 - ($count*3);

    $categories = \Modules\Product\Entities\ShopCategory::select('id','slug','name','order','parent_id')
        ->where('published',1)
        ->orderBy('order','asc')
        ->get();
    $menu = [];
    foreach($categories as $category) {
        $menu[] = [
            'id' => $category->id,
            'slug' => $category->getTranslation('slug', config('app.locale')),
            'name' => $category->getTranslation('name', config('app.locale')),
            'order' => $category->order,
            'parent_id' => $category->parent_id,
        ];
    }
    $html.= NestableExtends($menu)
        ->firstUlAttr('class', 'col col-'.$numberCol.' shop-categories')
        ->route(['product.list' => 'slug'])
        ->renderAsHtml();
    return $html;
}


/**
 * @param string $str
 * @return string
 */
function getExtension(string $str): string
{
    $i = strrpos($str, ".");
    if(!$i) {
        return "";
    }
    $l = strlen($str) - $i;
    return substr($str, $i+1, $l);
}

/**
 * @param float $price
 * @param string $currency
 * @return string
 */
function priceFormat(float $price, string $currency = '&euro;'): string
{
    return number_format($price, 2, ",", " ").$currency;
}

/**
 * @param string $key
 * @return string
 */
function setting(string $key):string
{
    $setting = anlutro\LaravelSettings\Facade::get($key);
    if(is_null($setting)) {
        return '';
    }
    return $setting;
}

/**
 * @param string $date
 * @param string $format
 * @return string
 */
function formatDate(string $date, string $format = 'd/m/Y'): string
{
    return \Carbon\Carbon::parse($date)->format($format);
}

/**
 * @param float $price
 * @param $reducPrice
 * @param $reducPercent
 * @return string
 */
function percentReduc(float $price, $reducPrice, $reducPercent)
{
    if(!is_null($reducPrice) && !empty($reducPrice)) {
        $calcul = (($price - $reducPrice)-$price)/$price*100;
    } else{
        $calcul = "-".$reducPercent;
    }

    return number_format(round ($calcul),0, ',', ' ') . "%";
}

/**
 * @return array
 */
function priceCarrier(): array
{
    $total = Cart::instance('shopping')->total(2, ".", "");
    $carrier = \Modules\Order\Entities\CarriersPrices::where("price_min", "<", $total)->where(function ($query) use ($total) {
        $query->where('price_max', '>', $total);
    })->where('country_id', setting('orders.countryId'))
        ->orderBy('price', "ASC")
        ->first();

    if (empty($carrier)) {
        $carrier = \Modules\Order\Entities\CarriersPrices::where("price_min", "<", $total)
            ->where('price_max', 0)
            ->where('country_id', setting('orders.countryId'))
            ->orderBy('price', "ASC")
            ->first();
    }else{
        $price = $carrier->price;
    }
    if (empty($carrier)) {
        $carrier = \Modules\Order\Entities\Carriers::where('default', 1)->first();
        $price = $carrier->default_price;
    } else{
        $price = $carrier->price;
    }
    if (!empty(setting('orders.freeShippingPrice'))) {
        if ($total >= setting('orders.freeShippingPrice')) {
            $price = 0;
        }
    }
    return [
        'priceCarrier' => $price,
        'total' => $price + $total
    ];
}

/**
 * @return array
 */
function checkCoupon(): array
{
    $coupon = [];
    $total = Cart::instance('shopping')->total(2,".","");
    if (session()->get('coupons') ){
        $coupon = (new \Modules\ShoppingCart\Http\Controllers\ShoppingCartController())
            ->checkCoupon(session()->get('coupons')['coupon_id'],$total);
    }
    return $coupon;
}

/**
 * @return mixed
 */
function pendingOrderCount()
{
    $status = \Modules\Order\Entities\Status::select('id')->where('order_accept', 1)->first();
    if($status) {
        return \Modules\Order\Entities\Order::where('status_id', $status->id)->count();
    }
    return 0;
}

function formTranslate($modelName, $data)
{
    return(new \App\Http\Picl0u\FormTranslate\FormTranslate(
        $modelName,
        $data
    ));
}
