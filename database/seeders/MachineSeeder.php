<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MachineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        // Data Mesin (Tanda underscore '_' diganti spasi agar cetakan lebih rapi)
        $machines = [
            ['name' => 'HUAH JENN 2500', 'volume' => 2000, 'machine_code' => '1'],
            ['name' => 'HUAH JENN 3000', 'volume' => 3000, 'machine_code' => '1'],
            ['name' => 'HUAH JENN 5000', 'volume' => 5000, 'machine_code' => '2'],
            ['name' => 'HUAH JENN 6000', 'volume' => 6000, 'machine_code' => '2'],
            ['name' => 'KUNNAN 5000',    'volume' => 5000, 'machine_code' => '2'],
            ['name' => 'KUNNAN 6000',    'volume' => 6000, 'machine_code' => '2'],
            ['name' => 'KUNNAN 6500',    'volume' => 6500, 'machine_code' => '2'],
            ['name' => 'KUNNAN 7000',    'volume' => 7000, 'machine_code' => '3'],
            ['name' => 'KUNNAN 7500',    'volume' => 7500, 'machine_code' => '4'],
            ['name' => 'HISAKA 3000',    'volume' => 3000, 'machine_code' => '1'],
            ['name' => 'SAMILL 2000',    'volume' => 3000, 'machine_code' => '1'],
            ['name' => 'SAMILL 2500',    'volume' => 3000, 'machine_code' => '1'],
            ['name' => 'JIGGER 1000',    'volume' => 1700, 'machine_code' => '1'],
        ];

        // Format data untuk di-insert ke database
        $dataToInsert = array_map(function ($item) use ($now) {
            return [
                'name'         => $item['name'],
                'volume'       => $item['volume'],
                'machine_code' => $item['machine_code'],
                'is_active'    => 1, // Status otomatis Aktif
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }, $machines);

        // 👇 MATIKAN SEMENTARA PENGECEKAN RELASI MYSQL 👇
        // Agar kita bisa membersihkan mesin dummy tanpa meng-error-kan Job Ticket yang lama
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 👇 KOSONGKAN PAKSA TABEL MESIN 👇
        DB::table('machines')->truncate(); 

        // 👇 NYALAKAN KEMBALI PENGECEKAN RELASI 👇
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 👇 MASUKKAN SEMUA DATA MESIN BARU 👇
        DB::table('machines')->insert($dataToInsert);
    }
}