<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JobTicketDyestuff extends Model
{
    protected $fillable = ['job_ticket_id', 'dyestuff_id', 'concentration', 'gram'];
    public function dyestuff() { return $this->belongsTo(Dyestuff::class); }
}