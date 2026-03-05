<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retur extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_retur', 
        'tanggal', 
        'fabric_id', 
        'total_meter', 
        'keterangan'
    ];

    // Relasi ke Data Kain (Corak)
    public function fabric()
    {
        return $this->belongsTo(Fabric::class);
    }
}