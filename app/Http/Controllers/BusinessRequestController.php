<?php

namespace App\Http\Controllers;

use App\Models\BusinessJoinRequest;
use App\Models\User;
use Illuminate\Http\Request;

class BusinessRequestController extends Controller
{
    public function action(Request $request, $id)
    {
        $joinRequest = BusinessJoinRequest::where('business_id', auth()->user()->business->id)
            ->findOrFail($id);

        if ($request->action === 'approve') {
            // 1. Update user business_id and assign staff role
            $user = User::find($joinRequest->user_id);
            $user->business_id = $joinRequest->business_id;
            $user->role = 'staf';
            $user->save();

            // 2. Hapus request karena sudah diterima
            $joinRequest->delete();

            return back()->with('success', 'Anggota berhasil ditambahkan ke tim.');
        }

        if ($request->action === 'reject') {
            // Hapus request
            $joinRequest->delete();
            return back()->with('success', 'Permintaan bergabung ditolak.');
        }

        return back();
    }
}
