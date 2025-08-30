<?php

namespace Database\Seeders;

use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;

class WebsiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WebsiteSetting::create([
            'site_name' => 'Wise Dynamic',
            'logo_alt_text' => 'Wise Dynamic Logo',
            'show_site_name_with_logo' => true
        ]);
    }
}
