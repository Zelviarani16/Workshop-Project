<?php

namespace App\Http\Controllers;

use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;


class AuthController extends Controller
{
    public function redirectGoogle() 
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle()
    {
    /** @var GoogleProvider $provider */
    $provider = Socialite::driver('google');

    $googleUser = $provider->stateless()->user();
        // Cek apakah user sudah ada
        $user = User::where('email', $googleUser->email)->first();

        // Jika belum ada, buat user baru
        if (!$user) {
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'id_google' => $googleUser->id,
                'password' => null,
            ]);
        }

        // Generate OTP
        $otp = rand(100000, 999999);

        $user->otp = $otp;
        $user->save();

        // Kirim email OTP
        Mail::to($user->email)->send(new SendOtpMail($otp));

        // Simpan sementara user id di session
        session(['otp_user_id' => $user->id]);

        // Redirect ke halaman input OTP
        return redirect('/otp');

    }

    public function verifyOtp(Request $request)
    {
        $user = User::find(session('otp_user_id'));

        if ($user && $user->otp == $request->otp) {
            
            Auth::login($user);

            return redirect('/dashboard');
        }

        return back()->with('error', 'OTP salah');
    }
}


