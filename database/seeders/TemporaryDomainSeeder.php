<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Services\DreamScapeServiceProduction;

class TemporaryDomainSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->dreamScapeServiceProduction = new DreamScapeServiceProduction();
        $data = [];

        $nake_domain = ['.magicak-sg.com', '.magicak-vn.com', '.magicak-auz.com', '.magicak-jp.com', '.magicak-cn.com'];

        for ($i = 1; $i <= 1000; $i++) {
            if ($i <= 200) {
                $dataDNS = [
                    "type" => 'A',
                    "content" => '195.133.139.169',
                    "subdomain" => 'temp' . $i,
                ];
                $domain = $this->dreamScapeServiceProduction->request("domains/25940759/dns", 'POST', [], $dataDNS);
                $data[] = [
                    'remote_domain_id' => $domain['id'],
                    'customer_id' => '10890501',
                    'domain_type_id' => 2,
                    'dc_location_id' => 1,
                    'domain_name' => 'temp' . $i . $nake_domain[0],
                    'ip' => '195.133.139.169',
                    'available' => 1,
                    'active' => 1
                ];
            } elseif ($i <= 400 && $i > 200) {
                $data[] = [
                    'remote_domain_id' => $i,
                    'customer_id' => $i,
                    'domain_type_id' => 2,
                    'dc_location_id' => 2,
                    'domain_name' => 'temp' . $i . $nake_domain[1],
                    'ip' => '195.133.139.169',
                    'available' => 1,
                    'active' => 1
                ];
            } elseif ($i <= 600 && $i > 400) {
                $data[] = [
                    'remote_domain_id' => $i,
                    'customer_id' => $i,
                    'domain_type_id' => 2,
                    'dc_location_id' => 3,
                    'domain_name' => 'temp' . $i . $nake_domain[2],
                    'ip' => '195.133.139.169',
                    'available' => 1,
                    'active' => 1
                ];
            } elseif ($i <= 800 && $i > 600) {
                $data[] = [
                    'remote_domain_id' => $i,
                    'customer_id' => $i,
                    'domain_type_id' => 2,
                    'dc_location_id' => 4,
                    'domain_name' => 'temp' . $i . $nake_domain[3],
                    'ip' => '195.133.139.169',
                    'available' => 1,
                    'active' => 1
                ];
            } elseif ($i <= 1000 && $i > 800) {
                $data[] = [
                    'remote_domain_id' => $i,
                    'customer_id' => $i,
                    'domain_type_id' => 2,
                    'dc_location_id' => 5,
                    'domain_name' => 'temp' . $i . $nake_domain[4],
                    'ip' => '195.133.139.169',
                    'available' => 1,
                    'active' => 1
                ];
            }
        }

        DB::table('temporary_domains')->insert($data);
    }
}
