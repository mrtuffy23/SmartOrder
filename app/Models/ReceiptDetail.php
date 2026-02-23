<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Relasi balik ke Induk Penerimaan
    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }

    // RELASI KE MASTER KAIN 👈
    public function fabric()
    {
        return $this->belongsTo(Fabric::class);
    }
}