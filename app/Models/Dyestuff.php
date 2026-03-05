<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dyestuff extends Model
{
    use HasFactory;
    protected $fillable = ['active_code', 'name', 'type', 'is_active'];
}