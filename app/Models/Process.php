<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'is_active'];

    // 🔥 Relasi ke tabel Chemical melalui pivot process_chemicals
    public function chemicals()
    {
        return $this->belongsToMany(Chemical::class, 'process_chemicals')
                    ->withPivot('concentration')
                    ->withTimestamps();
    }
}