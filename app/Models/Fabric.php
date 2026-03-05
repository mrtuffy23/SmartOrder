<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fabric extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = [
        'corak', 
        'code_kain', 
        'quality', 
        'kode_buyer', 
        'brand', 
        'construction', 
        'density',
        'is_active' // 🔥 Tambahan baru
    ];
    
    public function receiptDetails()
    {
        return $this->hasMany(ReceiptDetail::class);
    }

    public function pemartaianDetails()
    {
        return $this->hasMany(PemartaianDetail::class);
    }
}