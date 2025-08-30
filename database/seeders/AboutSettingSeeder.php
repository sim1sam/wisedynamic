<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AboutSetting;

class AboutSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing records to avoid duplicates
        AboutSetting::truncate();
        
        // Create default about settings
        AboutSetting::create([
            'title' => 'About Wise Dynamic',
            'subtitle' => 'We craft high-performing digital products and growth campaigns',
            'who_we_are_content' => 'Wise Dynamic is a multidisciplinary team specializing in Website Development, UI/UX, and Digital Marketing.',
            'who_we_are_image' => 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?q=80&w=1600&auto=format&fit=crop',
            'about_items' => json_encode([
                [
                    'icon' => 'fas fa-check',
                    'title' => 'Customer-first mindset',
                    'text' => 'with transparent communication'
                ],
                [
                    'icon' => 'fas fa-check',
                    'title' => 'Modern tech stack',
                    'text' => 'and data-informed decisions'
                ],
                [
                    'icon' => 'fas fa-check',
                    'title' => 'On-time delivery',
                    'text' => 'with quality assurance'
                ]
            ]),
            'stats' => json_encode([
                [
                    'value' => '5+',
                    'label' => 'Years Experience'
                ],
                [
                    'value' => '120+',
                    'label' => 'Projects Delivered'
                ],
                [
                    'value' => '98%',
                    'label' => 'Client Satisfaction'
                ]
            ]),
            'values' => json_encode([
                [
                    'title' => 'Integrity',
                    'description' => 'We do what\'s right, not what\'s easy.'
                ],
                [
                    'title' => 'Excellence',
                    'description' => 'We sweat the details and focus on outcomes.'
                ],
                [
                    'title' => 'Progress',
                    'description' => 'We learn, iterate, and improve continuously.'
                ],
                [
                    'title' => 'Partnership',
                    'description' => 'We act as an extension of your team.'
                ]
            ]),
            'services' => json_encode([
                [
                    'title' => 'Website Development',
                    'description' => 'Fast, secure, and scalable websites built to convert.'
                ],
                [
                    'title' => 'UI/UX & Branding',
                    'description' => 'Human-centric design that elevates your brand.'
                ],
                [
                    'title' => 'Digital Marketing',
                    'description' => 'SEO, Social, and Ads to drive qualified growth.'
                ],
                [
                    'title' => 'eCommerce & Integrations',
                    'description' => 'Payments, analytics, and automations that scale.'
                ]
            ]),
            'cta_title' => 'Ready to work with a results-driven team?',
            'cta_subtitle' => 'Get a free consultation and tailored plan for your business.',
            'cta_button_text' => 'Contact Us'
        ]);
    }
}
