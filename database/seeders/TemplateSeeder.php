<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Faker\Factory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        $filePath = Storage::path('templates');
        if(!File::exists($filePath)){
            File::makeDirectory($filePath);
        }

        $data = [];
        for ($i = 0; $i < 50; $i++) {
            $cateId = random_int(1, 12);

            Storage::path('');
            // $thumbnail = $faker->imageUrl(200, 200, 'technics');
            // $capture = $faker->imageUrl(200, 200, 'technics');

            $thumbnail = $faker->imageUrl(200, 200, 'technics');
            $capture = $faker->imageUrl(200, 200, 'technics');

            $data[] = [
                'template_category_id' => $cateId,
                'name' => $faker->name,
                'code' => 'T' . rand(1, 100),
                'description' => $faker->text(80),
                'version' => 1,
                'thumbnail' => str_replace(Storage::path(''), '', $thumbnail),
                'capture' => str_replace(Storage::path(''), '', $capture),
                'url' => $faker->url,
                'template_app_storage' => rand(1, 10),
                'template_db_storage' => rand(1, 10),
                'sort' => ($i + 1),
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('templates')->insert($data);
    }
}
