<?php

namespace Modules\Content\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Content extends Model
{

    use HasTranslations;

    protected $fillable = [
        'id',
        'uuid',
        'published',
        'on_homepage',
        'on_footer',
        'on_menu',
        'name',
        'slug',
        'image',
        'content_category_id',
        'summary',
        'description',
        'order',
        'seo_title',
        'seo_description'
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
            'label' => 'Nom de la page',
            'type' => 'text'
        ],
        'slug'=> [
            'label' => 'URL simplifié',
            'type' => 'text'
        ],
        'summary' => [
            'label' => 'Accroche',
            'type' => 'editor'
        ],
        'description' => [
            'label' => 'Contenu',
            'type' => 'editor'
        ],
        'seo_title' => [
            'label' => "Méta title",
            'type' => 'text'
        ],
        'seo_description' => [
            'label' => "Méta description",
            'type' => 'textarea'
        ],
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ContentCategory()
    {
        return $this->belongsTo(ContentCategory::class);
    }

}
