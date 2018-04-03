<?php

namespace Modules\Content\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ContentCategory extends Model
{
    use HasTranslations;

    protected $fillable = ['id','uuid','on_footer','name'];

    public $translatable = ['name'];

    public $translatableInputs = [
        'name' => [
            'label' => 'Nom de la page',
            'type' => 'text'
        ],
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function Contents()
    {
        return $this->hasMany(Content::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function ContentsFooter(int $categoryId)
    {
        return Content::select('id','slug','name')
            ->where("content_category_id", $categoryId)
            ->where('on_footer',1)
            ->orderBy('order','asc')
            ->get();
    }

}
