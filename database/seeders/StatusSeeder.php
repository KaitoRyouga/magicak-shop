<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $statuses = ['In progress', 'Payed', 'Up', 'Down', 'Delete', 'Suspended'];
        foreach ($statuses as $key => $status) {
            $data[] = [
                'name' => $status,
                'sort' => $key,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('statuses')->insert($data);
    }
}
