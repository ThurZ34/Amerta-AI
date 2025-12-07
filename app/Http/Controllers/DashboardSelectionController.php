<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessJoinRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardSelectionController extends Controller
{
    public function index()
    {
        $rejectedRequest = BusinessJoinRequest::where('user_id', Auth::id())
            ->where('status', 'rejected')
            ->with('business')
            ->first();

        if ($rejectedRequest) {
            $businessName = $rejectedRequest->business->nama_bisnis;

            $rejectedRequest->delete();

            return redirect()
                ->route('dashboard-selection')
                ->with('rejection_alert', "Maaf, permintaan Anda ditolak oleh Owner dari bisnis: $businessName.");
        }

        // Cek apakah user punya request yang masih pending
        $pendingRequest = BusinessJoinRequest::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->with('business')
            ->first();

        return view('dashboard-selection', compact('pendingRequest'));
    }

    public function join(Request $request)
    {
        $request->validate([
            'invite_code' => 'required|string|exists:businesses,invite_code',
        ]);

        $business = Business::where('invite_code', $request->invite_code)->firstOrFail();

        // Cek apakah bisnis penuh (hanya jika jumlah_tim sudah di-set)
        if ($business->jumlah_tim && $business->users()->count() >= $business->jumlah_tim) {
            return back()->withErrors(['invite_code' => 'Maaf, kuota tim bisnis ini sudah penuh.']);
        }

        // Cek apakah sudah pernah request sebelumnya
        $existingRequest = BusinessJoinRequest::where('user_id', Auth::id())
            ->where('business_id', $business->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return back()->with('info', 'Permintaan Anda sebelumnya masih menunggu persetujuan.');
        }

        // BUAT REQUEST BARU (Bukan langsung join)
        BusinessJoinRequest::create([
            'business_id' => $business->id,
            'user_id' => Auth::id(),
            'status' => 'pending'
        ]);

        // Kirim SweetAlert trigger ke session
        return back()->with('success_request', 'Permintaan bergabung telah dikirim ke Owner ' . $business->nama_bisnis . '. Silakan tunggu persetujuan.');
    }

    public function cancelRequest($id)
    {
        BusinessJoinRequest::where('id', $id)->where('user_id', Auth::id())->delete();
        return back()->with('success', 'Permintaan dibatalkan.');
    }
}
