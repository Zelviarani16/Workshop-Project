<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    // AuthenticatesUsers trait mendefinisikan method logout():

    // Inilah sebabnya ketika signout dia dikembalikan ke login
//     public function logout(Request $request)
// {
//     $this->guard()->logout(); // Hapus session login
//     $request->session()->invalidate(); // Reset session
//     $request->session()->regenerateToken(); // Anti CSRF
//     return redirect('/login'); // Redirect ke login
// }


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Halaman  
        $this->middleware('guest')->except('logout'); // halaman login -> hanya utk yg belum login dan kalau sudah login tdk bisa akses halaman login lagi
        $this->middleware('auth')->only('logout'); // hanya bisa logout oleh user yg sudah login
    }

    



}
