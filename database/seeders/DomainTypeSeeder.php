<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DomainTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('domain_types')->insert([
            [
                'name' => 'New domain',
                'description' => 'Register <span class="domain-blue">a New Domain</span>',
                'sort' => 1,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Sub domain',
                'description' => 'Use Magicak a <span class="domain-blue">Free Sub-Domain</span>',
                'sort' => 2,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'New domain',
                'description' => 'I already <span class="domain-blue">have a Domain</span>',
                'sort' => 3,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
