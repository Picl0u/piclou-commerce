<?php

namespace Modules\Website\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use anlutro\LaravelSettings\Facade as Setting;

class AdminSettingsController extends Controller
{

    protected $route = 'settings.generals';

   public function generals()
   {
       $data = [
           'websiteName' => Setting::get('generals.websiteName'),
           'logo' => Setting::get('generals.logo'),
           'firstname' => Setting::get('generals.firstname'),
           'lastname' => Setting::get('generals.lastname'),
           'company' => Setting::get('generals.company'),
           'siret' => Setting::get('generals.siret'),
           'email' => Setting::get('generals.email'),
           'phone' => Setting::get('generals.phone'),
           'address' => Setting::get('generals.address'),
           'zipCode' => Setting::get('generals.zipCode'),
           'city' => Setting::get('generals.city'),
           'invoiceLogo' => Setting::get('generals.LogoInvoice'),
           'invoiceCompany' => Setting::get('generals.invoiceCompany'),
           'invoiceSiret' => Setting::get('generals.invoiceSiret'),
           'invoicePhone' => Setting::get('generals.invoicePhone'),
           'invoiceAddress' => Setting::get('generals.invoiceAddress'),
           'invoiceZipCode' => Setting::get('generals.invoiceZipCode'),
           'invoiceCity' => Setting::get('generals.invoiceCity'),
           'invoiceCountry' => Setting::get('generals.invoiceCountry'),
           'invoiceTVA' => Setting::get('generals.invoiceTVA'),
           'invoiceRCS' => Setting::get('generals.invoiceRCS'),
           'invoiceFooter' => Setting::get('generals.invoiceFooter'),
           'invoiceNote' => Setting::get('generals.invoiceNote'),
           'facebook' => Setting::get('generals.facebook'),
           'twitter' => Setting::get('generals.twitter'),
           'pinterest' => Setting::get('generals.pinterest'),
           'googlePlus' => Setting::get('generals.googlePlus'),
           'instagram' => Setting::get('generals.instagram'),
           'youtube' => Setting::get('generals.youtube'),
           'seoRobot' => Setting::get('generals.seoRobot'),
           'analytics' => Setting::get('generals.analytics'),
           'seoTitle' => Setting::get('generals.seoTitle'),
           'seoDescription' => Setting::get('generals.seoDescription'),
       ];
       return view('website::admin.settings',compact('data','countriesArray', 'contentsArray'));
   }
   
   public function storeGenerals(Request $request)
   {

       if(config('ikCommerce.demo')) {
           session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
           return redirect()->route($this->route);
       }

       $seoRobot = 0;
       if ($request->seoRobot == "on") {
           $seoRobot = 1;
       }

       $insertLogo = Setting::get('generals.logo');
       if($request->hasFile('logo')){
           $insertLogo = uploadImage('settings', $request->logo);
           if(!empty(Setting::get('generals.logo'))) {
               if(file_exists(Setting::get('generals.logo'))) {
                   unlink(Setting::get('generals.logo'));
               }
           }
       }

       $insertLogoInvoice = Setting::get('generals.LogoInvoice');
       if($request->hasFile('invoiceLogo')){
           $insertLogoInvoice = uploadImage('invoices', $request->invoiceLogo);
           if(!empty(Setting::get('generals.LogoInvoice'))) {
               if(file_exists(Setting::get('generals.LogoInvoice'))) {
                   unlink(Setting::get('generals.LogoInvoice'));
               }
           }
       }

       Setting::set('generals.websiteName', $request->websiteName);
       Setting::set('generals.logo', $insertLogo);
       Setting::set('generals.firstname', $request->firstname);
       Setting::set('generals.company', $request->company);
       Setting::set('generals.siret', $request->siret);
       Setting::set('generals.email', $request->email);
       Setting::set('generals.phone', $request->phone);
       Setting::set('generals.address', $request->address);
       Setting::set('generals.zipCode', $request->zipCode);
       Setting::set('generals.city', $request->city);
       Setting::set('generals.LogoInvoice', $insertLogoInvoice);
       Setting::set('generals.invoiceCompany', $request->invoiceCompany);
       Setting::set('generals.invoiceSiret', $request->invoiceSiret);
       Setting::set('generals.invoiceAddress', $request->invoiceAddress);
       Setting::set('generals.invoiceZipCode', $request->invoiceZipCode);
       Setting::set('generals.invoiceCity', $request->invoiceCity);
       Setting::set('generals.invoiceCountry', $request->invoiceCountry);
       Setting::set('generals.invoiceTVA', $request->invoiceTVA);
       Setting::set('generals.invoiceRCS', $request->invoiceRCS);
       Setting::set('generals.invoiceFooter', $request->invoiceFooter);
       Setting::set('generals.invoiceNote', $request->invoiceNote);
       Setting::set('generals.facebook', $request->facebook);
       Setting::set('generals.twitter', $request->twitter);
       Setting::set('generals.pinterest', $request->pinterest);
       Setting::set('generals.instagram', $request->instagram);
       Setting::set('generals.youtube', $request->youtube);
       Setting::set('generals.seoRobot', $seoRobot);
       Setting::set('generals.analytics', $request->analytics);
       Setting::set('generals.seoTitle', $request->seoTitle);
       Setting::set('generals.seoDescription', $request->seoDescription);

       Setting::save();

       session()->flash('success','Les paramètres ont bien été mis à jours');
       return redirect()->route($this->route);
   }
}
