<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Faker\Factory;

class TemplatePriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        $data = [];
        for ($i = 1; $i <= 50; $i++) {
            $cate = Template::find($i)->category()->first();

            $data[] = [
                'template_id' => $i,
                'price' => $cate->template_type_id === 1 ? 0 : $faker->numberBetween(5, 1000),
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('template_prices')->insert($data);
    }
}
