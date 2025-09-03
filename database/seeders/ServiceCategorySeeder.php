<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Website Development',
                'slug' => 'website-development',
                'icon' => 'fas fa-laptop-code',
                'description' => 'Responsive, SEO-optimized websites from WordPress to custom PHP solutions',
                'status' => true,
            ],
            [
                'name' => 'Web App & E-commerce',
                'slug' => 'web-app-ecommerce',
                'icon' => 'fas fa-globe',
                'description' => 'Full-featured online stores and web applications',
                'status' => true,
            ],
            [
                'name' => 'Payment Gateway',
                'slug' => 'payment-gateway',
                'icon' => 'fas fa-credit-card',
                'description' => 'SSL partnership for secure online payments',
                'status' => true,
            ],
            [
                'name' => 'Digital Marketing',
                'slug' => 'digital-marketing',
                'icon' => 'fas fa-bullhorn',
                'description' => 'SEO, Social Media, and Google Ads management',
                'status' => true,
            ],
            [
                'name' => 'Background Music',
                'slug' => 'background-music',
                'icon' => 'fas fa-music',
                'description' => 'Custom copyright-free music for your brand',
                'status' => true,
            ],
            [
                'name' => 'Website Management',
                'slug' => 'website-management',
                'icon' => 'fas fa-cogs',
                'description' => 'Ongoing support and content management',
                'status' => true,
            ],
        ];

        foreach ($categories as $category) {
            ServiceCategory::create($category);
        }
    }
}
