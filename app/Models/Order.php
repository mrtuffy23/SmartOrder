<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'po_number', 
        'mf_number', 
        'order_date', 
        'buyer_id', 
        'customer_level', // 🔥 Tambahan baru
        'fabric_id',
        'target_produksi',
        'target_packing'
    ];

    // Relasi ke Buyer
    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    // Relasi ke Kain
    public function fabric()
    {
        return $this->belongsTo(Fabric::class);
    }

    // Relasi ke Rincian (Anaknya)
    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
}