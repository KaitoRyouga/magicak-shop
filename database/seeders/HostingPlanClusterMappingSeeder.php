<?php

namespace Database\Seeders;

use App\Models\HostingCluster;
use App\Models\Cart;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartClusterMappingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = [];

        $count = 1;

        for ($i=1; $i < 6; $i++) {

            for ($y = 1; $y < 9; $y++) {

                if ($y == 1 || $y == 2 || $y == 5 || $y == 6) {
                    $list[] = [
                        'hosting_plan_id' => $y,
                        'hosting_cluster_id' => $count,
                    ];
                } else {
                    $list[] = [
                        'hosting_plan_id' => $y,
                        'hosting_cluster_id' => $count + 1,
                    ];
                }
            }
            $count += 2;
        }


        DB::table('hosting_cluster_mappings')->insert($list);


    }
}
