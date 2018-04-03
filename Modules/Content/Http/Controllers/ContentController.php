<?php

namespace Modules\Content\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Content\Entities\Content;

class ContentController extends Controller
{
    /**
     * @param string $slug
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index(string $slug, int $id)
    {
        $content = Content::select('id','slug','name','description','content_category_id', 'on_homepage', 'image')
            ->where('published', 1)
            ->where('id', $id)
            ->first();

        if($content->getTranslation('slug', config('app.locale')) != $slug) {
            return redirect(
                Route('content.index',[
                    'slug' => $content->getTranslation('slug', config('app.locale')),
                    'id' => $content->id
                ]),301);
        }


        /* Recherches des contenus de la même catégorie */
        $category = $content->ContentCategory;
        $contentList = null;
        if(!is_null($category)) {
            $contentList = $category->Contents;
        }

        /* Fil d'arianne */
        $arianne = [
            __('generals.home') => '/',
            $content->name => Route('content.index',[
                'slug' => $content->getTranslation('slug', config('app.locale')),
                'id' => $content->id
            ]),
        ];

        return view('content::index', compact('content','category', 'contentList', 'arianne'));
    }
}
