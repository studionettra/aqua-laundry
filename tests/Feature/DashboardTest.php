<?php

use App\Models\Customer;
use App\Models\Level;
use App\Models\TransOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest cannot access dashboard page', function () {
    // Penjelasan: Mengakses halaman dashboard tanpa autentikasi harus mengarahkan kembali ke halaman login
    $response = $this->get('/dashboard');

    $response->assertRedirect('/');
});

test('logged in user can access dashboard page and see dynamic stats', function () {
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

    // Penjelasan: Membuat data transaksi order hari ini yang belum diambil
    TransOrder::create([
        'id_customer' => $customer->id,
        'order_code' => 'ORD-101',
        'order_date' => now()->format('Y-m-d'),
        'order_end_date' => now()->addDays(2)->format('Y-m-d'),
        'order_status' => 0, // Belum diambil
        'order_pay' => 50000,
        'order_change' => 0,
        'tax_percentage' => 10,
        'tax_amount' => 5000,
        'total' => 55000,
    ]);

    // Penjelasan: Mengakses halaman dashboard dengan login sebagai Administrator
    $response = $this->actingAs($user)->get('/dashboard');

    // Penjelasan: Memastikan status respons HTTP adalah 200 (OK)
    $response->assertStatus(200);

    // Penjelasan: Memastikan view menerima data ringkasan metrik dashboard yang dihitung dengan benar
    $response->assertViewHas('todayOrders', 1);
    $response->assertViewHas('todayRevenue', 55000);
    $response->assertViewHas('unclaimedOrders', 1);
    $response->assertViewHas('totalCustomers', 1);

    // Penjelasan: Memastikan elemen penting dirender ke halaman
    $response->assertSee('Order Hari Ini');
    $response->assertSee('Pendapatan Hari Ini');
    $response->assertSee('Belum Diambil');
    $response->assertSee('Total Pelanggan');
    $response->assertSee('Aksi Cepat');
    $response->assertSee('Antrean Cucian Terbaru');
    $response->assertSee('ORD-101');
});
