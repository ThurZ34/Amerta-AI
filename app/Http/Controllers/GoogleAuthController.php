<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirect() {
        return Socialite::driver('google')->redirect();
    }

    public function callback() {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())
                    ->orWhere('email', $googleUser->getEmail())
                    ->first();

            if ($user) {
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }

                Auth::login($user);

                return redirect('dashboard-selection');
            } else {
                $newUser = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => null,
                    'email_verified_at' => now(),
                ]);

                Auth::login($newUser);

                $user = $newUser;
            }

            if ($user->business_id) {
                return redirect()->intended('analisis.dashboard');
            } else {
                return redirect('dashboard-selection');
            }
         } catch (Exception $e) {
            return redirect('/login')->with('error', 'Login Google Gagal: ' . $e->getMessage());
         }
    }
}
