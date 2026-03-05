<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryDetail extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = [
        'delivery_id', 
        'pemartaian_detail_id', 
        'buyer_id',   // <-- Tambah ini
        'no_order', 
        'color_id',   // <-- Tambah ini
        'no_roda', 
        'keterangan'
    ];

    public function buyer() {
        return $this->belongsTo(Buyer::class);
    }

    public function color() {
        return $this->belongsTo(Color::class);
    }
    
    public function order() {
        return $this->belongsTo(Order::class, 'mf_number');
    }

    public function delivery() {
        return $this->belongsTo(Delivery::class);
    }

    public function pemartaianDetail() {
        return $this->belongsTo(PemartaianDetail::class, 'pemartaian_detail_id');
    }
}