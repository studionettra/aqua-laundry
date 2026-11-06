<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransOrderDetail extends Model
{
    protected $table = 'trans_order_detail';

    protected $fillable = [
        'id_order',
        'id_service',
        'qty',
        'subtotal',
        'notes',
    ];

    // Penjelasan: Relasi balik ke tabel order induk
    public function order()
    {
        return $this->belongsTo(TransOrder::class, 'id_order');
    }

    public function service()
    {
        return $this->belongsTo(TypeOfService::class, 'id_service')->withTrashed();
    }
}
