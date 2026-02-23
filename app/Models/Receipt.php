<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Relasi: 1 Penerimaan punya banyak Detail
    public function details()
    {
        return $this->hasMany(ReceiptDetail::class);
    }
}