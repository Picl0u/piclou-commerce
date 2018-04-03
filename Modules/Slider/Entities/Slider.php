<?php

namespace Modules\Slider\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Slider extends Model
{
    use HasTranslations;

    protected $fillable = ['id','uuid','published','name','description','image','link', 'position'];

    public $translatable = [
        'name',
        'description',
    ];

    public $translatableInputs = [
        'name' => [
            'label' => 'Titre de la slide',
            'type' => 'text'
        ],
        'description' => [
            'label' => 'Description',
            'type' => 'editor'
        ],
    ];


}
