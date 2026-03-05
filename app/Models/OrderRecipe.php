<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRecipe extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'color_id'];

    // Relasi ke tabel Master
    public function order() { return $this->belongsTo(Order::class); }
    public function color() { return $this->belongsTo(Color::class); }

    // Relasi ke rincian resep (Obat & Warna)
    public function dyestuffs() { return $this->hasMany(OrderRecipeDyestuff::class); }
    public function chemicals() { return $this->hasMany(OrderRecipeChemical::class); }
}