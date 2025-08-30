<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ContactSetting;

class ContactPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContactSetting::create([
            'title' => 'Contact Us',
            'subtitle' => 'Have a question or want to work together? Reach out to us using the contact information below or fill out the form.',
            'address' => '123 Business Street, Suite 100, City, Country',
            'phone' => '+1 (555) 123-4567',
            'email' => 'info@wisedynamic.com',
            'map_embed' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.9008212777105!2d90.38426661498136!3d23.750858084589382!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8bd5c3bbd77%3A0x3d12c1a7e70a3c13!2sWise%20Dynamic!5e0!3m2!1sen!2sbd!4v1598123456789!5m2!1sen!2sbd" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
            'office_hours' => [
                ['day' => 'Monday - Friday', 'hours' => '9:00 AM - 6:00 PM'],
                ['day' => 'Saturday', 'hours' => '10:00 AM - 4:00 PM'],
                ['day' => 'Sunday', 'hours' => 'Closed'],
            ],
            'social_links' => [
                ['platform' => 'Facebook', 'url' => 'https://facebook.com/wisedynamic', 'icon' => 'fab fa-facebook'],
                ['platform' => 'Twitter', 'url' => 'https://twitter.com/wisedynamic', 'icon' => 'fab fa-twitter'],
                ['platform' => 'LinkedIn', 'url' => 'https://linkedin.com/company/wisedynamic', 'icon' => 'fab fa-linkedin'],
                ['platform' => 'Instagram', 'url' => 'https://instagram.com/wisedynamic', 'icon' => 'fab fa-instagram'],
            ],
            'form_title' => 'Send Us a Message',
            'form_subtitle' => 'Fill out the form below and we\'ll get back to you as soon as possible.',
        ]);
    }
}
