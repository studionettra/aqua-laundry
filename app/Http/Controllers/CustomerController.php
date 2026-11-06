<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $customers = Customer::orderBy('id', 'desc')->get();
            $title = 'Data Pelanggan';
            confirmDelete('Hapus Pelanggan', 'Apakah Anda yakin ingin menghapus pelanggan ini?');

            return view('customer.index', compact('customers', 'title'));
        } catch (\Throwable $e) {
            Log::error('Gagal memuat data pelanggan: '.$e->getMessage());
            Alert::toast('Gagal memuat data pelanggan.', 'error');

            return redirect()->route('dashboard');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $title = 'Buat Pelanggan Baru';

            return view('customer.create', compact('title'));
        } catch (\Throwable $e) {
            Log::error('Gagal membuka form tambah pelanggan: '.$e->getMessage());
            Alert::toast('Gagal membuka form tambah pelanggan.', 'error');

            return redirect()->to('customer');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'customer_name' => 'required',
            'phone' => 'required|numeric',
            'address' => 'required',
        ]);

        try {
            Customer::create($validate);
            Alert::toast('Pelanggan berhasil ditambahkan.', 'success');

            return redirect()->to('customer');
        } catch (\Throwable $e) {
            Log::error('Gagal menambah pelanggan: '.$e->getMessage());
            Alert::toast('Gagal menambahkan pelanggan. Silakan coba lagi.', 'error');

            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $title = 'Edit Pelanggan';
            $edit = Customer::find($id);

            return view('customer.edit', compact('title', 'edit'));
        } catch (\Throwable $e) {
            Log::error('Gagal membuka form edit pelanggan: '.$e->getMessage());
            Alert::toast('Gagal membuka form edit pelanggan.', 'error');

            return redirect()->to('customer');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'customer_name' => 'required',
            'phone' => 'required|numeric',
            'address' => 'required',
        ]);

        try {
            Customer::find($id)->update($validate);
            Alert::toast('Pelanggan berhasil diperbarui.', 'success');

            return redirect()->to('customer');
        } catch (\Throwable $e) {
            Log::error('Gagal memperbarui pelanggan: '.$e->getMessage());
            Alert::toast('Gagal memperbarui pelanggan. Silakan coba lagi.', 'error');

            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $customer = Customer::find($id);
            if ($customer) {
                $customer->delete();
                Alert::toast('Pelanggan berhasil dihapus.', 'success');
            }

            return redirect()->to('customer');
        } catch (\Throwable $e) {
            Log::error('Gagal menghapus pelanggan: '.$e->getMessage());
            Alert::toast('Gagal menghapus pelanggan. Silakan coba lagi.', 'error');

            return redirect()->to('customer');
        }
    }
}
