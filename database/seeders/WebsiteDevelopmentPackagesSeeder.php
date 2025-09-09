<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\PackageCategory;
use Illuminate\Support\Str;

class WebsiteDevelopmentPackagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find or create Website Development category
        $category = PackageCategory::firstOrCreate(
            ['name' => 'Website Development'],
            [
                'slug' => 'website-development',
                'description' => 'Professional website development services for businesses of all sizes',
                'status' => true
            ]
        );
        
        // Define the packages
        $packages = [
            [
                'title' => 'Startup',
                'short_description' => 'Perfect for Portfolio',
                'description' => "WordPress Technology\nResponsive Design\nBasic SEO\nFree Domain (1st Year)\nFree 1GB Hosting\n3-7 Days Delivery",
                'price' => 20000,
                'price_unit' => null,
                'status' => true,
                'featured' => false,
            ],
            [
                'title' => 'Streamline',
                'short_description' => 'Best for E-Commerce',
                'description' => "PHP Technology\nResponsive Design\nBasic SEO\nFree Domain (1st Year)\nFree 3GB Hosting\n15-20 Days Delivery",
                'price' => 50000,
                'price_unit' => null,
                'status' => true,
                'featured' => true,
            ],
            [
                'title' => 'Scale',
                'short_description' => 'Advanced E-Commerce',
                'description' => "PHP Technology\nResponsive Design\nAdvanced SEO\nFree Domain (1st Year)\nFree 5GB Hosting\nFree Payment Integration\n25-30 Days Delivery",
                'price' => 80000,
                'price_unit' => null,
                'status' => true,
                'featured' => false,
            ],
            [
                'title' => 'Stable',
                'short_description' => 'Custom Requirements',
                'description' => "Custom Technology\nResponsive Design\nAdvanced SEO\nCustom Features\nScalable Architecture\nPremium Support",
                'price' => 200000,
                'price_unit' => null,
                'status' => true,
                'featured' => false,
            ],
        ];
        
        // Create the packages
        foreach ($packages as $packageData) {
            Package::updateOrCreate(
                ['title' => $packageData['title'], 'package_category_id' => $category->id],
                [
                    'slug' => Str::slug($packageData['title']),
                    'short_description' => $packageData['short_description'],
                    'description' => $packageData['description'],
                    'price' => $packageData['price'],
                    'price_unit' => $packageData['price_unit'],
                    'status' => $packageData['status'],
                    'featured' => $packageData['featured'],
                    'package_category_id' => $category->id,
                ]
            );
        }
        
        echo "Website Development Packages seeded successfully!\n";
    }
}
