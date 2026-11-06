<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class TaxController extends Controller
{
    public function index()
    {
        try {
            $title = 'Manajemen Pajak';

            $taxPercentage = Setting::firstOrCreate(['key' => 'tax_percentage'], ['value' => '11'])->value;
            $isTaxActive = Setting::firstOrCreate(['key' => 'tax_is_active'], ['value' => '1'])->value;

            $tax = (object) [
                'id' => 1,
                'tax_percentage' => (int) $taxPercentage,
                'is_active' => (int) $isTaxActive,
            ];

            return view('tax.index', compact('title', 'tax'));
        } catch (\Throwable $e) {
            Log::error('Gagal memuat data pajak: '.$e->getMessage());
            Alert::toast('Gagal memuat data pajak.', 'error');

            return redirect()->route('dashboard');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tax_percentage' => 'required|integer|min:0|max:100',
        ]);

        try {
            Setting::updateOrCreate(['key' => 'tax_percentage'], ['value' => $request->tax_percentage]);
            Setting::updateOrCreate(['key' => 'tax_is_active'], ['value' => $request->has('is_active') ? '1' : '0']);

            Alert::toast('Pajak berhasil diperbarui.', 'success');

            return redirect()->route('tax.index');
        } catch (\Throwable $e) {
            Log::error('Gagal memperbarui pajak: '.$e->getMessage());
            Alert::toast('Gagal memperbarui pajak. Silakan coba lagi.', 'error');

            return back()->withInput();
        }
    }
}
