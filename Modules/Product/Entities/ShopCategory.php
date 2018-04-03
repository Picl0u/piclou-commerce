<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ShopCategory extends Model
{
    use HasTranslations;

    protected $guarded = [];

    public $translatable = [
        'name',
        'slug',
        'description',
        'seo_title',
        'seo_description'
    ];


    public $translatableInputs = [
        'name' => [
            'label' => 'Nom de la catégorie',
            'type' => 'text'
        ],
        'slug'=> [
            'label' => 'Lien de la catégorie',
            'type' => 'text'
        ],
        'description' => [
            'label' => 'Contenu',
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ProductsHasCategories()
    {
        return $this->hasMany(ProductsHasCategory::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * @param int $parentId
     * @return mixed
     */
    public function parentCategory($parentId)
    {
        if(!empty($parentId)){

            $parent = ShopCategory::where('id', $parentId)->First();
            if(!empty($parent->parent_id)){
                return self::parentCategory($parent->parent_id);
            }
            return $parent;
        }
        return null;
    }
}
