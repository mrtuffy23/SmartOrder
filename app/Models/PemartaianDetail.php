<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemartaianDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

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
    // Relasi ke Data Finish (1 rincian kain hanya punya 1 hasil finish)
    public function qualityFinish()
    {
        return $this->hasOne(QualityFinish::class);
    }
}