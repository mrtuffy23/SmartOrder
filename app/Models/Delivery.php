<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = ['tanggal', 'buyer_id'];

    public function details()
    {
        return $this->hasMany(DeliveryDetail::class);
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id');
    }
}