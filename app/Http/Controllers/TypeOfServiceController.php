<?php

namespace App\Http\Controllers;

use App\Models\TypeOfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class TypeOfServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $services = TypeOfService::where('service_name', '!=', 'Pajak PPN')->orderBy('id', 'desc')->get();
            $title = 'Data Layanan';
            confirmDelete('Hapus Layanan', 'Apakah Anda yakin ingin menghapus layanan ini?');

            return view('service.index', compact('services', 'title'));
        } catch (\Throwable $e) {
            Log::error('Gagal memuat data layanan: '.$e->getMessage());
            Alert::toast('Gagal memuat data layanan.', 'error');

            return redirect()->route('dashboard');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $title = 'Buat Layanan Baru';

            return view('service.create', compact('title'));
        } catch (\Throwable $e) {
            Log::error('Gagal membuka form tambah layanan: '.$e->getMessage());
            Alert::toast('Gagal membuka form tambah layanan.', 'error');

            return redirect()->to('service');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_name' => 'required|unique:type_of_service,service_name',
            'price' => 'required|numeric',
            'description' => 'nullable',
        ], [
            'service_name.unique' => 'Nama layanan ini sudah digunakan, silakan pilih nama lain.',
        ]);

        if ($validator->fails()) {
            Alert::toast($validator->errors()->first(), 'error');

            return back()->withInput();
        }

        $validate = $validator->validated();

        try {
            TypeOfService::create($validate);
            Alert::toast('Layanan berhasil ditambahkan.', 'success');

            return redirect()->to('service');
        } catch (\Throwable $e) {
            Log::error('Gagal menambah layanan: '.$e->getMessage());
            Alert::toast('Gagal menambahkan layanan. Silakan coba lagi.', 'error');

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
            $title = 'Edit Layanan';
            $edit = TypeOfService::find($id);

            return view('service.edit', compact('title', 'edit'));
        } catch (\Throwable $e) {
            Log::error('Gagal membuka form edit layanan: '.$e->getMessage());
            Alert::toast('Gagal membuka form edit layanan.', 'error');

            return redirect()->to('service');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'service_name' => 'required|unique:type_of_service,service_name,'.$id,
            'price' => 'required|numeric',
            'description' => 'nullable',
        ], [
            'service_name.unique' => 'Nama layanan ini sudah digunakan, silakan pilih nama lain.',
        ]);

        if ($validator->fails()) {
            Alert::toast($validator->errors()->first(), 'error');

            return back()->withInput();
        }

        $validate = $validator->validated();

        try {
            TypeOfService::find($id)->update($validate);
            Alert::toast('Layanan berhasil diperbarui.', 'success');

            return redirect()->to('service');
        } catch (\Throwable $e) {
            Log::error('Gagal memperbarui layanan: '.$e->getMessage());
            Alert::toast('Gagal memperbarui layanan. Silakan coba lagi.', 'error');

            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $service = TypeOfService::find($id);
            if ($service) {
                $service->delete();
                Alert::toast('Layanan berhasil dihapus.', 'success');
            }

            return redirect()->to('service');
        } catch (\Throwable $e) {
            Log::error('Gagal menghapus layanan: '.$e->getMessage());
            Alert::toast('Gagal menghapus layanan. Silakan coba lagi.', 'error');

            return redirect()->to('service');
        }
    }
}
