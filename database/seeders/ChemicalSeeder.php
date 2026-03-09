<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChemicalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        // Data sudah difilter dari duplikat agar database bersih
        $chemicals = [
            ['active_code' => 'ESKACID', 'name' => 'ESKACID DA-BA 1'],
            ['active_code' => 'ESKAPLUS DADLK', 'name' => 'ESKAPLUS DA-DLK'],
            ['active_code' => 'HYDRO', 'name' => 'SODIUM HYDROSULPHITE'],
            ['active_code' => 'OXALIC ACID', 'name' => 'OXALIC ACID'],
            ['active_code' => 'CAUSTIC', 'name' => 'CAUSTIC SODA LQ'],
            ['active_code' => 'SUNSOLT RM340', 'name' => 'SUNSOLT RM340'],
            ['active_code' => 'ESKAPLUS DALM', 'name' => 'ESKAPLUS DA-LM'],
            ['active_code' => 'ESKAPOL SN-LF', 'name' => 'ESKAPOL SN-LF'],
            ['active_code' => 'SODA ASH', 'name' => 'Na2CO3'], // Subscript diubah ke teks standar agar aman di DB
            ['active_code' => 'DEMULGEN OR', 'name' => 'DEMULGEN OR'],
            ['active_code' => 'ESKAPOL SN-DO', 'name' => 'ESKAPOL SN-DO'],
            ['active_code' => 'STARBLITZ EA', 'name' => 'STARBLITZ EA'],
            ['active_code' => 'SODIUM SULPHAT', 'name' => 'SODIUM SULPHATE'],
            ['active_code' => 'H2O2', 'name' => 'H2O2'],
            ['active_code' => 'ESKALET SA-AP', 'name' => 'ESKALET SA-AP'],
            ['active_code' => 'UREA', 'name' => 'CO(NH2)2'],
            ['active_code' => 'ESKALET DA-APC', 'name' => 'ESKALET DA-APC'],
            ['active_code' => 'ESKAWET WN-DA', 'name' => 'ESKAWET WN-DA'],
        ];

        // Format data untuk di-insert
        $dataToInsert = array_map(function ($item) use ($now) {
            return [
                'active_code' => $item['active_code'],
                'name' => $item['name'],
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $chemicals);

        // 👇 MATIKAN SEMENTARA PENGECEKAN RELASI MYSQL 👇
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 👇 KOSONGKAN PAKSA KEDUA TABEL (Sapu Bersih) 👇
        DB::table('job_ticket_chemicals')->truncate(); // Hapus riwayat tiket yg pakai obat lama
        DB::table('chemicals')->truncate(); // Kosongkan master bahan kimia

        // 👇 NYALAKAN KEMBALI PENGECEKAN RELASI 👇
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 👇 MASUKKAN DATA BARU YANG FRESH 👇
        DB::table('chemicals')->insert($dataToInsert);
    }
}