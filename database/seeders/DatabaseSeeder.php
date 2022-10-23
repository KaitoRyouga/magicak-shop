<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            DataCenterLocationSeeder::class,
            SystemDomainSeeder::class,
            HostingPlatformSeeder::class,
            HostingClusterSeeder::class,
            DomainTypeSeeder::class,
            PaymentMethodSeeder::class,
            HostingPlanTypeSeeder::class,
            HostingPlanSeeder::class,
            HostingPlanPriceSeeder::class,
            HostingPlanDcLocationMappingSeeder::class,
            TemplateTypeSeeder::class,
            TemplateCategorySeeder::class,
            TemplateSeeder::class,
            TemplatePriceSeeder::class,
            UserAttributeSeeder::class,
            SettingSeeder::class,
            DomainSeeder::class,
            UserWebsiteSeeder::class,
            HostingPlanClusterMappingSeeder::class,
            TransactionTypeSeeder::class,
            TemporaryDomainSeeder::class
        ]);
    }
}
