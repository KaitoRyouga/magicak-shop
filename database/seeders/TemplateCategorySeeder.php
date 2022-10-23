<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TemplateCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $arrNames = [
            'Business' => false,
            'OnlineStore' => false,
            'Music' => false,
            'Design' => true,
            'Blog' => false,
            'Beauty & Wellness' => true,
            'Portfolio & CV' => true,
            'Events' => false,
            'Photography' => true,
            'Restaurant&Food' => false,
            'Fitness' => false,
            'Other' => false,
        ];

        $i = 1;
        foreach ($arrNames as $name => $premium) {
            $data[] = [
                'template_type_id' => $premium ? 2 : 1,
                'name' => $name,
                'sort' => $i,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $i++;
        }

        DB::table('template_categories')->insert($data);
    }
}
