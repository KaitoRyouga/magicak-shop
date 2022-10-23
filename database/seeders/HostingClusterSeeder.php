<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class HostingClusterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hosting_clusters')->insert([
            [
                'name' => 'magicak-sg',
                'hosting_platform_id' => 1,
                'dc_location_id' => 1,
                'system_domain_id' => 1,
                'sort' => 1,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'magicak-sg-premium',
                'hosting_platform_id' => 2,
                'dc_location_id' => 1,
                'system_domain_id' => 2,
                'sort' => 2,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'magicak-auz',
                'hosting_platform_id' => 1,
                'dc_location_id' => 2,
                'system_domain_id' => 3,
                'sort' => 3,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'magicak-auz-premium',
                'hosting_platform_id' => 2,
                'dc_location_id' => 2,
                'system_domain_id' => 4,
                'sort' => 4,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'magicak-vn',
                'hosting_platform_id' => 1,
                'dc_location_id' => 3,
                'system_domain_id' => 5,
                'sort' => 5,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'magicak-vn-premium',
                'hosting_platform_id' => 2,
                'dc_location_id' => 3,
                'system_domain_id' => 6,
                'sort' => 6,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'magicak-jp',
                'hosting_platform_id' => 1,
                'dc_location_id' => 4,
                'system_domain_id' => 7,
                'sort' => 7,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'magicak-jp-premium',
                'hosting_platform_id' => 2,
                'dc_location_id' => 4,
                'system_domain_id' => 8,
                'sort' => 8,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'magicak-cn',
                'hosting_platform_id' => 1,
                'dc_location_id' => 5,
                'system_domain_id' => 9,
                'sort' => 9,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'magicak-cn-premium',
                'hosting_platform_id' => 2,
                'dc_location_id' => 5,
                'system_domain_id' => 10,
                'sort' => 10,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
