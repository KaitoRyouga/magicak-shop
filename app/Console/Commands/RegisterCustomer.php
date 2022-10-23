<?php

namespace App\Console\Commands;

use App\Managers\DomainManager;
use Illuminate\Console\Command;
use App\Services\DreamScapeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class RegisterCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'register:customer';


    /**
     * @var DreamScapeService
     */
    protected $dreamScapeService;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register customer and domain';

    /**
     * Create a new command instance.
     * @param DreamScapeService $dreamScapeService
     * @return void
     */
    public function __construct(
        DreamScapeService $dreamScapeService
    ) {
        parent::__construct();
        $this->dreamScapeService = $dreamScapeService;
    }

    /**
     * Execute the console command.
     * @throws \Exception
     */
    public function handle()
    {
        $data = [
            "first_name" => "Massive",
            "last_name" => "Aggregate",
            "address" => "10 ANSON ROAD INTERNATIONAL PLAZA #27-15",
            "city" => "Singapore",
            "country" => "SG",
            "state" => "Singapore",
            "post_code" => "079903",
            "country_code" => 65,
            "phone" => "85106688",
            "email" => "admin@magicak.com",
            "account_type" => "business",
            "username" => "magicakAdmin",
            "password" => "123qweasd!@#QWEASD",
            "mobile" => "85106688",
            "business_name" => "magicak",
            "business_number_type" => "ABN",
            "business_number" => "201904562M",
        ];

        try {
            $this->dreamScapeService->request('customers', 'POST', [], $data);
        } catch (\Throwable $e) {
            $this->info('Insert Vodien customer successfully!');
        }

        $account = DB::table('service_accounts')->where('username', $data['username'])->first();
        if (!$account) {
            DB::table('service_accounts')->insert($data);
        }

        $customers = $this->dreamScapeService->request('customers', 'GET', [], []);
        $customer_id = 0;
        foreach ($customers as $value) {
            if ($value['username'] == $data['username']) {
                $customer_id = $value['id'];
                break;
            }
        }

        $dataDomain = [
            "domain_name" => strtolower(config('app.env')) === 'production' ? config('magicak.domain_prod') : config('magicak.domain_dev'),
            "customer_id" => $customer_id,
            "period" => 12,
            "eligibility_data" => [
                [
                    "name" => "business_type",
                    "value" => "Other"
                ],
                [
                    "name" => "business_name",
                    "value" => "magicak"
                ],
                [
                    "name" => "business_number_type",
                    "value" => "VIC BN"
                ],
                [
                    "name" => "business_number",
                    "value" => "201904562M"
                ]
            ]
        ];

        try {
            $this->dreamScapeService->request('domains', 'POST', [], $dataDomain);
        } catch (\Throwable $e) {
            $this->info('Insert Vodien domain successfully!');
        }

        $domains = $this->dreamScapeService->request('domains', 'GET', [], []);

        $domain_current = [];
        foreach ($domains as $value) {
            if ($value['domain_name'] == $dataDomain['domain_name']) {
                $domain_current = $value;
                break;
            }
        }

        DB::table('domains')->insert([
            'remote_domain_id' => $domain_current['id'],
            'customer_id' => $customer_id,
            'domain_name' => $dataDomain['domain_name'],
            'registration_date' => $domain_current['start_date'],
            'expiration_date' => $domain_current['expiry_date'],
            'domain_type_id' => DomainManager::NEW_DOMAIN,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
