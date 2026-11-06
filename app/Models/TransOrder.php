<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransOrder extends Model
{
    use SoftDeletes;

    protected $table = 'trans_order';

    protected $fillable = [
        'id_customer',
        'order_code',
        'order_date',
        'order_end_date',
        'order_status',
        'order_pay',
        'order_change',
        'payment_method',
        'tax_percentage',
        'tax_amount',
        'total',
    ];

    // Penjelasan: Relasi ke tabel customer (Satu pesanan dimiliki oleh satu pelanggan)
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer')->withTrashed();
    }

    public function details()
    {
        return $this->hasMany(TransOrderDetail::class, 'id_order');
    }

    // Penjelasan: Relasi one-to-one ke tabel pengambilan (Satu pesanan hanya diambil satu kali)
    public function pickup()
    {
        return $this->hasOne(TransLaundryPickup::class, 'id_order');
    }
}
