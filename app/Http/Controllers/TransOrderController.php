<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Setting;
use App\Models\TransOrder;
use App\Models\TransOrderDetail;
use App\Models\TypeOfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class TransOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Penjelasan: Mengambil semua data transaksi pesanan beserta relasi customer dan pickup-nya
            $orders = TransOrder::with(['customer', 'pickup'])->orderBy('id', 'desc')->get();
            $title = 'Pesanan Laundry';
            confirmDelete('Hapus Pesanan', 'Apakah Anda yakin ingin menghapus pesanan ini?');

            return view('transaction.index', compact('orders', 'title'));
        } catch (\Throwable $e) {
            Log::error('Gagal memuat data pesanan: '.$e->getMessage());
            Alert::toast('Gagal memuat data pesanan.', 'error');

            return redirect()->route('dashboard');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $title = 'Buat Pesanan Laundry Baru';
            $customers = Customer::orderBy('customer_name', 'asc')->get();
            $services = TypeOfService::orderBy('service_name', 'asc')->get();
            $tax_percentage = (int) Setting::where('key', 'tax_percentage')->value('value') ?? 0;
            $tax_is_active = (int) Setting::where('key', 'tax_is_active')->value('value') ?? 0;

            // Penjelasan: Kita membuat string array dari data services untuk mempermudah render dropdown di JS dinamis
            return view('transaction.create', compact('title', 'customers', 'services', 'tax_percentage', 'tax_is_active'));
        } catch (\Throwable $e) {
            Log::error('Gagal membuka form buat pesanan: '.$e->getMessage());
            Alert::toast('Gagal membuka form buat pesanan.', 'error');

            return redirect()->to('transaction');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi form utama
        $request->validate([
            'id_customer' => 'required|exists:customer,id',
            'order_date' => 'required|date',
            'order_end_date' => 'nullable|date',
            'service_id' => 'required|array',
            'service_id.*' => 'required|exists:type_of_service,id',
            'qty' => 'required|array',
            'qty.*' => 'required|numeric|min:0.1',
            'order_pay' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|in:Cash,Transfer,QRIS',
        ]);

        DB::beginTransaction();
        try {
            // Membuat nomor nota / order_code secara otomatis
            // Format: LAUNDRY-YYYYMMDD-XXXX (contoh: LAUNDRY-20260628-0001)
            $datePrefix = date('Ymd');
            $lastOrder = TransOrder::whereDate('created_at', date('Y-m-d'))->latest()->first();

            if ($lastOrder) {
                // Ambil 4 digit terakhir dan tambahkan 1
                $lastNumber = (int) substr($lastOrder->order_code, -4);
                $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '0001';
            }
            $orderCode = 'LAUNDRY-'.$datePrefix.'-'.$newNumber;

            // Menyimpan ke tabel trans_order (Header)
            // Menentukan pembayaran berdasarkan pilihan
            $order_pay = null;
            $order_change = null;
            $order_status = 0; // 0 = baru

            $totalLayanan = 0;
            $detailsData = [];

            // Loop data array layanan dari form
            foreach ($request->service_id as $key => $serviceId) {
                $service = TypeOfService::findOrFail($serviceId);
                $qty = $request->qty[$key];
                $subtotal = round($service->price * $qty);

                $detailsData[] = [
                    'id_service' => $serviceId,
                    'qty' => $qty,
                    'subtotal' => $subtotal,
                    'notes' => $request->notes[$key] ?? null,
                ];

                $totalLayanan += $subtotal;
            }

            // Hitung Pajak
            $tax_percentage = (int) Setting::where('key', 'tax_percentage')->value('value') ?? 0;
            $tax_is_active = (int) Setting::where('key', 'tax_is_active')->value('value') ?? 0;

            if (! $tax_is_active) {
                $tax_percentage = 0;
            }

            $tax_amount = round(($totalLayanan * $tax_percentage) / 100);

            $finalTotalRaw = $totalLayanan + $tax_amount;
            $finalTotal = round($finalTotalRaw / 100) * 100; // Pembulatan ke 100 terdekat

            if ($request->order_pay !== null) {
                if ($request->order_pay < $finalTotal) {
                    DB::rollBack();

                    return back()->withInput()->withErrors(['order_pay' => 'Jumlah bayar tidak boleh kurang dari total tagihan (Rp '.number_format($finalTotal, 0, ',', '.').').']);
                }
                $order_pay = $request->order_pay;
                $order_change = $order_pay - $finalTotal;
            }

            $order = TransOrder::create([
                'id_customer' => $request->id_customer,
                'order_code' => $orderCode,
                'order_date' => $request->order_date,
                'order_end_date' => $request->order_end_date,
                'order_status' => $order_status,
                'order_pay' => $order_pay,
                'order_change' => $order_change,
                'payment_method' => $request->order_pay !== null ? $request->payment_method : null,
                'tax_percentage' => $tax_percentage,
                'tax_amount' => $tax_amount,
                'total' => $finalTotal,
            ]);

            // Menyimpan ke tabel trans_order_detail (Detail)
            foreach ($detailsData as $detail) {
                TransOrderDetail::create([
                    'id_order' => $order->id,
                    'id_service' => $detail['id_service'],
                    'qty' => $detail['qty'],
                    'subtotal' => $detail['subtotal'],
                    'notes' => $detail['notes'],
                ]);
            }

            DB::commit();
            Alert::toast('Pesanan berhasil dibuat.', 'success');

            return redirect()->to('transaction');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan pesanan: '.$e->getMessage());
            Alert::toast('Terjadi kesalahan saat menyimpan pesanan. Silakan coba lagi.', 'error');

            return back()->withInput();
        }
    }

    public function show(string $id)
    {
        try {
            $title = 'Detail Pesanan';
            $order = TransOrder::with(['customer', 'details.service'])->findOrFail($id);

            return view('transaction.show', compact('title', 'order'));
        } catch (\Throwable $e) {
            Log::error('Gagal melihat detail pesanan: '.$e->getMessage());
            Alert::toast('Gagal melihat detail pesanan.', 'error');

            return redirect()->to('transaction');
        }
    }

    /**
     * Print the specified resource receipt.
     */
    public function print(string $id)
    {
        try {
            $title = 'Cetak Nota Order';
            $order = TransOrder::with(['customer', 'details.service'])->findOrFail($id);

            return view('transaction.print', compact('title', 'order'));
        } catch (\Throwable $e) {
            Log::error('Gagal mencetak nota pesanan: '.$e->getMessage());
            Alert::toast('Gagal mencetak nota pesanan.', 'error');

            return redirect()->to('transaction');
        }
    }

    /**
     * Update the production status of an order.
     */
    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'order_status' => 'required|integer|between:0,4',
        ]);

        try {
            $order = TransOrder::findOrFail($id);
            $order->order_status = $request->order_status;
            $order->save();

            Alert::toast('Status pesanan berhasil diperbarui.', 'success');

            return redirect()->back();
        } catch (\Throwable $e) {
            Log::error('Gagal memperbarui status pesanan: '.$e->getMessage());
            Alert::toast('Gagal memperbarui status pesanan.', 'error');

            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Dalam konteks laundry, order yang sudah berjalan biasanya tidak diedit secara master-detail
        // Kecuali hanya untuk update status. (Untuk update status nantinya ada di fitur Pickup)
        // Jadi kita bisa nonaktifkan fungsi edit order atau membatasi perbaikannya.
        Alert::toast('Fitur edit dinonaktifkan untuk pesanan aktif.', 'warning');

        return redirect()->to('transaction');
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
            $order = TransOrder::find($id);
            if ($order) {
                $order->delete();
                Alert::toast('Pesanan berhasil dihapus.', 'success');
            }

            return redirect()->to('transaction');
        } catch (\Throwable $e) {
            Log::error('Gagal menghapus pesanan: '.$e->getMessage());
            Alert::toast('Gagal menghapus pesanan. Silakan coba lagi.', 'error');

            return redirect()->to('transaction');
        }
    }
}
