<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemartaianDetail extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = [
        'pemartaian_id', 
        'no_order', 
        'fabric_id', 
        'warna', 
        'no_batch', 
        'jml_gulung', 
        'total_meter', 
        'berat',
        'keterangan' // <--- Tambahkan ini
    ];
    // Relasi balik ke Induk
    public function pemartaian()
    {
        return $this->belongsTo(Pemartaian::class);
    }

    // Relasi ke Data Master Kain
    public function fabric()
    {
        return $this->belongsTo(Fabric::class);
    }
    public function deliveryDetails()
    {
        return $this->hasMany(DeliveryDetail::class);
    }
}