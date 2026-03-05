<?php

namespace App\Http\Controllers;

use App\Models\JobTicket;
use App\Models\JobTicketDyestuff;
use App\Models\JobTicketChemical;
use App\Models\Order;
use App\Models\Machine;
use App\Models\Process;
use App\Models\Dyestuff;
use App\Models\Chemical;
use Illuminate\Http\Request;

class JobTicketController extends Controller
{
    // 1. TAMPILKAN DAFTAR RIWAYAT JOB TICKET
    public function index()
    {
        $tickets = JobTicket::with(['order', 'color', 'machine', 'process'])->orderBy('id', 'desc')->paginate(20);
        return view('job_tickets.index', compact('tickets'));
    }

    // 2. FUNGSI CETAK JOB TICKET (PRINT)
    public function print($id)
    {
        // Panggil data tiket secara lengkap sampai ke akar relasinya (Zat Warna & Kimia)
        $job = JobTicket::with([
            'order.fabric', 
            'color', 
            'machine', 
            'process', 
            'dyestuffs.dyestuff', 
            'chemicals.chemical'
        ])->findOrFail($id);

        return view('job_tickets.print', compact('job'));
    }
    // Tampilkan Form Buat Tiket
    public function create()
    {
        $orders = Order::orderBy('id', 'desc')->get();
        $machines = Machine::where('is_active', 1)->orderBy('name', 'asc')->get();
        // Bawa beserta relasi resep obat bawaannya (SOP)
        $processes = Process::with('chemicals')->where('is_active', 1)->orderBy('name', 'asc')->get();
        $dyestuffs = Dyestuff::where('is_active', 1)->orderBy('name', 'asc')->get();
        $chemicals = Chemical::where('is_active', 1)->orderBy('name', 'asc')->get();

        return view('job_tickets.create', compact('orders', 'machines', 'processes', 'dyestuffs', 'chemicals'));
    }

    // Simpan Data ke Database
    // Simpan Data ke Database
    public function store(Request $request)
    {
        // 👇 1. TAMBAHKAN VALIDASI COLOR_ID 👇
        $request->validate([
            'order_id' => 'required',
            'color_id' => 'required', 
            'fabric_weight' => 'required|numeric',
            'machine_id' => 'required',
            'process_id' => 'required',
        ]);

        // Buat Kode Tiket Otomatis (Contoh: 260326) -> (TglBlnThn + Angka Random)
        $ticket_code = date('dmy') . rand(10, 99);

        // 1. Simpan Data Induk
        $job = JobTicket::create([
            'ticket_code' => $ticket_code,
            'tanggal' => $request->tanggal,
            'order_id' => $request->order_id,
            'color_id' => $request->color_id, // 👈 2. INI BARIS YANG TADI HILANG!
            'fabric_weight' => $request->fabric_weight,
            'machine_id' => $request->machine_id,
            'process_id' => $request->process_id,
            'volume' => $request->volume,
        ]);

        // 2. Simpan Rincian Zat Warna (Dyestuffs)
        if ($request->dyestuff_id) {
            foreach ($request->dyestuff_id as $key => $dye_id) {
                if ($dye_id) {
                    JobTicketDyestuff::create([
                        'job_ticket_id' => $job->id,
                        'dyestuff_id' => $dye_id,
                        'concentration' => $request->dye_concentration[$key] ?? 0,
                        'gram' => $request->dye_gram[$key] ?? 0,
                    ]);
                }
            }
        }

        // 3. Simpan Rincian Bahan Kimia (Chemicals)
        if ($request->chemical_id) {
            foreach ($request->chemical_id as $key => $chem_id) {
                if ($chem_id) {
                    JobTicketChemical::create([
                        'job_ticket_id' => $job->id,
                        'chemical_id' => $chem_id,
                        'concentration' => $request->chem_concentration[$key] ?? 0,
                        'gram' => $request->chem_gram[$key] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->route('job-tickets.index')->with('success', 'Job Ticket berhasil disimpan!');
    }
    // Fungsi baru untuk menarik data Warna dan Berat Kain secara otomatis (AJAX)
    // Fungsi untuk menarik daftar warna (berupa Array) beserta berat kainnya
    public function getOrderDetails($id)
    {
        $order = Order::with('details.color')->findOrFail($id);

        $colors = [];
        foreach ($order->details as $detail) {
            // Pastikan relasi warna ada, agar tidak error
            if ($detail->color) {
                $colors[] = [
                    'id' => $detail->color->id,
                    'name' => $detail->color->name,
                    'weight' => $detail->jml_grey // Tarik berat kain spesifik untuk warna ini
                ];
            }
        }

        // Kembalikan dalam format JSON Array agar bisa di-looping oleh Javascript (.forEach)
        return response()->json(['colors' => $colors]);
    }
    // Fungsi untuk menyedot Buku Resep Original (Zat Warna & Kimia)
    public function getRecipe($order_id, $color_id)
    {
        // Cari resep berdasarkan Order & Warna yang dipilih
        $recipe = \App\Models\OrderRecipe::with([
            'dyestuffs.dyestuff', 
            'chemicals.chemical'
        ])->where('order_id', $order_id)
          ->where('color_id', $color_id)
          ->first();

        if ($recipe) {
            return response()->json([
                'status' => 'success',
                'dyestuffs' => $recipe->dyestuffs,
                'chemicals' => $recipe->chemicals
            ]);
        }

        // Jika resep belum pernah dibuat oleh Laborat
        return response()->json(['status' => 'empty']);
    }
    // Fungsi untuk menghapus Job Ticket
    public function destroy($id)
    {
        $job = \App\Models\JobTicket::findOrFail($id);
        
        // Hapus data (karena di database kita sudah pakai onDelete('cascade'), 
        // maka rincian warna dan obatnya otomatis ikut terhapus sampai bersih!)
        $job->delete();

        return back()->with('success', 'Data Job Ticket berhasil dihapus!');
    }
}