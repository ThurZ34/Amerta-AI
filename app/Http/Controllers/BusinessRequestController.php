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
            $user = User::find($joinRequest->user_id);
            $user->business_id = $joinRequest->business_id;
            $user->role = 'staf';
            $user->save();

            $joinRequest->delete();

            return back()->with('success', 'Anggota berhasil ditambahkan ke tim.');
        }

        if ($request->action === 'reject') {
            $joinRequest->update(['status' => 'rejected']);
            return back()->with('success', 'Permintaan bergabung ditolak.');
        }

        return back();
    }
}
