<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CartPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hosting_plan_prices')->insert([
            [ // 1
                'hosting_plan_id' => 1,
                'month' => 1,
                'price' => 0,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [ // 2
                'hosting_plan_id' => 2,
                'month' => 1,
                'price' => 10,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [ // 3
                'hosting_plan_id' => 3,
                'month' => 1,
                'price' => 20,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [ // 4
                'hosting_plan_id' => 4,
                'month' => 1,
                'price' => 30,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [ // 5
                'hosting_plan_id' => 5,
                'month' => 1,
                'price' => 0,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [ // 6
                'hosting_plan_id' => 6,
                'month' => 1,
                'price' => 15,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [ // 7
                'hosting_plan_id' => 7,
                'month' => 1,
                'price' => 25,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [ // 8
                'hosting_plan_id' => 8,
                'month' => 1,
                'price' => 35,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
