<?php

namespace App\Http\Controllers\Auth;

use Modules\User\Entities\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Ramsey\Uuid\Uuid;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/compte';

    /**
     * Create a new controller instance.
     *
     *
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $newsletter = 0;
        if(isset($data['newsletter']) && !empty($data['newsletter'])) {
            $newsletter = 1;
        }

        return User::create([
            'uuid' => Uuid::uuid4()->toString(),
            'gender' => $data['gender'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'username' => str_slug($data['firstname'] . "-" . $data['lastname']),
            'email' => $data['email'],
            'newsletter' => $newsletter,
            'role' => 'user',
            'password' => bcrypt($data['password']),
        ]);
    }
}
