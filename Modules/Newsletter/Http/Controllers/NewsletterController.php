<?php

namespace Modules\Newsletter\Http\Controllers;

use Modules\Newsletter\Entities\Newsletters;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Newsletter\Http\Requests\Register;
use Ramsey\Uuid\Uuid;

class NewsletterController extends Controller
{
    public function register(Register $request)
    {
        $newsletter = Newsletters::where('email', $request->email)->first();

        if(!empty($newsletter)){
            if(empty($newsletter->active)) {
                Newsletters::where('id', $newsletter->id)->update([
                    'active' => 1
                ]);
            }else{
                return response(__("newsletter::front.already"), 403)
                    ->header('Content-Type', 'text/plain');
            }
        }else{
            Newsletters::create([
                'uuid' => Uuid::uuid4()->toString(),
                'active' => 1,
                'email' => $request->email
            ]);
        }
        return response(__("newsletter::front.thanks"), 200)
            ->header('Content-Type', 'text/plain');

    }
}
