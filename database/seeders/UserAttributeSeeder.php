<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UserAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_attributes')->insert([
            [
                'name' => 'Phone',
                'mapping_field' => 'phone',
                'sort' => 1,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Phone verified',
                'mapping_field' => 'phone_verified',
                'sort' => 2,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Billing address',
                'mapping_field' => 'billing_address',
                'sort' => 3,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Billing Proof',
                'mapping_field' => 'billing_proof',
                'sort' => 4,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Postcode',
                'mapping_field' => 'postcode',
                'sort' => 5,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Country',
                'mapping_field' => 'country',
                'sort' => 6,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Free website',
                'mapping_field' => 'free_website',
                'sort' => 7,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Company',
                'mapping_field' => 'company',
                'sort' => 9,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Birth day',
                'mapping_field' => 'birth_date',
                'sort' => 11,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Language',
                'mapping_field' => 'language',
                'sort' => 12,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Gender',
                'mapping_field' => 'gender',
                'sort' => 13,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Twitter',
                'mapping_field' => 'twitter',
                'sort' => 14,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Facebook',
                'mapping_field' => 'facebook',
                'sort' => 15,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Instagram',
                'mapping_field' => 'instagram',
                'sort' => 16,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Avatar',
                'mapping_field' => 'avatar',
                'sort' => 17,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Avatar',
                'mapping_field' => 'avatar',
                'sort' => 17,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'City',
                'mapping_field' => 'city',
                'sort' => 18,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'State',
                'mapping_field' => 'state',
                'sort' => 18,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
