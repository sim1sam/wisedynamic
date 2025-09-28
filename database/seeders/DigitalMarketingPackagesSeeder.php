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
                'description' => "<ul><li>12 branded content designs</li><li>Facebook/Instagram Ads setup</li><li>Page setup & audience targeting</li><li>Weekly performance report</li></ul>",
                'price' => 12000,
                'price_unit' => 'Per Month',
                'status' => true,
                'featured' => false,
            ],
            [
                'title' => 'SEO Growth Plan',
                'short_description' => 'Improve your search engine rankings',
                'description' => "<ul><li>Full website SEO audit</li><li>Keyword research + competitor analysis</li><li>On-page + Technical SEO</li><li>Google Console & Sitemap setup</li><li>Monthly rank tracking</li></ul>",
                'price' => 18000,
                'price_unit' => 'Per Month',
                'status' => true,
                'featured' => true,
            ],
            [
                'title' => 'Google Ads Campaign',
                'short_description' => 'Drive targeted traffic to your website',
                'description' => "<ul><li>Google Ads account setup</li><li>Up to 3 campaign sets</li><li>Conversion & traffic targeting</li><li>A/B testing + ROI reporting</li></ul>",
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
