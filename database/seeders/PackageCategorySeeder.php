<?php

namespace Database\Seeders;

use App\Models\PackageCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PackageCategorySeeder extends Seeder
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
                'description' => 'Professional website development services for businesses of all sizes.',
                'status' => true,
            ],
            [
                'name' => 'Digital Marketing',
                'slug' => 'digital-marketing',
                'icon' => 'fas fa-bullhorn',
                'description' => 'Comprehensive digital marketing solutions to grow your online presence.',
                'status' => true,
            ],
        ];

        foreach ($categories as $category) {
            PackageCategory::create($category);
        }
    }
}
