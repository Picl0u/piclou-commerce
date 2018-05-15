<?php

namespace Modules\Editor\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class EditorController extends Controller
{

    public function index(Request $request)
    {
        if(!isset($request->href) && empty($request->href)) {
            return '';
        }

        return view('editor::index', ['href' => $request->href]);
    }


}
