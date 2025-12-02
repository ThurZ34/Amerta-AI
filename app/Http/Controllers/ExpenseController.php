<?php

namespace App\Http\Controllers;

use App\Models\CashJournal;
use App\Models\Coa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpenseController extends Controller
{
    public function create()
    {
        $coaOptions = Coa::orderBy('type')
            ->orderBy('name')
            ->get()
            ->groupBy('type');

        return view('expenses.create', compact('coaOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_date' => 'required|date',
            'coa_id' => 'required|exists:coa,id',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
            'payment_method' => 'required|string|max:50',
        ]);

        $coa = Coa::findOrFail($request->coa_id);

        DB::beginTransaction();
        try {
            CashJournal::create([
                'transaction_date' => $request->transaction_date,
                'coa_id' => $request->coa_id,
                'amount' => $request->amount,
                'is_inflow' => ($coa->type === 'INFLOW'),
                'payment_method' => $request->payment_method,
                'description' => $request->description,
            ]);

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Transaksi kas (' . $coa->type . ') berhasil dicatat!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error storing general cash transaction: " . $e->getMessage());
            return back()->withInput()->withErrors('Gagal mencatat transaksi kas: ' . $e->getMessage());
        }
    }
}
