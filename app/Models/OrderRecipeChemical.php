<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderRecipeChemical extends Model
{
    protected $fillable = ['order_recipe_id', 'chemical_id', 'concentration'];
    
    public function orderRecipe() { return $this->belongsTo(OrderRecipe::class); }
    public function chemical() { return $this->belongsTo(Chemical::class); }
}