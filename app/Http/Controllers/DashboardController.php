<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\TransOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama beserta data statistik operasional
     * dan transaksi cucian yang belum diambil.
     */
    public function index(Request $request): View
    {
        try {
            // 1. Order Baru Masuk Hari Ini
            $todayOrders = TransOrder::whereDate('order_date', today())->count();

            // 2. Pendapatan Hari Ini
            $todayRevenue = (int) TransOrder::whereDate('order_date', today())->sum('total');

            // 3. Cucian Belum Diambil (order_status = 0 atau belum selesai)
            $unclaimedOrders = TransOrder::where('order_status', 0)->count();

            // 4. Total Pelanggan Aktif
            $totalCustomers = Customer::count();
            
            // 5. Tagihan Belum Dibayar
            $unpaidBills = (int) TransOrder::whereNull('order_pay')->sum('total');

            // 6. Data Bulanan untuk Pimpinan
            $monthlyRevenue = (int) TransOrder::whereMonth('order_date', now()->month)
                                        ->whereYear('order_date', now()->year)
                                        ->sum('total');
            $monthlyOrders = TransOrder::whereMonth('order_date', now()->month)
                                        ->whereYear('order_date', now()->year)
                                        ->count();

            // 7. Grafik Tren Pendapatan 7 Hari Terakhir
            $chartDates = [];
            $chartRevenues = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $chartDates[] = $date->translatedFormat('d M');
                $chartRevenues[] = (int) TransOrder::whereDate('order_date', $date->format('Y-m-d'))->sum('total');
            }

            // 8. Antrean 5 Cucian Aktif Terbaru (Belum Diambil)
            $recentActiveOrders = TransOrder::with('customer')
                ->where('order_status', 0)
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();

            return view('dashboard.index', compact(
                'todayOrders',
                'todayRevenue',
                'unclaimedOrders',
                'totalCustomers',
                'unpaidBills',
                'monthlyRevenue',
                'monthlyOrders',
                'chartDates',
                'chartRevenues',
                'recentActiveOrders'
            ));
        } catch (\Throwable $e) {
            // Log error jika terjadi kesalahan dan kembalikan view dengan data kosong / default
            Log::error('Gagal memuat data dashboard: '.$e->getMessage());
            Alert::toast('Gagal memuat data dashboard.', 'error');

            $todayOrders = 0;
            $todayRevenue = 0;
            $unclaimedOrders = 0;
            $totalCustomers = 0;
            $unpaidBills = 0;
            $monthlyRevenue = 0;
            $monthlyOrders = 0;
            $chartDates = [];
            $chartRevenues = [];
            $recentActiveOrders = collect();

            return view('dashboard.index', compact(
                'todayOrders',
                'todayRevenue',
                'unclaimedOrders',
                'totalCustomers',
                'unpaidBills',
                'monthlyRevenue',
                'monthlyOrders',
                'chartDates',
                'chartRevenues',
                'recentActiveOrders'
            ));
        }
    }
}
