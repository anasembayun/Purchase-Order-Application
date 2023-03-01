<?php

namespace App\Http\Controllers\Auth;

use App\Models\Auth\Role\Role;
use App\Notifications\Auth\ConfirmEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Models\Auth\User\User;
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

    use RegistersUsers {
        // We are doing this so the predefined register method does not clash with the one we just defined.
        register as registration;
    }

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ];

        if (config('auth.captcha.registration')) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User|\Illuminate\Database\Eloquent\Model
     */
    protected function create(array $data)
    {
        /** @var  $user User */
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'google2fa_secret' => $data['google2fa_secret'],
            'password' => bcrypt($data['password']),
            'confirmation_code' => Uuid::uuid4(),
            'confirmed' => false,
            
        ]);

        if (config('auth.users.default_role')) {
            $user->roles()->attach(Role::firstOrCreate(['name' => config('auth.users.default_role')]));
        }

        return $user;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        
        $google2fa = app('pragmarx.google2fa');

        $registration_data = $request->all();

        $registration_data['google2fa_secret'] = $google2fa->generateSecretKey();

        $request->session()->flash('registration_data',$registration_data);

        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            $registration_data['email'],
            $registration_data['google2fa_secret']
        );

        return view('google2fa.register',['QR_Image' => $QR_Image, 'secret' => $registration_data['google2fa_secret']]);

        // event(new Registered($user = $this->create($request->all())));

        // $this->guard()->login($user);

        // return $this->registered($request, $user)
        //     ?: redirect($this->redirectPath());
    }

    public function completeRegistration(Request $request)
    {      
          // add the session data back to the request input
        $request->merge(session('registration_data'));

          // Call the default laravel authentication
        return $this->registration($request);
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        if (config('auth.users.confirm_email') && !$user->confirmed) {

            $this->guard()->logout();

            $user->notify(new ConfirmEmail());

            return redirect(route('login'));
        }
    }
}
