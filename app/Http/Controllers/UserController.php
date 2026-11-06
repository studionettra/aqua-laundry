<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\User;
// use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::with('level')->orderBy('id', 'asc')->get();
            $title = 'Data Pengguna';
            $text = 'Apakah Anda yakin ingin menghapus pengguna ini?';
            confirmDelete('Hapus Pengguna', $text);

            return view('user.index', compact('users', 'title', 'text'));
        } catch (\Throwable $e) {
            Log::error('Gagal memuat data pengguna: '.$e->getMessage());
            Alert::toast('Gagal memuat data pengguna.', 'error');

            return redirect()->route('dashboard');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            if (auth()->id() !== 1) {
                Alert::toast('Hanya Administrator Utama yang dapat menambah pengguna baru!', 'error');

                return redirect()->back();
            }
            $title = 'Buat Pengguna Baru';
            $levels = Level::get();

            return view('user.create', compact('title', 'levels'));
        } catch (\Throwable $e) {
            Log::error('Gagal membuka form tambah pengguna: '.$e->getMessage());
            Alert::toast('Gagal membuka form tambah pengguna.', 'error');

            return redirect()->to('user');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->id() !== 1) {
            Alert::toast('Hanya Administrator Utama yang dapat menambah pengguna baru!', 'error');

            return redirect()->back();
        }

        $validate = $request->validate([
            'id_level' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|min:6',
        ]);

        DB::beginTransaction();
        try {
            $validate['password'] = Hash::make($request->password);

            User::create($validate);
            DB::commit();

            Alert::toast('Pengguna berhasil ditambahkan.', 'success');

            return redirect()->to('user');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal menambah pengguna: '.$e->getMessage());
            Alert::toast('Gagal menambahkan pengguna. Silakan coba lagi.', 'error');

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
            if ($id == 1 && auth()->id() != 1) {
                Alert::toast('Anda tidak memiliki akses untuk mengedit Administrator Utama!', 'error');

                return redirect()->back();
            }
            $title = 'Edit Pengguna';
            $edit = User::find($id); // blank
            $levels = Level::get();

            return view('user.edit', compact('title', 'edit', 'levels'));
        } catch (\Throwable $e) {
            Log::error('Gagal membuka form edit pengguna: '.$e->getMessage());
            Alert::toast('Gagal membuka form edit pengguna.', 'error');

            return redirect()->to('user');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if ($id == 1 && auth()->id() != 1) {
            Alert::toast('Anda tidak memiliki akses untuk memperbarui Administrator Utama!', 'error');

            return redirect()->back();
        }

        $validate = $request->validate([
            'id_level' => 'sometimes|required|exists:level,id',
            'name' => 'required',
            'email' => 'required|email|unique:user,email,'.$id,
            'password' => 'nullable|min:6',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
            ];
            // If user don't enter the password
            if (filled($request->password)) {
                $data['password'] = Hash::make($request->password);
            }
            $user = User::find($id);
            if ($request->has('id_level')) {
                $data['id_level'] = $request->id_level;
            }

            $user->update($data);
            DB::commit();

            Alert::toast('Pengguna berhasil diperbarui.', 'success');

            return redirect()->to('user');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui pengguna: '.$e->getMessage());
            Alert::toast('Gagal memperbarui pengguna. Silakan coba lagi.', 'error');

            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::find($id);
            if ($user) {
                if ($user->id === 1) {
                    Alert::toast('Akun Administrator Utama (ID 1) tidak dapat dihapus oleh siapa pun!', 'error');

                    return redirect()->back();
                }
                if ($user->id === auth()->id()) {
                    Alert::toast('Tidak bisa menghapus diri sendiri!', 'error');

                    return redirect()->back();
                }
                $user->delete();
                Alert::toast('Pengguna berhasil dihapus.', 'success');
            }

            return redirect()->to('user');
        } catch (\Throwable $e) {
            Log::error('Gagal menghapus pengguna: '.$e->getMessage());
            Alert::toast('Gagal menghapus pengguna. Silakan coba lagi.', 'error');

            return redirect()->to('user');
        }
    }
}
