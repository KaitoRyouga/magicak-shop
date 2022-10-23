<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $cards = ['Stripe', 'Paypal', 'JCB', 'VCB'];
        foreach ($cards as $key => $card) {
            $data[] = [
                'name' => $card,
                'icon' => 'icon',
                'sort' => $key + 1,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }


        DB::table('payment_methods')->insert($data);
    }
}
