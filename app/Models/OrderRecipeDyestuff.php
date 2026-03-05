<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderRecipeDyestuff extends Model
{
    protected $fillable = ['order_recipe_id', 'dyestuff_id', 'concentration'];
    
    public function orderRecipe() { return $this->belongsTo(OrderRecipe::class); }
    public function dyestuff() { return $this->belongsTo(Dyestuff::class); }
}