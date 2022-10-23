<?php

namespace Database\Seeders;

use App\Models\CartItem;
use App\Models\Cart;
use Illuminate\Database\Seeder;

class CartDcLocationMappingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hostingPlans = Cart::all();
        $dcLocations = CartItem::all()->pluck('id');

        foreach ($hostingPlans as $hostingPlan) {
            $hostingPlan->dcLocation()->attach($dcLocations);
        }
    }
}
