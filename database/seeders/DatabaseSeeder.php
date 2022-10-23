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
            CartItemSeeder::class,
            SystemDomainSeeder::class,
            HostingPlatformSeeder::class,
            HostingClusterSeeder::class,
            DomainTypeSeeder::class,
            PaymentMethodSeeder::class,
            CartTypeSeeder::class,
            CartSeeder::class,
            CartPriceSeeder::class,
            CartDcLocationMappingSeeder::class,
            TemplateTypeSeeder::class,
            TemplateCategorySeeder::class,
            TemplateSeeder::class,
            TemplatePriceSeeder::class,
            UserAttributeSeeder::class,
            SettingSeeder::class,
            DomainSeeder::class,
            ProductSeeder::class,
            CartClusterMappingSeeder::class,
            TransactionTypeSeeder::class,
            TemporaryDomainSeeder::class
        ]);
    }
}
