<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Managers\ProductManager;
use App\Managers\DomainManager;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_websites')->insert([
            [
                'status' => ProductManager::STATUS_INITIAL,
                'business_name' => 'test1',
                'template_id' => 1,
                'template_name' => Template::find(1)->name,
                'hosting_plan_id' => 1,
                'dc_location_id' => 1,
                'domain_type_id' => DomainManager::NEW_DOMAIN,
                'domain_id' => 1,
                'created_id' => 4,
                'updated_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'status' => ProductManager::STATUS_INITIAL,
                'business_name' => 'test2',
                'template_id' => 2,
                'template_name' => Template::find(2)->name,
                'hosting_plan_id' => 1,
                'dc_location_id' => 1,
                'domain_type_id' => DomainManager::SUB_DOMAIN,
                'domain_id' => 2,
                'created_id' => 4,
                'updated_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
