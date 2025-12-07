<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Business;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function bussiness_index(Request $request)
    {
        $user = auth()->user();
        $business = $user->ownedBusiness ?? $user->business;

        $categories = Category::orderBy('name')->get();

        $owner = $business ? User::where('business_id', $business->id)
            ->where('id', $business->user_id)
            ->first() : null;

        $perPage = $request->get('per_page', 5);
        $staffMembers = $business ? User::where('business_id', $business->id)
            ->where('id', '!=', $business->user_id)
            ->orderBy('name')
            ->paginate($perPage)
            ->appends(['per_page' => $perPage]) : null;

        return view('profil.index', compact('business', 'categories', 'owner', 'staffMembers'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_bisnis' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'nullable|string',
            'target_pasar' => 'nullable|string',
            'jumlah_tim' => 'nullable|string',
            'tujuan_utama' => 'nullable|string',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string',
        ]);

        $business = Business::where('user_id', auth()->user()->id)->first();

        if (!$business) {
            $business = new Business();
            $business->user_id = auth()->user()->id;
        }

        if ($request->filled('kategori')) {
            $category = Category::firstOrCreate(
                ['name' => $request->kategori],
                ['created_at' => now(), 'updated_at' => now()]
            );
            $business->category_id = $category->id;
        }

        if ($request->input('hapus_gambar') == '1' && !$request->hasFile('gambar')) {
            if ($business->gambar && Storage::disk('public')->exists($business->gambar)) {
                Storage::disk('public')->delete($business->gambar);
            }
            $business->gambar = null;
        }

        if ($request->hasFile('gambar')) {
            $request->validate([
                'gambar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($business->gambar && Storage::disk('public')->exists($business->gambar)) {
                Storage::disk('public')->delete($business->gambar);
            }

            $path = $request->file('gambar')->store('business_images', 'public');
            $business->gambar = $path;
        }

        $business->fill($request->except(['kategori', 'gambar', 'hapus_gambar']));
        $business->save();

        return redirect()->route('manajemen.profil-bisnis.index')->with('success', 'Profil bisnis berhasil diperbarui.');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = Category::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'category' => $category,
        ]);
    }

    public function updateInitialCapital(Request $request)
    {
        $request->validate([
            'amount' => 'required',
        ]);

        $cleanAmount = preg_replace('/[^0-9]/', '', $request->amount);

        $user = auth()->user();
        $business = $user->ownedBusiness ?? $user->business;

        if (!$business) {
             return back()->with('error', 'Bisnis tidak ditemukan.');
        }

        $equityCoa = \App\Models\Coa::firstOrCreate(
            ['name' => 'Modal Disetor'],
            ['code' => '31001', 'type' => 'EQUITY']
        );

        $transaction = \App\Models\CashJournal::where('business_id', $business->id)
            ->where('coa_id', $equityCoa->id)
            ->where('description', 'Modal Awal Bisnis')
            ->first();

        if ($transaction) {
            $transaction->update([
                'amount' => $cleanAmount,
            ]);
        } else {
            \App\Models\CashJournal::create([
                'business_id' => $business->id,
                'transaction_date' => now(),
                'coa_id' => $equityCoa->id,
                'amount' => $cleanAmount,
                'is_inflow' => true,
                'payment_method' => 'Transfer',
                'description' => 'Modal Awal Bisnis',
            ]);
        }

        return back()->with('success', 'Modal awal berhasil diperbarui.');
    }
}
