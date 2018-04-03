<?php

namespace Modules\Product\Entities;

use App\Http\Picl0u\Medias\HasMedias;
use Illuminate\Database\Eloquent\Model;
use Modules\Order\Entities\OrdersProducts;
use Modules\Vat\Entities\Vat;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{

    use HasTranslations;
    use HasMedias;

    /**
     * @var array
     */
    protected $guarded = [];

    public $medias = [
        'image'
    ];

    public $translatable = [
        'name',
        'slug',
        'summary',
        'description',
        'seo_title',
        'seo_description'
    ];

    public $translatableInputs = [
        'name' => [
            'label' => 'Nom du produit',
            'type' => 'text'
        ],
        'slug'=> [
            'label' => 'Lien du produit',
            'type' => 'text'
        ],
        'summary' => [
            'label' => 'Description courte du produit',
            'type' => 'editor'
        ],
        'description' => [
            'label' => 'Description du produit',
            'type' => 'editor'
        ],
        'seo_title' => [
            'label' => 'SEO Title',
            'type' => 'text'
        ],
        'seo_description' => [
            'label' => 'SEO Description',
            'type' => 'textarea'
        ],
    ];

    public function Vat()
    {
        return $this->belongsTo(Vat::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ShopCategory()
    {
        return $this->belongsTo(ShopCategory::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ProductsHasCategories()
    {
        return $this->hasMany(ProductsHasCategory::class)
            ->orderBy('shop_category_id','asc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ProductsHasMedias()
    {
        $medias = $this->hasMany(ProductsHasMedia::class)
            ->orderBy('image->order','asc');
        if(count($medias->getResults()) < 1) {
            $medias = $this->hasMany(ProductsHasMedia::class);
        }
        return $medias;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OrdersProducts()
    {
        return $this->hasMany(OrdersProducts::class);
    }


    public static function FlashSales()
    {
        return Product::select(
            'id',
            'stock_available',
            'reduce_date_end',
            'reduce_price',
            'reduce_percent',
            'price_ttc',
            'image',
            'name',
            'slug',
            'summary',
            'updated_at'
        )
        ->where('published',1)
        ->where('reduce_date_begin', '<=', date('Y-m-d H:i:s'))
        ->where('reduce_date_end', '>', date('Y-m-d H:i:s'))
        ->orderBy('reduce_date_end','ASC')
        ->limit(5)
        ->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Comments()
    {
        return $this->hasMany(Comment::class)->where('published', 1);
    }

    /**
     * Retourne la liste des produits associés
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ProductsAssociates()
    {
        return $this->hasMany(ProductsAssociate::class, 'product_parent');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ProductsAttributes()
    {
        return $this->hasMany(ProductsAttribute::class);
    }


}
