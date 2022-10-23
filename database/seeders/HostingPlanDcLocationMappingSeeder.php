<?php

namespace Database\Seeders;

use App\Models\DataCenterLocation;
use App\Models\HostingPlan;
use Illuminate\Database\Seeder;

class HostingPlanDcLocationMappingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hostingPlans = HostingPlan::all();
        $dcLocations = DataCenterLocation::all()->pluck('id');

        foreach ($hostingPlans as $hostingPlan) {
            $hostingPlan->dcLocation()->attach($dcLocations);
        }
    }
}
