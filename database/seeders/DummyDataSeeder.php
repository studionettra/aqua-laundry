<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Setting;
use App\Models\TransLaundryPickup;
use App\Models\TransOrder;
use App\Models\TransOrderDetail;
use App\Models\TypeOfService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Inisialisasi Pengaturan Pajak
        Setting::updateOrCreate(['key' => 'tax_percentage'], ['value' => '11']);
        Setting::updateOrCreate(['key' => 'tax_is_active'], ['value' => '1']);

        // 2. Data Pelanggan Dummy (Konsumen Nyata)
        $customersData = [
            [
                'customer_name' => 'Budi Santoso',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 12, Bandung',
            ],
            [
                'customer_name' => 'Siti Aminah',
                'phone' => '081298765432',
                'address' => 'Jl. Dago Atas No. 45, Bandung',
            ],
            [
                'customer_name' => 'Rian Hidayat',
                'phone' => '085612345678',
                'address' => 'Jl. Cihampelas No. 88, Bandung',
            ],
            [
                'customer_name' => 'Dewi Lestari',
                'phone' => '087812345678',
                'address' => 'Komp. Permata Hijau B3, Bandung',
            ],
            [
                'customer_name' => 'Ahmad Pratama',
                'phone' => '081345678901',
                'address' => 'Jl. Riau No. 25, Bandung',
            ],
            [
                'customer_name' => 'Maya Anggraini',
                'phone' => '082198765432',
                'address' => 'Jl. Buah Batu No. 102, Bandung',
            ],
        ];

        $customers = [];
        foreach ($customersData as $c) {
            $customers[] = Customer::firstOrCreate(['phone' => $c['phone']], $c);
        }

        // 3. Pastikan Layanan Tersedia
        $serviceCuciGosok = TypeOfService::where('service_name', 'Cuci dan Gosok')->first();
        $serviceCuci = TypeOfService::where('service_name', 'Hanya Cuci')->first();
        $serviceGosok = TypeOfService::where('service_name', 'Hanya Gosok')->first();
        $serviceBesar = TypeOfService::where('service_name', 'Laundry Besar')->first();

        // 4. Riwayat Transaksi 6 Hari Terakhir (Agar Grafik Tren ApexCharts Terbentuk Indah)
        $orderCounter = 1;
        for ($daysAgo = 6; $daysAgo >= 1; $daysAgo--) {
            $date = Carbon::today()->subDays($daysAgo);
            $ordersCountOnDay = rand(2, 4);

            for ($j = 0; $j < $ordersCountOnDay; $j++) {
                $cust = $customers[array_rand($customers)];
                $code = 'LAUNDRY-'.$date->format('Ymd').'-'.str_pad($orderCounter++, 4, '0', STR_PAD_LEFT);

                // Cek jika order sudah ada agar tidak duplikasi
                if (TransOrder::where('order_code', $code)->exists()) {
                    continue;
                }

                $qty = rand(3, 7);
                $service = ($j % 2 == 0) ? $serviceCuciGosok : $serviceBesar;
                $subtotal = $qty * ($service ? $service->price : 5000);
                $taxPercentage = 11;
                $taxAmount = (int) round(($subtotal * $taxPercentage) / 100);
                $total = $subtotal + $taxAmount;

                $order = TransOrder::create([
                    'id_customer' => $cust->id,
                    'order_code' => $code,
                    'order_date' => $date->format('Y-m-d'),
                    'order_end_date' => $date->copy()->addDays(2)->format('Y-m-d'),
                    'order_status' => 1, // Sudah Diambil
                    'order_pay' => $total + 5000,
                    'order_change' => 5000,
                    'payment_method' => ($j % 2 == 0) ? 'Cash / Tunai' : 'QRIS / Transfer',
                    'tax_percentage' => $taxPercentage,
                    'tax_amount' => $taxAmount,
                    'total' => $total,
                    'created_at' => $date->copy()->setTime(rand(8, 16), rand(10, 50)),
                    'updated_at' => $date->copy()->addDays(2)->setTime(17, 0),
                ]);

                TransOrderDetail::create([
                    'id_order' => $order->id,
                    'id_service' => $service ? $service->id : 1,
                    'qty' => $qty,
                    'subtotal' => $subtotal,
                    'notes' => 'Pewangi lavender, ekstra bersih',
                ]);

                TransLaundryPickup::create([
                    'id_order' => $order->id,
                    'id_customer' => $cust->id,
                    'pickup_date' => $date->copy()->addDays(2)->format('Y-m-d'),
                    'notes' => 'Diambil langsung oleh pelanggan',
                ]);
            }
        }

        // 5. Transaksi Hari Ini (Order Masuk, Dalam Proses, & Tagihan Belum Dibayar)
        $today = Carbon::today();

        // Order Hari Ini 1: Sudah Selesai & Diambil (Lunas)
        $code1 = 'LAUNDRY-'.$today->format('Ymd').'-0091';
        if (! TransOrder::where('order_code', $code1)->exists()) {
            $subtotal1 = 5 * ($serviceCuciGosok ? $serviceCuciGosok->price : 5000);
            $tax1 = (int) round(($subtotal1 * 11) / 100);
            $total1 = $subtotal1 + $tax1;

            $order1 = TransOrder::create([
                'id_customer' => $customers[0]->id,
                'order_code' => $code1,
                'order_date' => $today->format('Y-m-d'),
                'order_end_date' => $today->format('Y-m-d'),
                'order_status' => 1, // Sudah Diambil
                'order_pay' => 35000,
                'order_change' => 35000 - $total1,
                'payment_method' => 'Cash / Tunai',
                'tax_percentage' => 11,
                'tax_amount' => $tax1,
                'total' => $total1,
                'created_at' => $today->copy()->setTime(8, 30),
            ]);

            TransOrderDetail::create([
                'id_order' => $order1->id,
                'id_service' => $serviceCuciGosok ? $serviceCuciGosok->id : 1,
                'qty' => 5,
                'subtotal' => $subtotal1,
                'notes' => 'Baju kantor, setrika licin',
            ]);

            TransLaundryPickup::create([
                'id_order' => $order1->id,
                'id_customer' => $customers[0]->id,
                'pickup_date' => $today->format('Y-m-d'),
                'notes' => 'Diambil siang hari jam 13.00',
            ]);
        }

        // Order Hari Ini 2: Dalam Proses / Belum Diambil (Sudah Lunas)
        $code2 = 'LAUNDRY-'.$today->format('Ymd').'-0092';
        if (! TransOrder::where('order_code', $code2)->exists()) {
            $subtotal2 = 7 * ($serviceBesar ? $serviceBesar->price : 7000);
            $tax2 = (int) round(($subtotal2 * 11) / 100);
            $total2 = $subtotal2 + $tax2;

            $order2 = TransOrder::create([
                'id_customer' => $customers[1]->id,
                'order_code' => $code2,
                'order_date' => $today->format('Y-m-d'),
                'order_end_date' => $today->copy()->addDays(1)->format('Y-m-d'),
                'order_status' => 0, // Belum Diambil / Proses
                'order_pay' => 60000,
                'order_change' => 60000 - $total2,
                'payment_method' => 'QRIS / Transfer',
                'tax_percentage' => 11,
                'tax_amount' => $tax2,
                'total' => $total2,
                'created_at' => $today->copy()->setTime(10, 15),
            ]);

            TransOrderDetail::create([
                'id_order' => $order2->id,
                'id_service' => $serviceBesar ? $serviceBesar->id : 4,
                'qty' => 7,
                'subtotal' => $subtotal2,
                'notes' => 'Sprei king size + bed cover, jangan pakai pemutih',
            ]);
        }

        // Order Hari Ini 3: Dalam Proses / Belum Diambil (Belum Dibayar / Unpaid)
        $code3 = 'LAUNDRY-'.$today->format('Ymd').'-0093';
        if (! TransOrder::where('order_code', $code3)->exists()) {
            $subtotal3 = 8 * ($serviceCuciGosok ? $serviceCuciGosok->price : 5000);
            $tax3 = (int) round(($subtotal3 * 11) / 100);
            $total3 = $subtotal3 + $tax3;

            $order3 = TransOrder::create([
                'id_customer' => $customers[2]->id,
                'order_code' => $code3,
                'order_date' => $today->format('Y-m-d'),
                'order_end_date' => $today->copy()->addDays(2)->format('Y-m-d'),
                'order_status' => 0, // Belum Diambil / Antrean Aktif
                'order_pay' => null, // Tagihan Unpaid
                'order_change' => null,
                'payment_method' => null,
                'tax_percentage' => 11,
                'tax_amount' => $tax3,
                'total' => $total3,
                'created_at' => $today->copy()->setTime(11, 45),
            ]);

            TransOrderDetail::create([
                'id_order' => $order3->id,
                'id_service' => $serviceCuciGosok ? $serviceCuciGosok->id : 1,
                'qty' => 8,
                'subtotal' => $subtotal3,
                'notes' => 'Bayar saat pengambilan cucian',
            ]);
        }

        // 6. Data Pengeluaran Operasional Toko (Untuk Menu Laporan Pimpinan)
        $expensesData = [
            [
                'expense_date' => Carbon::now()->startOfMonth()->addDays(1)->format('Y-m-d'),
                'amount' => 185000,
                'category' => 'Bahan Kimia',
                'description' => 'Deterjen Cair Laundry Konsentrat 20 Liter',
            ],
            [
                'expense_date' => Carbon::now()->startOfMonth()->addDays(3)->format('Y-m-d'),
                'amount' => 120000,
                'category' => 'Bahan Kimia',
                'description' => 'Parfum & Pelicin Pakaian Aroma Sakura 10 Liter',
            ],
            [
                'expense_date' => Carbon::now()->startOfMonth()->addDays(5)->format('Y-m-d'),
                'amount' => 75000,
                'category' => 'Perlengkapan',
                'description' => 'Plastik Klip Packing & Hanger Baju 2 Gross',
            ],
            [
                'expense_date' => Carbon::now()->startOfMonth()->addDays(7)->format('Y-m-d'),
                'amount' => 88000,
                'category' => 'Operasional',
                'description' => 'Isi Ulang Gas LPG Dryer Pengering 3kg x 4 tabung',
            ],
            [
                'expense_date' => Carbon::now()->startOfMonth()->addDays(10)->format('Y-m-d'),
                'amount' => 250000,
                'category' => 'Utilitas',
                'description' => 'Token Listrik Operasional Toko & Mesin Cuci',
            ],
        ];

        foreach ($expensesData as $exp) {
            Expense::firstOrCreate(
                [
                    'expense_date' => $exp['expense_date'],
                    'description' => $exp['description'],
                ],
                $exp
            );
        }
    }
}

