<?php

namespace App\Http\Controllers;

use App\Models\TransLaundryPickup;
use App\Models\TransOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class TransLaundryPickupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Mengambil order yang statusnya 3 (Siap Diambil)
            $orders = TransOrder::with('customer')
                ->where('order_status', 3)
                ->orderBy('id', 'desc')
                ->get();

            $title = 'Data Pengambilan Laundry';

            return view('pickup.index', compact('orders', 'title'));
        } catch (\Throwable $e) {
            Log::error('Gagal memuat data antrean pickup: '.$e->getMessage());
            Alert::toast('Gagal memuat data antrean pickup.', 'error');

            return redirect()->route('dashboard');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->to('pickup');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validate = $request->validate([
            'id_order' => 'required|exists:trans_order,id',
            'notes' => 'nullable|string',
            'payment_method' => 'nullable|string|in:Cash,Transfer,QRIS',
        ]);

        DB::beginTransaction();
        try {
            // Ambil data order
            $order = TransOrder::findOrFail($request->id_order);

            // Validasi apakah status order sudah "Siap Diambil" (3)
            if ($order->order_status != 3) {
                Alert::toast('Pesanan belum siap diambil! Pastikan status sudah Siap Diambil.', 'error');
                return back();
            }

            // Cek apakah sudah dibayar di awal
            $isAlreadyPaid = $order->order_pay !== null && $order->order_pay >= $order->total;

            $orderPay = 0;
            $change = 0;

            if ($isAlreadyPaid) {
                // Jika sudah dibayar, gunakan data yang ada di database
                $orderPay = $order->order_pay;
                $change = $order->order_change ?? ($orderPay - $order->total);
            } else {
                // Jika belum, validasi order_pay dari request
                $request->validate([
                    'order_pay' => 'required|numeric|min:0',
                ]);
                $orderPay = $request->order_pay;
                $change = $orderPay - $order->total;

                if ($change < 0) {
                    // Jika uang kurang, batalkan proses
                    Alert::toast('Uang pembayaran kurang dari total tagihan!', 'error');

                    return back()->withInput();
                }
            }

            // Tahap 1: Update status dan pembayaran di tabel trans_order
            $updateData = [
                'order_status' => 4, // 4 = Sudah Diambil
                'order_pay' => $orderPay,
                'order_change' => $change,
            ];

            if (! $isAlreadyPaid && $request->payment_method) {
                $updateData['payment_method'] = $request->payment_method;
            }

            $order->update($updateData);

            // Tahap 2: Catat riwayat pengambilan di tabel trans_laundry_pickup
            $notes = $request->notes;
            if (! $isAlreadyPaid) {
                // Tambahkan penanda otomatis agar UI tahu ini dibayar di akhir (Bayar saat Pickup)
                $notes = $notes ? $notes.' (Lunas dibayar saat Pickup)' : '(Lunas dibayar saat Pickup)';
            }

            TransLaundryPickup::create([
                'id_order' => $order->id,
                'id_customer' => $order->id_customer,
                'pickup_date' => date('Y-m-d'),
                'notes' => $notes,
            ]);

            DB::commit();
            Alert::toast('Pengambilan Laundry Berhasil Diproses.', 'success');

            return redirect()->to('pickup');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal memproses pickup: '.$e->getMessage());
            Alert::toast('Terjadi kesalahan saat menyimpan data pengambilan. Silakan coba lagi.', 'error');

            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Fitur show detail pickup
            $title = 'Pengambilan Laundry';
            $pickup = TransLaundryPickup::with(['order.details.service', 'customer'])->findOrFail($id);

            return view('pickup.show', compact('title', 'pickup'));
        } catch (\Throwable $e) {
            Log::error('Gagal melihat detail pickup: '.$e->getMessage());
            Alert::toast('Gagal melihat detail pickup.', 'error');

            return redirect()->to('pickup');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Alert::toast('Fitur edit dinonaktifkan untuk data pengambilan.', 'warning');

        return redirect()->to('pickup');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $pickup = TransLaundryPickup::find($id);
            if ($pickup) {
                $pickup->delete();
                Alert::toast('Data pengambilan berhasil dihapus.', 'success');
            }

            return redirect()->to('pickup');
        } catch (\Throwable $e) {
            Log::error('Gagal menghapus data pengambilan: '.$e->getMessage());
            Alert::toast('Gagal menghapus data pengambilan.', 'error');

            return redirect()->to('pickup');
        }
    }
}
