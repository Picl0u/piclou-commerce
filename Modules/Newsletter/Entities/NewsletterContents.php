<?php

namespace Modules\Newsletter\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class NewsletterContents extends Model
{
    use HasTranslations;

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'image'
    ];

    public $translatable = [
        'name',
        'description',
    ];

    public $translatableInputs = [
        'name' => [
            'label' => 'Nom de la page',
            'type' => 'text'
        ],
        'description' => [
            'label' => 'Contenu',
            'type' => 'editor'
        ],
    ];
}
