<?php

namespace Modules\Contact\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Contact\Emails\SendContactEmail;
use Modules\Contact\Http\Requests\SendEmailRequest;
use Modules\Content\Entities\Content;

class ContactController extends Controller
{
    protected $viewPath = 'contact::';

    public function index()
    {
        $arianne = [
            __('generals.home') => '/',
            __('contact::front.title') => route('contact.index')
        ];
        $contents = Content::select('id','image','name','slug','summary','content_category_id','updated_at')
            ->where('published', 1)
            ->where('on_homepage', 1)
            ->orderBy('order','ASC')
            ->get();

        return view($this->viewPath . 'index', compact('arianne', 'contents'));
    }

    public function send(SendEmailRequest $request)
    {
        $contact = [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'message' => $request->message,
        ];
        Mail::to(setting('generals.email'))->send(new SendContactEmail($contact));

        session()->flash('success',__("contact::front.success"));
        return redirect()->route('contact.index');

    }

}
