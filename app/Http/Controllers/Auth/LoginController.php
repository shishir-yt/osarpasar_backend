<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;



    public function loginUser(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);
        $user = User::where('email', $request->email)->first();
        $remember_me  = ( !empty( $request->remember ) ) ? TRUE : FALSE;
        // Crypt::encryptString()

        if ($user){
            if (Crypt::decryptString($user->password) === $request->password) {

                auth()->guard()->login($user, $remember_me);
                // dd($user->is_admin);
                if($user->is_admin == "1") {
                    // return redirect('/admin/home');
                    return redirect()->route('adminHome');
                } elseif($user->is_admin == "0") {
                    return redirect()->route('serviceProvider.home');
                } else {
                    return back()->with('passwordError', 'Oops! The data you have entered is not in the database.');
                }
            }else {
                return back()->with('passwordError', 'Oops! You have entered an invalid password. Please try again.');
            }


        } else {
            return back()->with('emailError', 'Oops! You have entered an invalid email. Please try again.');
        }
        return back()->withInput($request->only('email', 'remember'));
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:web')->except('logout');
    }
}