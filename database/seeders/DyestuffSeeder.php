<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DyestuffSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $dyestuffs = [
            // --- KELOMPOK D (DISPERSE DYES) ---
            ['active_code' => 'BLACK CA', 'name' => 'DOMACRON BLACK CA', 'type' => 'D'],
            ['active_code' => 'BLACK ECO', 'name' => 'DOMACRON BLACK ECO 30%', 'type' => 'D'],
            ['active_code' => 'BLUE SE-RDN F', 'name' => 'FORON BLUE SE-RDN', 'type' => 'D'],
            ['active_code' => 'BLUE ERD', 'name' => 'DOMACRON BLUE ERD', 'type' => 'D'],
            ['active_code' => 'BLUE SERN', 'name' => 'DOMACRON BLUE SERN 200%', 'type' => 'D'],
            ['active_code' => 'BLUE UNSE', 'name' => 'DOMACRON BLUE UN-SE 200%', 'type' => 'D'],
            ['active_code' => 'BLUE-2BLN', 'name' => 'DOMACRON BLUE-2BLN', 'type' => 'D'],
            ['active_code' => 'NAVY BLUE S2GL', 'name' => 'DOMACRON NAVY BLUE S2GLL 220%', 'type' => 'D'],
            ['active_code' => 'NAVY SEFS', 'name' => 'DOMACRON NAVY SEFS 300%', 'type' => 'D'],
            ['active_code' => 'ORANGE S4RL', 'name' => 'DOMACRON ORANGE S4RL 150%', 'type' => 'D'],
            ['active_code' => 'ORANGE SC', 'name' => 'DOMACRON ORANGE SC', 'type' => 'D'],
            ['active_code' => 'RED 277', 'name' => 'DOMACRON RED 277', 'type' => 'D'],
            ['active_code' => 'RED SE-RD F', 'name' => 'FORON RED SE-RD', 'type' => 'D'],
            ['active_code' => 'RED ERD', 'name' => 'DOMACRON RED ERD', 'type' => 'D'],
            ['active_code' => 'RED S3BS', 'name' => 'DOMACRON RED S3BS 150%', 'type' => 'D'],
            ['active_code' => 'RED SE GFL', 'name' => 'DOMACRON RED SE-GFL 200%', 'type' => 'D'],
            ['active_code' => 'RED UNSE', 'name' => 'DOMACRON RED UN-SE 200%', 'type' => 'D'],
            ['active_code' => 'RUBINE XHF', 'name' => 'DOMACRON RUBINE XHF', 'type' => 'D'],
            ['active_code' => 'TURQ BSGL', 'name' => 'DOMACRON TURQUOISE BLUE SGL 200%', 'type' => 'D'],
            ['active_code' => 'VIOLET 28', 'name' => 'DOMACRON VIOLET 28', 'type' => 'D'],
            ['active_code' => 'VIOLET RL', 'name' => 'DOMACRON VIOLET RL 200%', 'type' => 'D'],
            ['active_code' => 'YELLOW 8 GFF', 'name' => 'DOMACRON YELLOW 8 GFF', 'type' => 'D'],
            ['active_code' => 'YELLOW SE-RD F BRILL', 'name' => 'FORON BRILLIANT YELLOW SE-RD', 'type' => 'D'],
            ['active_code' => 'YELLOW ERD', 'name' => 'DOMACRON YELLOW ERD', 'type' => 'D'],
            ['active_code' => 'YELLOW S6GL', 'name' => 'DOMACRON YELLOW S6GL 200%', 'type' => 'D'],
            ['active_code' => 'YELLOW SE4G', 'name' => 'DOMACRON YELLOW SE4G 200%', 'type' => 'D'],
            ['active_code' => 'YELLOW SEFN', 'name' => 'DOMACRON YELLOW SEFN', 'type' => 'D'],
            ['active_code' => 'YELLOW UN-SE', 'name' => 'DOMACRON YELLOW UN-SE', 'type' => 'D'],
            ['active_code' => 'ESKAWHITE DN PN', 'name' => 'ESKAWHITE DN PN', 'type' => 'D'],
            ['active_code' => 'TOR BLUEFBLN', 'name' => 'TORAPERSE BLUE FBLN 200%', 'type' => 'D'],
            ['active_code' => 'TF-905', 'name' => 'TRANSWHITE TF-905', 'type' => 'D'],

            // --- KELOMPOK R (REACTIVE DYES) ---
            ['active_code' => 'BLACK CK-RW', 'name' => 'DOMAFIX BLACK CK-RW', 'type' => 'R'],
            ['active_code' => 'BLUE RSP', 'name' => 'DOMAFIX BLUE RSP', 'type' => 'R'],
            ['active_code' => 'CRIMSON RED KRB', 'name' => 'DOMAFIX CRIMSON RED KRB', 'type' => 'R'],
            ['active_code' => 'DEEP RED SB', 'name' => 'DOMAFIX DEEP RED SB', 'type' => 'R'],
            ['active_code' => 'GOLDEN YELLOW K3RS', 'name' => 'DOMAFIX GOLDEN YELLOW K3RS', 'type' => 'R'],
            ['active_code' => 'NAVY BLUE KGB', 'name' => 'DOMAFIX NAVY BLUE KGB', 'type' => 'R'],
            ['active_code' => 'ORANGE K-3RN', 'name' => 'DOMAFIX ORANGE K-3RN', 'type' => 'R'],
            ['active_code' => 'TURQUOISE BLUE G', 'name' => 'DOMAFIX TURQUOISE BLUE G 266%', 'type' => 'R'],
            ['active_code' => 'YELLOW 4GL', 'name' => 'DOMAFIX YELLOW 4GL', 'type' => 'R'],
        ];

        // Memasukkan data ke format array untuk di-insert
        $dataToInsert = array_map(function ($item) use ($now) {
            return [
                'active_code' => $item['active_code'],
                'name' => $item['name'],
                'type' => $item['type'],
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $dyestuffs);

        // 👇 1. MATIKAN SEMENTARA PENGECEKAN RELASI MYSQL 👇
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 👇 2. KOSONGKAN PAKSA KEDUA TABEL 👇
        // Hapus rincian tiket yang memakai zat warna lama (agar tidak ada data 'yatim piatu')
        DB::table('job_ticket_dyestuffs')->truncate(); 
        // Hapus master zat warna
        DB::table('dyestuffs')->truncate(); 

        // 👇 3. NYALAKAN KEMBALI PENGECEKAN RELASI 👇
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 👇 4. MASUKKAN DATA BARU YANG FRESH 👇
        DB::table('dyestuffs')->insert($dataToInsert);
    }
}