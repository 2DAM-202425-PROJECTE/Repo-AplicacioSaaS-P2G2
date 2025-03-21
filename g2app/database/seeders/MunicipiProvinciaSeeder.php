<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MunicipiProvinciaSeeder extends Seeder
{
    public function run(): void
    {
        $provinces = json_decode(File::get(public_path('data/provinces.json')), true);
        $provinces = array_slice($provinces, 0, 14); // Limit to first x provinces
        $provinces = array_map(function ($province) {
            return [
                'id' => $province['id'],
                'name' => $province['name'],
            ];
        }, $provinces);

        DB::table('provincias')->insert($provinces);

        $municipalities = json_decode(File::get(public_path('data/municipalities.json')), true);


        $municipalities = array_filter($municipalities, function ($municipality) use ($provinces) {
            return in_array($municipality['province_id'], array_column($provinces, 'id'));
        });


        $municipalitiesToInsert = [];
        foreach ($provinces as $province) {
            $provinceMunicipalities = array_filter($municipalities, function ($municipality) use ($province) {
                return $municipality['province_id'] === $province['id'];
            });


            $limitedProvinceMunicipalities = array_slice($provinceMunicipalities, 0, 8);

            foreach ($limitedProvinceMunicipalities as $municipality) {
                $municipalitiesToInsert[] = [
                    'id' => $municipality['id'],
                    'name' => $municipality['name'],
                    'provincia_id' => $municipality['province_id'],
                ];
            }
        }

        DB::table('municipios')->insert($municipalitiesToInsert);
    }
}
