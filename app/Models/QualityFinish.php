<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityFinish extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Relasi balik ke rincian pemartaian (WIP)
    public function pemartaianDetail()
    {
        return $this->belongsTo(PemartaianDetail::class);
    }
    // Relasi ke Pengiriman (Satu kain jadi bisa dikirim sebagian atau seluruhnya)
    public function deliveryDetails()
    {
        return $this->hasMany(DeliveryDetail::class);
    }
}