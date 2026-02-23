<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemartaian extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Relasi ke detail
    public function details()
    {
        return $this->hasMany(PemartaianDetail::class);
    }
}