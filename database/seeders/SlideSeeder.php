<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Slide;

class SlideSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'title' => 'Premium Web Development',
                'subtitle' => 'At Unbeatable Prices',
                'price_text' => 'Starting from BDT 20,000/-',
                'link_url' => 'https://yourdomain.com/services/web-development',
                'image_url' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=2072&q=80',
            ],
            [
                'title' => 'Digital Marketing Excellence',
                'subtitle' => 'Boost Your Online Presence',
                'price_text' => 'From BDT 12,000/- per month',
                'link_url' => 'https://yourdomain.com/services/digital-marketing',
                'image_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=2015&q=80',
            ],
            [
                'title' => 'Complete IT Solutions',
                'subtitle' => 'From Startups to Enterprises',
                'price_text' => 'BASIS Certified Since 2020',
                'link_url' => 'https://yourdomain.com/about',
                'image_url' => 'https://images.unsplash.com/photo-1551650975-87deedd944c3?auto=format&fit=crop&w=2074&q=80',
            ],
        ];

        foreach ($slides as $idx => $s) {
            $exists = Slide::where('title', $s['title'])->exists();
            if ($exists) continue;
            Slide::create([
                'title' => $s['title'],
                'subtitle' => $s['subtitle'],
                'price_text' => $s['price_text'],
                'link_url' => $s['link_url'],
                'image_source' => 'url',
                'image_url' => $s['image_url'],
                'position' => $idx,
                'active' => true,
            ]);
        }
    }
}
