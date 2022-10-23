<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert(
            [
                'copyright' => 'COPYRIGHT © 2022 Magicak, All rights Reserved',
                'power_by' => 'Magicak Dashboard Webapp',
                'logo_dashboard' => '',
                'logo_landing_page' => '',
                'social_link' => json_encode(
                    [
                        "facebook" => "https://www.facebook.com/",
                        "twitter" => "https://twitter.com/",
                        "instagram" => "https://www.instagram.com/"
                    ]
                ),
                'video_config_domain' => 'https://www.youtube.com/embed/SZsDwXaXYNQ',
                'video_get_auth_key' => 'https://www.youtube.com/embed/317t-Q1TgQg',
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        );
    }
}
