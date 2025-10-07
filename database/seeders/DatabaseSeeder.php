<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            // Use SlideSeeder (without the 's')
            SlideSeeder::class,
            WebsiteSettingSeeder::class,
            AboutPageSeeder::class,
            ContactPageSeeder::class,
            ServiceCategorySeeder::class,
            ServiceSeeder::class,
            PackageCategorySeeder::class,
            PackageSeeder::class,
            DigitalMarketingPackagesSeeder::class,
            WebsiteDevelopmentPackagesSeeder::class,
        ]);
    }
}
