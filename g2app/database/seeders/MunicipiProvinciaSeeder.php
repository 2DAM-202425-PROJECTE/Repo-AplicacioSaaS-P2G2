<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MunicipiProvinciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Read and decode municipalities.json
        $municipalities = json_decode(File::get(public_path('data/municipalities.json')), true);
        $municipalities = array_slice($municipalities, 0, 30); // Limit to 20 entries
        $municipalities = array_map(function ($municipality) {
            return [
                'id' => $municipality['id'],
                'name' => $municipality['name'],
                'provincia_id' => $municipality['province_id'],
            ];
        }, $municipalities);


        // Collect province_ids from inserted municipalities
        $provinceIds = array_unique(array_column($municipalities, 'provincia_id'));

        // Read and decode provinces.json
        $provinces = json_decode(File::get(public_path('data/provinces.json')), true);
        $provinces = array_filter($provinces, function ($province) use ($provinceIds) {
            return in_array($province['id'], $provinceIds);
        });
        $provinces = array_map(function ($province) {
            return [
                'id' => $province['id'],
                'name' => $province['name'],
            ];
        }, $provinces);
        DB::table('provincias')->insert($provinces);
        DB::table('municipios')->insert($municipalities);
    }
}
