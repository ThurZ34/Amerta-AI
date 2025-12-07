<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class ForgotPasswordOtpController extends Controller
{
    // Kirim OTP ke email
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan.',
            ], 404);
        }

        $otp = rand(100000, 999999); // 6 digit

        // simpan atau update OTP
        PasswordOtp::updateOrCreate(
            ['email' => $request->email, 'used' => false],
            [
                'otp'        => $otp,
                'expires_at' => Carbon::now()->addMinutes(10),
                'used'       => false,
            ]
        );

        // kirim email sederhana
        Mail::raw("Kode OTP reset password Anda: {$otp}", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Kode OTP Reset Password');
        });

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP telah dikirim ke email Anda.',
        ]);
    }

    // Verifikasi OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp'   => ['required', 'digits:6'],
        ]);

        $record = PasswordOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (! $record) {
            return response()->json([
                'success' => false,
                'message' => 'OTP tidak valid atau sudah kadaluarsa.',
            ], 422);
        }

        // tandai otp ini sebagai "terverifikasi sementara"
        // bisa simpan di session agar aman
        session([
            'password_otp_email' => $request->email,
            'password_otp_code'  => $request->otp,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'OTP valid, silakan buat password baru.',
        ]);
    }

    // Simpan password baru
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'otp'      => ['required', 'digits:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $record = PasswordOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (! $record) {
            return response()->json([
                'success' => false,
                'message' => 'OTP tidak valid atau sudah kadaluarsa.',
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        // update password
        $user->password = Hash::make($request->password);
        $user->save();

        // OTP ditandai sudah digunakan
        $record->used = true;
        $record->save();

        // hapus session
        session()->forget(['password_otp_email', 'password_otp_code']);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil direset. Silakan login kembali.',
        ]);
    }
}
