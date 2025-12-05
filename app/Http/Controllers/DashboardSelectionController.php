<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardSelectionController extends Controller
{
    public function index()
    {
        return view('dashboard-selection');
    }

    public function join(Request $request)
    {
        $request->validate([
            'invite_code' => 'required|string|exists:businesses,invite_code',
        ]);

        $business = Business::where('invite_code', $request->invite_code)->firstOrFail();

        if ($business->jumlah_tim <= 1) {
            return back()
            ->withErrors(['invite_code' => 'Maaf, bisnis ini sedang tidak menerima staff'])
            ->withInput();
        }

        $user = Auth::user();
        $user->business_id = $business->id;
        $user->save();

        session()->flash('first_time_entry', true);

        return redirect()->route('dashboard')->with('success', 'Berhasil bergabung dengan tim ' . $business->nama_bisnis);
    }
}
