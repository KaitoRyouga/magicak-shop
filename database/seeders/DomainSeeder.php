<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Managers\DomainManager;

class DomainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('domains')->insert([
            [
                'remote_domain_id' => 547,
                'customer_id' => 283,
                'Domain_Register_from' => 'Vodien',
                'Domain_name' => 'test123.org',
                'Domain_time' => '1',
                'active' => DomainManager::STATUS_ACTIVE,
                'Registration_date' => Carbon::now(),
                'Expiration_date' => Carbon::now()->addYear(),
                'domain_type_id' => DomainManager::NEW_DOMAIN,
                'Domain_Migration' => false,
                'Domain_Migration_status' => null,
                'ip' => null,
                'created_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'remote_domain_id' => 2701,
                'customer_id' => 283,
                'Domain_Register_from' => 'Vodien',
                'Domain_name' => 'bannha.magicak-vn.com',
                'Domain_time' => '1',
                'active' => DomainManager::STATUS_ACTIVE,
                'Registration_date' => Carbon::now(),
                'Expiration_date' => Carbon::now()->addYear(),
                'domain_type_id' => DomainManager::SUB_DOMAIN,
                'Domain_Migration' => false,
                'Domain_Migration_status' => null,
                'ip' => null,
                'created_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
