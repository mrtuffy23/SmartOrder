<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function qualityFinish()
    {
        return $this->belongsTo(QualityFinish::class);
    }
}