<?php

use App\Models\Customer;
use App\Models\Level;
use App\Models\TransOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest cannot access report page', function () {
    // Penjelasan: Menghubungi halaman laporan tanpa autentikasi harus mengarahkan kembali ke halaman login
    $response = $this->get('/report');

    $response->assertRedirect('/');
});

test('administrator can access report page and see metrics cards', function () {
    // Penjelasan: Membuat data level Administrator
    $level = Level::create(['level_name' => 'Administrator']);

    // Penjelasan: Membuat user Administrator baru
    $user = User::create([
        'id_level' => $level->id,
        'name' => 'Admin User',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
    ]);

    // Penjelasan: Membuat data Customer baru
    $customer = Customer::create([
        'customer_name' => 'Customer Test',
        'phone' => '0812345678',
        'address' => 'Alamat Test',
    ]);

    // Penjelasan: Membuat data transaksi order baru
    TransOrder::create([
        'id_customer' => $customer->id,
        'order_code' => 'ORD-001',
        'order_date' => now()->format('Y-m-d'),
        'order_end_date' => now()->addDays(2)->format('Y-m-d'),
        'order_status' => 0,
        'order_pay' => 50000,
        'order_change' => 0,
        'tax_percentage' => 10,
        'tax_amount' => 5000,
        'total' => 50000,
    ]);

    // Penjelasan: Mengakses halaman laporan dengan login sebagai Administrator
    $response = $this->actingAs($user)->get('/report');

    // Penjelasan: Memastikan status respons HTTP adalah 200 (OK)
    $response->assertStatus(200);

    // Penjelasan: Memastikan view menerima data ringkasan metrik yang dihitung dengan benar
    $response->assertViewHas('totalOrders', 1);
    $response->assertViewHas('totalRevenue', 50000);
    $response->assertViewHas('totalTax', 5000);
    $response->assertViewHas('netIncome', 45000);
    $response->assertViewHas('uniqueCustomersCount', 1);
    $response->assertViewHas('returningCustomersCount', 0);
    $response->assertViewHas('pickedUpCount', 0);
    $response->assertViewHas('newCount', 1);

    // Penjelasan: Memastikan teks-teks indikator pada kartu ringkasan dirender ke halaman
    $response->assertSee('Total Order');
    $response->assertSee('Avg Transaksi');
    $response->assertSee('Pelanggan Unik');
    $response->assertSee('Repeat Cust');
    $response->assertSee('Status Pengambilan');
});
