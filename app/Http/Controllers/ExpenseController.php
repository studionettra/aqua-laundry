<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Data Pengeluaran';
        $expenses = Expense::orderBy('expense_date', 'desc')->orderBy('id', 'desc')->get();

        return view('expense.index', compact('title', 'expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Pengeluaran';

        return view('expense.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            Expense::create($request->all());
            Alert::toast('Data pengeluaran berhasil ditambahkan.', 'success');

            return redirect()->route('expense.index');
        } catch (\Throwable $e) {
            Log::error('Gagal tambah pengeluaran: '.$e->getMessage());
            Alert::toast('Gagal menambahkan data pengeluaran.', 'error');

            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not used
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = 'Edit Pengeluaran';
        $expense = Expense::findOrFail($id);

        return view('expense.edit', compact('title', 'expense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $expense = Expense::findOrFail($id);
            $expense->update($request->all());
            Alert::toast('Data pengeluaran berhasil diperbarui.', 'success');

            return redirect()->route('expense.index');
        } catch (\Throwable $e) {
            Log::error('Gagal update pengeluaran: '.$e->getMessage());
            Alert::toast('Gagal memperbarui data pengeluaran.', 'error');

            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $expense = Expense::findOrFail($id);
            $expense->delete();
            Alert::toast('Data pengeluaran berhasil dihapus.', 'success');

            return redirect()->route('expense.index');
        } catch (\Throwable $e) {
            Log::error('Gagal hapus pengeluaran: '.$e->getMessage());
            Alert::toast('Gagal menghapus data pengeluaran.', 'error');

            return back();
        }
    }
}
