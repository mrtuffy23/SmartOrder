<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $guarded = [];

    // Relasi balik ke Induknya
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi ke Warna
    public function color()
    {
        return $this->belongsTo(Color::class);
    }
}