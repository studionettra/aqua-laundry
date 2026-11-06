<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class LevelController extends Controller
{
    public function index()
    {
        try {
            $levels = Level::orderBy('id', 'desc')->get();
            $title = 'Data Level';
            $text = 'Apakah Anda yakin ingin menghapus level ini?';
            confirmDelete('Hapus Level', $text);

            return view('level.index', compact('levels', 'title'));
        } catch (\Throwable $e) {
            Log::error('Gagal memuat data level: '.$e->getMessage());
            Alert::toast('Gagal memuat data level.', 'error');

            return redirect()->route('dashboard');
        }
    }

    public function create()
    {
        try {
            $title = 'Buat Level Baru';

            return view('level.create', compact('title'));
        } catch (\Throwable $e) {
            Log::error('Gagal membuka form tambah level: '.$e->getMessage());
            Alert::toast('Gagal membuka form tambah level.', 'error');

            return redirect()->to('level');
        }
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'level_name' => 'required',
        ]);

        try {
            Level::create($validate);
            Alert::toast('Level berhasil ditambahkan.', 'success');

            return redirect()->to('level');
        } catch (\Throwable $e) {
            Log::error('Gagal menambah level: '.$e->getMessage());
            Alert::toast('Gagal menambahkan level. Silakan coba lagi.', 'error');

            return back()->withInput();
        }
    }

    public function show(string $id) {}

    public function edit(string $id)
    {
        try {
            $title = 'Edit Level';
            $edit = Level::find($id);

            return view('level.edit', compact('title', 'edit'));
        } catch (\Throwable $e) {
            Log::error('Gagal membuka form edit level: '.$e->getMessage());
            Alert::toast('Gagal membuka form edit level.', 'error');

            return redirect()->to('level');
        }
    }

    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'level_name' => 'required',
        ]);

        try {
            Level::find($id)->update($validate);
            Alert::toast('Level berhasil diperbarui.', 'success');

            return redirect()->to('level');
        } catch (\Throwable $e) {
            Log::error('Gagal memperbarui level: '.$e->getMessage());
            Alert::toast('Gagal memperbarui level. Silakan coba lagi.', 'error');

            return back()->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            $level = Level::find($id);
            if (! $level) {
                Alert::toast('Level tidak ditemukan.', 'error');

                return redirect()->to('level');
            }

            // Mencegah penghapusan level inti sistem
            if (strtolower($level->level_name) === 'administrator') {
                Alert::toast('Gagal menghapus! Level Administrator tidak boleh dihapus.', 'error');

                return redirect()->to('level');
            }

            // Cek apakah level yang akan dihapus sedang digunakan oleh user yang login (berdasarkan ID maupun Nama Level)
            if (auth()->check() && (auth()->user()->id_level == $id || auth()->user()->level->level_name === $level->level_name)) {
                Alert::toast('Gagal menghapus! Anda sedang menggunakan level ini.', 'error');

                return redirect()->to('level');
            }

            // Cek apakah level masih digunakan oleh user manapun di sistem
            if (User::where('id_level', $id)->exists()) {
                Alert::toast('Gagal menghapus! Level ini masih terikat dengan user lain.', 'error');

                return redirect()->to('level');
            }

            $level->delete();
            Alert::toast('Level berhasil dihapus.', 'success');

            return redirect()->to('level');
        } catch (\Throwable $e) {
            Log::error('Gagal menghapus level: '.$e->getMessage());
            Alert::toast('Gagal menghapus level. Silakan coba lagi.', 'error');

            return redirect()->to('level');
        }
    }
}
