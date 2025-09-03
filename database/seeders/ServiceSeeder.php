<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\ServiceCategory;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get category IDs for reference
        $categories = ServiceCategory::pluck('id', 'slug')->toArray();
        
        $services = [
            // Website Development Services
            [
                'title' => 'Custom Website Development',
                'slug' => 'custom-website-development',
                'short_description' => 'Tailored websites built from scratch to meet your specific business requirements.',
                'description' => '<p>Our custom website development service delivers unique, high-performing websites designed specifically for your business needs. We focus on creating solutions that are not only visually appealing but also functionally robust.</p>
                <p>Every project begins with a thorough understanding of your business goals, target audience, and technical requirements. Our development team uses modern frameworks and best practices to ensure your website is fast, secure, and scalable.</p>
                <p>We pride ourselves on clean code, responsive design, and SEO-friendly architecture that helps your business stand out in the digital landscape.</p>',
                'price' => 50000,
                'price_unit' => 'project',
                'image' => null,
                'service_category_id' => $categories['website-development'],
                'status' => true,
                'featured' => true,
            ],
            [
                'title' => 'WordPress Website Development',
                'slug' => 'wordpress-website-development',
                'short_description' => 'Professional WordPress websites with custom themes and functionality.',
                'description' => '<p>Our WordPress development service combines the flexibility of the world\'s most popular CMS with custom design and functionality tailored to your business.</p>
                <p>We create custom themes that perfectly represent your brand identity, implement essential plugins for functionality, and optimize your site for performance and search engines.</p>
                <p>Our WordPress solutions include:</p>
                <ul>
                    <li>Custom theme development</li>
                    <li>Plugin customization</li>
                    <li>E-commerce integration</li>
                    <li>Performance optimization</li>
                    <li>Security hardening</li>
                </ul>',
                'price' => 35000,
                'price_unit' => 'project',
                'image' => null,
                'service_category_id' => $categories['website-development'],
                'status' => true,
                'featured' => false,
            ],
            [
                'title' => 'Corporate Website Package',
                'slug' => 'corporate-website-package',
                'short_description' => 'Complete corporate website solution with professional design and content management.',
                'description' => '<p>Our corporate website package provides everything your business needs to establish a professional online presence. We combine sleek design with powerful functionality to create a website that represents your brand effectively.</p>
                <p>This comprehensive package includes:</p>
                <ul>
                    <li>Professional responsive design</li>
                    <li>Up to 10 custom pages</li>
                    <li>Content Management System</li>
                    <li>Contact forms and maps integration</li>
                    <li>Social media integration</li>
                    <li>Basic SEO setup</li>
                    <li>Analytics installation</li>
                    <li>1 hour training session</li>
                </ul>',
                'price' => 45000,
                'price_unit' => 'package',
                'image' => null,
                'service_category_id' => $categories['website-development'],
                'status' => true,
                'featured' => true,
            ],
            
            // Web App & E-commerce Services
            [
                'title' => 'E-commerce Website Development',
                'slug' => 'ecommerce-website-development',
                'short_description' => 'Full-featured online stores with secure payment processing and inventory management.',
                'description' => '<p>Transform your business with our comprehensive e-commerce development solutions. We build online stores that not only look great but drive conversions and sales.</p>
                <p>Our e-commerce websites come with all the features you need to successfully sell online:</p>
                <ul>
                    <li>User-friendly product catalogs</li>
                    <li>Secure payment gateway integration</li>
                    <li>Inventory management systems</li>
                    <li>Order processing workflows</li>
                    <li>Customer account management</li>
                    <li>Mobile-responsive shopping experience</li>
                </ul>
                <p>We work with platforms like WooCommerce, Shopify, and custom solutions to create the perfect online store for your business.</p>',
                'price' => 75000,
                'price_unit' => 'project',
                'image' => null,
                'service_category_id' => $categories['web-app-ecommerce'],
                'status' => true,
                'featured' => true,
            ],
            [
                'title' => 'Custom Web Application',
                'slug' => 'custom-web-application',
                'short_description' => 'Tailored web applications to streamline your business processes and improve efficiency.',
                'description' => '<p>Our custom web application development service creates powerful, scalable solutions designed specifically for your business needs. Whether you need an internal tool, customer portal, or complex business system, we deliver applications that solve real problems.</p>
                <p>Our web application development includes:</p>
                <ul>
                    <li>Requirements analysis and planning</li>
                    <li>User experience design</li>
                    <li>Frontend and backend development</li>
                    <li>Database design and optimization</li>
                    <li>API integrations</li>
                    <li>Security implementation</li>
                    <li>Testing and quality assurance</li>
                </ul>
                <p>We use modern frameworks and technologies to ensure your application is robust, secure, and maintainable.</p>',
                'price' => 120000,
                'price_unit' => 'project',
                'image' => null,
                'service_category_id' => $categories['web-app-ecommerce'],
                'status' => true,
                'featured' => false,
            ],
            
            // Payment Gateway Services
            [
                'title' => 'SSL Commerz Integration',
                'slug' => 'ssl-commerz-integration',
                'short_description' => 'Seamless integration of SSL Commerz payment gateway for secure online transactions.',
                'description' => '<p>Our SSL Commerz integration service provides your business with a secure, reliable payment solution for your website or application. As an official SSL Commerz partner, we ensure smooth implementation and optimal configuration.</p>
                <p>Our integration service includes:</p>
                <ul>
                    <li>SSL Commerz account setup assistance</li>
                    <li>Payment gateway integration with your website</li>
                    <li>Multiple payment method configuration</li>
                    <li>Transaction testing and verification</li>
                    <li>Security compliance implementation</li>
                    <li>Documentation and training</li>
                </ul>
                <p>Accept payments securely and provide your customers with a seamless checkout experience.</p>',
                'price' => 15000,
                'price_unit' => 'integration',
                'image' => null,
                'service_category_id' => $categories['payment-gateway'],
                'status' => true,
                'featured' => true,
            ],
            [
                'title' => 'Multiple Payment Gateway Setup',
                'slug' => 'multiple-payment-gateway-setup',
                'short_description' => 'Integration of multiple payment options to maximize customer convenience.',
                'description' => '<p>Our multiple payment gateway setup service helps you provide your customers with various payment options, increasing conversion rates and customer satisfaction. We integrate and configure multiple payment solutions into a single, seamless checkout experience.</p>
                <p>This service includes:</p>
                <ul>
                    <li>Integration of SSL Commerz as primary gateway</li>
                    <li>Additional gateway integrations (bKash, Nagad, etc.)</li>
                    <li>Payment method selection interface</li>
                    <li>Unified transaction reporting</li>
                    <li>Security measures for all payment methods</li>
                    <li>Testing across all payment options</li>
                </ul>
                <p>Give your customers the flexibility to pay using their preferred method while maintaining a consistent checkout experience.</p>',
                'price' => 25000,
                'price_unit' => 'package',
                'image' => null,
                'service_category_id' => $categories['payment-gateway'],
                'status' => true,
                'featured' => false,
            ],
            
            // Digital Marketing Services
            [
                'title' => 'Search Engine Optimization (SEO)',
                'slug' => 'search-engine-optimization',
                'short_description' => 'Comprehensive SEO strategies to improve your website\'s visibility in search engine results.',
                'description' => '<p>Our SEO service helps your business gain visibility and traffic through organic search results. We implement proven strategies to improve your rankings for relevant keywords and drive qualified traffic to your website.</p>
                <p>Our comprehensive SEO approach includes:</p>
                <ul>
                    <li>Technical SEO audit and optimization</li>
                    <li>Keyword research and strategy</li>
                    <li>On-page optimization</li>
                    <li>Content strategy and creation</li>
                    <li>Link building and off-page SEO</li>
                    <li>Local SEO for businesses serving specific areas</li>
                    <li>Regular performance reporting</li>
                </ul>
                <p>We focus on sustainable, white-hat SEO practices that build long-term search visibility and protect your site from algorithm penalties.</p>',
                'price' => 15000,
                'price_unit' => 'month',
                'image' => null,
                'service_category_id' => $categories['digital-marketing'],
                'status' => true,
                'featured' => true,
            ],
            [
                'title' => 'Google Ads Management',
                'slug' => 'google-ads-management',
                'short_description' => 'Strategic Google Ads campaigns to drive targeted traffic and increase conversions.',
                'description' => '<p>Our Google Ads management service helps you maximize your advertising budget with carefully crafted campaigns that target your ideal customers. We create, optimize, and manage ads that drive qualified traffic and conversions.</p>
                <p>Our Google Ads service includes:</p>
                <ul>
                    <li>Account setup and structure optimization</li>
                    <li>Keyword research and selection</li>
                    <li>Ad copywriting and design</li>
                    <li>Landing page optimization recommendations</li>
                    <li>Bid management and budget optimization</li>
                    <li>A/B testing of ad variations</li>
                    <li>Performance tracking and reporting</li>
                </ul>
                <p>Get immediate visibility and drive targeted traffic to your website with expertly managed Google Ads campaigns.</p>',
                'price' => 12000,
                'price_unit' => 'month',
                'image' => null,
                'service_category_id' => $categories['digital-marketing'],
                'status' => true,
                'featured' => false,
            ],
            
            // Background Music Services
            [
                'title' => 'Custom Background Music',
                'slug' => 'custom-background-music',
                'short_description' => 'Unique, copyright-free music compositions tailored to your brand identity.',
                'description' => '<p>Our custom background music service provides your business with unique audio that enhances your brand identity and creates a memorable atmosphere for your customers. All compositions are copyright-free, allowing unlimited use across all your channels.</p>
                <p>This service includes:</p>
                <ul>
                    <li>Brand identity analysis</li>
                    <li>Music style consultation</li>
                    <li>Custom composition creation</li>
                    <li>Multiple variations and lengths</li>
                    <li>High-quality audio files in various formats</li>
                    <li>Full usage rights</li>
                </ul>
                <p>Perfect for websites, videos, physical locations, presentations, and any other brand touchpoints that could benefit from distinctive audio branding.</p>',
                'price' => 20000,
                'price_unit' => 'package',
                'image' => null,
                'service_category_id' => $categories['background-music'],
                'status' => true,
                'featured' => true,
            ],
            [
                'title' => 'Audio Branding Package',
                'slug' => 'audio-branding-package',
                'short_description' => 'Complete audio identity system including logo sound, background music, and notification tones.',
                'description' => '<p>Our audio branding package creates a comprehensive sound identity for your brand. This complete solution includes all the audio elements needed to create a consistent and recognizable audio presence across all customer touchpoints.</p>
                <p>The package includes:</p>
                <ul>
                    <li>Sonic logo (2-5 second audio signature)</li>
                    <li>Custom background music (3 variations)</li>
                    <li>UI sound effects for website/app</li>
                    <li>On-hold phone music</li>
                    <li>Notification sounds</li>
                    <li>Audio style guide</li>
                    <li>All files in multiple formats</li>
                </ul>
                <p>Establish a distinctive audio identity that strengthens brand recognition and enhances customer experience.</p>',
                'price' => 35000,
                'price_unit' => 'package',
                'image' => null,
                'service_category_id' => $categories['background-music'],
                'status' => true,
                'featured' => false,
            ],
            
            // Website Management Services
            [
                'title' => 'Website Maintenance Package',
                'slug' => 'website-maintenance-package',
                'short_description' => 'Comprehensive website maintenance to keep your site secure, updated, and performing optimally.',
                'description' => '<p>Our website maintenance package ensures your website remains secure, up-to-date, and performing at its best. We handle all the technical aspects of website maintenance so you can focus on your business.</p>
                <p>This monthly service includes:</p>
                <ul>
                    <li>Regular software updates</li>
                    <li>Security monitoring and patches</li>
                    <li>Performance optimization</li>
                    <li>Regular backups</li>
                    <li>Uptime monitoring</li>
                    <li>Minor content updates (up to 2 hours)</li>
                    <li>Monthly performance report</li>
                </ul>
                <p>Prevent security vulnerabilities, avoid downtime, and maintain optimal website performance with our proactive maintenance service.</p>',
                'price' => 5000,
                'price_unit' => 'month',
                'image' => null,
                'service_category_id' => $categories['website-management'],
                'status' => true,
                'featured' => true,
            ],
            [
                'title' => 'Content Management Service',
                'slug' => 'content-management-service',
                'short_description' => 'Regular content updates and management to keep your website fresh and engaging.',
                'description' => '<p>Our content management service keeps your website up-to-date with fresh, engaging content. We handle regular updates to ensure your site remains relevant to both visitors and search engines.</p>
                <p>This service includes:</p>
                <ul>
                    <li>Regular content updates (up to 5 hours monthly)</li>
                    <li>New page creation as needed</li>
                    <li>Image sourcing and optimization</li>
                    <li>Content proofreading and formatting</li>
                    <li>Basic SEO optimization for new content</li>
                    <li>Content calendar management</li>
                    <li>Monthly content performance report</li>
                </ul>
                <p>Keep your website fresh and engaging without the hassle of managing updates yourself.</p>',
                'price' => 8000,
                'price_unit' => 'month',
                'image' => null,
                'service_category_id' => $categories['website-management'],
                'status' => true,
                'featured' => false,
            ],
        ];
        
        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
