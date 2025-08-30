<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomeSetting;

class HomeSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HomeSetting::create([
            'about_title' => 'About Wise Dynamic',
            'about_subtitle' => 'We craft high-performing digital products and growth campaigns that help businesses move faster, scale smarter, and convert better.',
            'about_items' => [
                [
                    'title' => 'Customer-First',
                    'text' => 'Transparent communication and dedicated support',
                    'icon' => 'fas fa-users'
                ],
                [
                    'title' => 'Modern Tech',
                    'text' => 'Data-informed decisions with cutting-edge solutions',
                    'icon' => 'fas fa-laptop-code'
                ],
                [
                    'title' => 'Quality Assured',
                    'text' => 'On-time delivery with rigorous testing',
                    'icon' => 'fas fa-check-circle'
                ],
                [
                    'title' => 'Scalable Solutions',
                    'text' => 'Built to grow with your business needs',
                    'icon' => 'fas fa-chart-line'
                ]
            ]
        ]);
    }
}
