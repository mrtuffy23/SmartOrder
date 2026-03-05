<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JobTicketChemical extends Model
{
    protected $fillable = ['job_ticket_id', 'chemical_id', 'concentration', 'gram'];
    public function chemical() { return $this->belongsTo(Chemical::class); }
}