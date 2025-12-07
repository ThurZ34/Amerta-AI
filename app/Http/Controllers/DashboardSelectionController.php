<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessJoinRequest;
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

        if ($business->jumlah_tim && $business->users()->count() >= $business->jumlah_tim) {
            return back()->withErrors(['invite_code' => 'Maaf, kuota tim bisnis ini sudah penuh.']);
        }

        $existingRequest = BusinessJoinRequest::where('user_id', Auth::id())
            ->where('business_id', $business->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return back()->with('info', 'Permintaan Anda sebelumnya masih menunggu persetujuan.');
        }

        BusinessJoinRequest::create([
            'business_id' => $business->id,
            'user_id' => Auth::id(),
            'status' => 'pending'
        ]);

        return back()->with('success_request', 'Permintaan bergabung telah dikirim ke Owner ' . $business->nama_bisnis . '. Silakan tunggu persetujuan.');
    }

    public function cancelRequest($id)
    {
        BusinessJoinRequest::where('id', $id)->where('user_id', Auth::id())->delete();
        return back()->with('success', 'Permintaan dibatalkan.');
    }
}
