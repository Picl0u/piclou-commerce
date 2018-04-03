<?php

namespace Modules\User\Http\Controllers;

use Modules\User\Entities\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\UsersAdresses;
use Modules\Order\Entities\Countries;
use Modules\User\Http\Requests\AddressRequest;
use Modules\User\Http\Requests\UpdateAccountRequest;
use Ramsey\Uuid\Uuid;

class UserController extends Controller
{

    protected $viewPath = 'user::';

    /**
     * Page mon compte
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $arianne = [
            __('generals.home') => '/',
            __('user::user.my_account') => route('user.account')
        ];
        return view($this->viewPath . "index",compact('arianne'));
    }

    /**
     * Page mes informations
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function informations()
    {
        $user = Auth::user();
        $arianne = [
            __('generals.home') => '/',
            __('user::user.my_account') => route('user.account'),
            __('user::user.my_informations') => route('user.infos'),
        ];
        return view($this->viewPath . "infos", compact('user', 'arianne'));
    }

    /**
     * Modification du compte
     * @return \Illuminate\Http\RedirectResponse
     */
    public function informationsUpdate(UpdateAccountRequest $request)
    {
        $user = Auth::user();

        $newsletter = 0;
        if($request->newsletter == 'on') {
            $newsletter = 1;
        }
        $update = [
            'gender' => $request->gender,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'newsletter' => $newsletter
        ];

        if (!empty($request->password)) {
            $update['password'] = bcrypt($request->password);
        }

        User::where('id', $user->id)->update($update);

        session()->flash('success', __('user::user.infos_update_success'));
        return redirect()->route('user.infos');
    }

    /**
     * Liste des adresses
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addresses()
    {
        $addresses = UsersAdresses::where('user_id', Auth::user()->id)->orderBy('id','DESC')->get();

        $arianne = [
            __('generals.home') => '/',
            __('user::user.my_account') => route('user.account'),
            __('user::user.my_addresses') => route('user.addresses'),
        ];

        return view($this->viewPath . "addresses", compact('addresses', 'arianne'));
    }

    /**
     * Page d'ajout d'une nouvelle adresse
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addressesCreate()
    {
        $countries = Countries::where('activated', 1)->orderBy('name','asc')->get();

        $arianne = [
            __('generals.home') => '/',
            __('user::user.my_account') => route('user.account'),
            __('user::user.my_addresses') => route('user.addresses'),
            __('user::user.add_address') => route('user.addresses.create'),
        ];

        $data = new UsersAdresses();
        $data->gender = Auth::user()->gender;
        $data->firstname = Auth::user()->firstname;
        $data->lastname = Auth::user()->lastname;

        return view(
            $this->viewPath . "address.create",
            compact('countries', 'data', 'arianne')
        );

    }

    /**
     * @param AddressRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addressesStore(AddressRequest $request)
    {

        $billing = 0;
        if($request->billing == 'on') {
            $billing = 1;
        }

        $insert = [
            'uuid' => Uuid::uuid4()->toString(),
            'user_id' => Auth::user()->id,
            'gender' => $request->gender,
            'delivery' => 1,
            'billing' => $billing,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'address' => $request->address,
            'additional_address' => $request->additional_address,
            'zip_code' => $request->zip_code,
            'city' => $request->city,
            'phone' => $request->phone,
            'country_id' => $request->country_id
        ];
        UsersAdresses::create($insert);

        session()->flash('success', __('user::user.address_created'));
        return redirect()->route('user.addresses');
    }

    /**
     * Page d'édition d'adresse
     * @param string $uuid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addressesEdit(string $uuid)
    {
        $data = UsersAdresses::where('uuid', $uuid)
            ->where('user_id', Auth::user()->id)
            ->FirstOrFail();

        $countries = Countries::where('activated', 1)->orderBy('name','asc')->get();

        $arianne = [
            __('generals.home') => '/',
            __('user::user.my_account') => route('user.account'),
            __('user::user.my_addresses') => route('user.addresses'),
            __('user::user.edit_address') => route('user.addresses.edit',['uuid' => $data->uuid]),
        ];
        return view(
            $this->viewPath . "address.edit",
            compact('countries', 'data', 'arianne')
        );
    }

    /**
     * @param AddressRequest $request
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addressesUpdate(AddressRequest $request, string $uuid)
    {
        $address = UsersAdresses::where('uuid', $uuid)
            ->where('user_id', Auth::user()->id)
            ->FirstOrFail();

        $billing = 0;
        if($request->billing == 'on') {
            $billing = 1;
        }

        UsersAdresses::where('id', $address->id)->update([
            'gender' => $request->gender,
            'billing' => $billing,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'address' => $request->address,
            'additional_address' => $request->additional_address,
            'zip_code' => $request->zip_code,
            'city' => $request->city,
            'phone' => $request->phone,
            'country_id' => $request->country_id
        ]);

        session()->flash('success', __('user::user.address_updated'));
        return redirect()->route('user.addresses');

    }

    /**
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addressesDelete(string $uuid)
    {
        $address = UsersAdresses::where('uuid', $uuid)
            ->where('user_id', Auth::user()->id)
            ->FirstOrFail();

        UsersAdresses::where('id', $address->id)->delete();

        session()->flash('success', __('user::user.address_deleted'));
        return redirect()->route('user.addresses');

    }

}
