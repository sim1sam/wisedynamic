<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\PackageCategory;
use Illuminate\Support\Str;

class DigitalMarketingPackagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find or create Digital Marketing category
        $category = PackageCategory::firstOrCreate(
            ['name' => 'Digital Marketing'],
            [
                'slug' => 'digital-marketing',
                'description' => 'Professional digital marketing services to grow your online presence',
                'status' => true
            ]
        );
        
        // Define the packages
        $packages = [
            [
                'title' => 'Social Media Marketing',
                'short_description' => 'Boost your social media presence',
                'description' => "12 branded content designs\nFacebook/Instagram Ads setup\nPage setup & audience targeting\nWeekly performance report",
                'price' => 12000,
                'price_unit' => 'Per Month',
                'status' => true,
                'featured' => false,
            ],
            [
                'title' => 'SEO Growth Plan',
                'short_description' => 'Improve your search engine rankings',
                'description' => "Full website SEO audit\nKeyword research + competitor analysis\nOn-page + Technical SEO\nGoogle Console & Sitemap setup\nMonthly rank tracking",
                'price' => 18000,
                'price_unit' => 'Per Month',
                'status' => true,
                'featured' => true,
            ],
            [
                'title' => 'Google Ads Campaign',
                'short_description' => 'Drive targeted traffic to your website',
                'description' => "Google Ads account setup\nUp to 3 campaign sets\nConversion & traffic targeting\nA/B testing + ROI reporting",
                'price' => 15000,
                'price_unit' => 'Per Month',
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
        
        echo "Digital Marketing Packages seeded successfully!\n";
    }
}
