<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Process;
use App\Models\Chemical;
use Carbon\Carbon;

class ProcessSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        // 1. KOSONGKAN RELASI LAMA DENGAN AMAN
        // Kita gunakan detach() agar Laravel yang menghapus otomatis tabel perantaranya (pivot)
        $oldProcesses = Process::all();
        foreach($oldProcesses as $old) {
            $old->chemicals()->detach();
        }

        // 2. KOSONGKAN MASTER PROSES
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('processes')->truncate(); 
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 3. DATA PROSES & SOP DARI EXCEL
        $dataProses = [
            ['name' => 'CELUP', 'sops' => []],
            ['name' => 'CELUP REAKTIF', 'sops' => []],
            ['name' => 'CELUP - RC 2', 'sops' => ['HYDRO' => 0.5, 'CAUSTIC' => 1]],
            ['name' => 'CELUP - SOAPING', 'sops' => ['ESKACID' => 0.5]],
            ['name' => 'CELUP - RC KNITTING', 'sops' => ['DEMULGEN RAAC' => 1, 'ACID' => 0.5]],
            ['name' => 'CUCI RESIN - CELUP - RC', 'sops' => ['ACID' => 1, 'HYDRO' => 2, 'CAUSTIC' => 2, 'SUNMORL' => 0.5]],
            ['name' => 'BLEACHING', 'sops' => ['H2O2' => 3, 'CAUSTIC' => 3, 'STARBLITZ EA' => 1.5, 'ESKAPOL SN-L' => 0.25]],
            ['name' => 'BUANG KANJI', 'sops' => ['CAUSTIC' => 2, 'ESKAPOL SN-D' => 1, 'ESKAPOL SN-LF' => 0.25]],
            ['name' => 'STRIPING', 'sops' => ['HYDRO' => 3, 'CAUSTIC' => 3, 'ESKAPLUS DALM' => 1]],
            ['name' => 'LEV DEWA', 'sops' => ['OXALIC ACID' => 0.5, 'ESKAPLUS DAI' => 1, 'DEMULGEN OR' => 0.5, 'SODA ASH' => 0.5]],
            ['name' => 'CUCI OXALIC', 'sops' => ['OXALIC ACID' => 1, 'ESKAPOL SN-L' => 0.5]],
            ['name' => 'CUCI ASAM', 'sops' => ['ESKACID' => 1]],
            ['name' => 'CUCI CAUSTIC', 'sops' => ['NaOH' => 5]],
            ['name' => 'CUCI DEWA', 'sops' => ['OXALIC ACID' => 0.5, 'ESKAPOL SN-L' => 0.25, 'DEMULGEN OR' => 1, 'SODA ASH' => 1]],
            ['name' => 'CPB', 'sops' => ['CAUSTIC' => 7, 'SODA ASH' => 20]],
        ];

        // 4. MASUKKAN DATA & HUBUNGKAN RELASINYA
        foreach ($dataProses as $item) {
            
            // A. Buat Induk Prosesnya
            $process = Process::create([
                'name' => $item['name'],
                'is_active' => 1
            ]);

            // B. Masukkan dan Hubungkan Resep SOP-nya
            foreach ($item['sops'] as $chemName => $concentration) {
                
                // Filter penyesuaian nama dari Excel agar sinkron dengan database
                if ($chemName == 'NaOH') {
                    $chemName = 'CAUSTIC'; // NaOH diarahkan ke Caustic
                } elseif ($chemName == 'ESKAPOL SN-L') {
                    $chemName = 'ESKAPOL SN-LF'; 
                } elseif ($chemName == 'ESKAPOL SN-D') {
                    $chemName = 'ESKAPOL SN-DO'; 
                } elseif ($chemName == 'ACID') {
                    $chemName = 'OXALIC ACID'; // Asumsi ACID umum adalah Oxalic Acid
                }

                // Cari obat di database berdasarkan nama/kode
                $chemical = Chemical::where('active_code', 'LIKE', "%{$chemName}%")
                                    ->orWhere('name', 'LIKE', "%{$chemName}%")
                                    ->first();

                // Jika obatnya sama sekali belum ada di Master, buatkan otomatis!
                if (!$chemical) {
                    $chemical = Chemical::create([
                        'active_code' => $chemName,
                        'name' => $chemName,
                        'is_active' => 1
                    ]);
                }

                // Tempelkan obat ke Proses ini beserta nilai konsentrasinya (Pivot Table)
                $process->chemicals()->attach($chemical->id, [
                    'concentration' => $concentration
                ]);
            }
        }
    }
}