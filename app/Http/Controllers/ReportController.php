<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\TransOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class ReportController extends Controller
{
    /**
     * Display the report filter and results.
     */
    public function index(Request $request)
    {
        $title = 'Laporan Pendapatan Laundry';

        try {
            // Mengatur default filter tanggal (mulai awal bulan ini sampai hari ini)
            $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = $request->end_date ?? Carbon::now()->format('Y-m-d');

            $paymentMethod = $request->payment_method;

            // Mengambil data order berdasarkan rentang tanggal beserta relasi customer dan detail layanan
            $query = TransOrder::with(['customer', 'details.service'])
                ->whereBetween('order_date', [$startDate, $endDate]);

            if ($paymentMethod) {
                $query->where('payment_method', $paymentMethod);
            }

            $orders = $query->orderBy('id', 'desc')->get();

            // Menghitung total keseluruhan pendapatan, pajak, dan pendapatan bersih (Hanya transaksi Lunas)
            $paidOrders = $orders->whereNotNull('order_pay');
            $totalRevenue = $paidOrders->sum('total');
            $totalTax = $paidOrders->sum(function ($order) {
                return $order->details->filter(function ($detail) {
                    return ($detail->service->service_name ?? '') === 'Pajak PPN';
                })->sum('subtotal');
            });
            $netIncome = $totalRevenue - $totalTax;

            // Menghitung total pengeluaran
            $totalExpense = Expense::whereBetween('expense_date', [$startDate, $endDate])->sum('amount');
            $labaBersih = $netIncome - $totalExpense;

            // Menghitung pendapatan berdasarkan metode pembayaran
            $totalCash = $paidOrders->where('payment_method', 'Cash')->sum('total');
            $totalTransfer = $paidOrders->where('payment_method', 'Transfer')->sum('total');
            $totalQRIS = $paidOrders->where('payment_method', 'QRIS')->sum('total');

            // Menghitung tagihan belum lunas (Bayar di akhir dan belum diambil)
            $unpaidRevenue = $orders->whereNull('order_pay')->sum('total');

            // Menghitung total transaksi (orderan) dalam periode terpilih
            $totalOrders = $orders->count();

            // Menghitung rata-rata nilai transaksi (total pendapatan dibagi jumlah order)
            $averageTransactionValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

            // Menghitung jumlah pelanggan unik yang melakukan order
            $uniqueCustomersCount = $orders->pluck('id_customer')->unique()->filter()->count();

            // Menghitung pelanggan yang datang kembali (repeat customers)
            // Kategori: Pelanggan yang order di periode ini AND (punya order sebelum periode ini ATAU order > 1 kali di periode ini)
            $customerIds = $orders->pluck('id_customer')->unique()->filter();
            $returningCustomersCount = 0;
            if ($customerIds->isNotEmpty()) {
                // Mencari ID pelanggan yang memiliki riwayat transaksi sebelum tanggal mulai pencarian
                $orderedBefore = TransOrder::whereIn('id_customer', $customerIds)
                    ->where('order_date', '<', $startDate)
                    ->pluck('id_customer')
                    ->unique();

                // Mencari pelanggan yang melakukan pemesanan lebih dari sekali di periode pencarian saat ini
                $customerOrderCounts = $orders->groupBy('id_customer')->map->count();
                $orderedMultipleInPeriod = $customerOrderCounts->filter(fn ($count) => $count > 1)->keys();

                // Menggabungkan kedua kategori pelanggan di atas dan menghitung total uniknya
                $returningCustomersCount = $orderedBefore->merge($orderedMultipleInPeriod)->unique()->count();
            }

            // Menghitung status pengambilan cucian (Status 4: Sudah Diambil, Status != 4: Proses)
            $pickedUpCount = $orders->where('order_status', 4)->count();
            $newCount = $orders->where('order_status', '!=', 4)->count();

            // Mengembalikan ke view dengan menyertakan semua variabel perhitungan
            return view('report.index', compact(
                'title', 'orders', 'startDate', 'endDate', 'paymentMethod',
                'totalRevenue', 'totalTax', 'netIncome', 'totalExpense', 'labaBersih', 'unpaidRevenue',
                'totalCash', 'totalTransfer', 'totalQRIS',
                'totalOrders', 'averageTransactionValue',
                'uniqueCustomersCount', 'returningCustomersCount',
                'pickedUpCount', 'newCount'
            ));
        } catch (\Throwable $e) {
            // Tangkap error jika query/proses gagal dan kembalikan dengan pesan error flash
            Log::error('Gagal memuat data laporan: '.$e->getMessage());
            Alert::toast('Gagal memuat data laporan.', 'error');

            return back();
        }
    }

    /**
     * Display a clean print view for the report.
     */
    public function print(Request $request)
    {
        $title = 'Cetak Laporan Pendapatan';

        try {
            // Penjelasan: Mengatur default filter tanggal (mulai awal bulan ini sampai hari ini)
            $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = $request->end_date ?? Carbon::now()->format('Y-m-d');

            $paymentMethod = $request->payment_method;

            // Penjelasan: Mengambil data order berdasarkan rentang tanggal beserta relasi customer dan detail
            $query = TransOrder::with(['customer', 'details.service'])
                ->whereBetween('order_date', [$startDate, $endDate]);

            if ($paymentMethod) {
                $query->where('payment_method', $paymentMethod);
            }

            $orders = $query->orderBy('id', 'desc')->get();

            // Penjelasan: Menghitung total keseluruhan pendapatan, pajak, dan pendapatan bersih (Hanya transaksi Lunas)
            $paidOrders = $orders->whereNotNull('order_pay');
            $totalRevenue = $paidOrders->sum('total');
            $totalTax = $paidOrders->sum(function ($order) {
                return $order->details->filter(function ($detail) {
                    return ($detail->service->service_name ?? '') === 'Pajak PPN';
                })->sum('subtotal');
            });
            $netIncome = $totalRevenue - $totalTax;

            $totalExpense = Expense::whereBetween('expense_date', [$startDate, $endDate])->sum('amount');
            $labaBersih = $netIncome - $totalExpense;

            // Penjelasan: Menghitung tagihan belum lunas
            $unpaidRevenue = $orders->whereNull('order_pay')->sum('total');

            // Penjelasan: Mengembalikan ke view cetak laporan
            return view('report.print', compact('title', 'orders', 'startDate', 'endDate', 'paymentMethod', 'totalRevenue', 'totalTax', 'netIncome', 'totalExpense', 'labaBersih', 'unpaidRevenue'));
        } catch (\Throwable $e) {
            // Penjelasan: Tangkap error jika query/proses gagal dan kembalikan dengan pesan error flash
            Log::error('Gagal mencetak laporan: '.$e->getMessage());
            Alert::toast('Gagal mencetak laporan.', 'error');

            return back();
        }
    }
}
