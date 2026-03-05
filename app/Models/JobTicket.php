<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTicket extends Model
{
    use HasFactory;
    
    // Pastikan color_id sudah ada di sini ya
    protected $fillable = ['ticket_code', 'tanggal', 'order_id', 'color_id', 'fabric_weight', 'machine_id', 'process_id', 'volume'];

    // ==========================================
    // RELASI KE MASTER DATA
    // ==========================================
    public function order() { return $this->belongsTo(Order::class); }
    public function machine() { return $this->belongsTo(Machine::class); }
    public function process() { return $this->belongsTo(Process::class); }
    
    // 👇 INI DIA OBAT PENAWAR ERROR-NYA 👇
    public function color() { return $this->belongsTo(Color::class); }

    // ==========================================
    // RELASI KE RINCIAN RESEP
    // ==========================================
    public function dyestuffs() { return $this->hasMany(JobTicketDyestuff::class); }
    public function chemicals() { return $this->hasMany(JobTicketChemical::class); }
}