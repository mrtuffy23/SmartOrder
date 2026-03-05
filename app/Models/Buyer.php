<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    use HasFactory;
    protected $guarded = []; 
    protected $fillable = ['name', 'kode_buyer'];

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }
}