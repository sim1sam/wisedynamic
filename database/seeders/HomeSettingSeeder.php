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
            ],
            'why_choose_title' => 'Why Choose Wise Dynamic?',
            'why_choose_subtitle' => 'We blend creativity, technology, and personalized support',
            'why_choose_items' => [
                [
                    'title' => 'BASIS Certified Excellence',
                    'text' => 'Official BASIS member since 2020, ensuring professional standards and reliability',
                    'icon' => 'fas fa-award'
                ],
                [
                    'title' => 'Startup-Friendly Pricing',
                    'text' => 'Affordable solutions designed for young entrepreneurs and growing businesses',
                    'icon' => 'fas fa-rocket'
                ],
                [
                    'title' => 'Full-Spectrum Solutions',
                    'text' => 'From websites to mobile apps, marketing to music — everything under one roof',
                    'icon' => 'fas fa-tools'
                ],
                [
                    'title' => 'Dedicated Small Team',
                    'text' => 'Personal attention and care - we treat your success like our own',
                    'icon' => 'fas fa-heart'
                ]
            ],
            'why_choose_clients_count' => 100,
            'why_choose_experience' => '4+ Years',
            'contact_title' => 'Let\'s Build Something Amazing Together',
            'contact_subtitle' => 'Ready to bring your digital vision to life? Contact us today!',
            'contact_phone' => '+880 1805 081012',
            'contact_email' => 'sales@wisedynamic.com.bd',
            'contact_location' => 'Bangladesh'
        ]);
    }
}
