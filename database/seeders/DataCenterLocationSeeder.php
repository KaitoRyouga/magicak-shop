<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DataCenterLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrLocations = [
            'SG' => 'Singapore',
            'AUZ' => 'Australia',
            'VN' => 'Việt Nam',
            'JP' => 'Japan',
            'CN' => 'China',
        ];

        $i = 1;
        foreach ($arrLocations as $key => $location) {
            $data[] = [
                'location' => $location,
                'code' => $key,
                'sort' => $i,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $i++;
        }

        DB::table('data_center_locations')->insert($data);
    }
}
