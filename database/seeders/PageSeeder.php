<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        // First, clean up redundant pages that have dedicated views
        Page::whereIn('slug', ['about-us', 'contact-us'])->delete();

        $pages = [
            [
                'title' => 'Privacy Policy', 
                'slug' => 'privacy-policy', 
                'content' => '<h2>Privacy Policy</h2>
                <p>Welcome to Kare Ons Herbal. We respect your privacy and are committed to protecting your personal data.</p>
                <h3>1. Information We Collect</h3>
                <p>When you visit our site or make a purchase, we collect certain information about your device and your interaction with the site.</p>
                <ul>
                    <li><strong>Order Information:</strong> Name, billing address, shipping address, payment info (securely processed via Razorpay), email, and phone number.</li>
                    <li><strong>Device Information:</strong> Web browser version, IP address, time zone, and cookie data.</li>
                </ul>
                <h3>2. How We Use Your Data</h3>
                <p>We use your data to fulfill orders, process payments, arrange shipping, provide invoices, and screen for potential fraud.</p>
                <h3>3. Data Sharing</h3>
                <p>We do not sell your personal data. We only share it with trusted third-party service providers (like our shipping and payment gateways) strictly for order fulfillment purposes.</p>'
            ],
            [
                'title' => 'Refund Policy', 
                'slug' => 'refund-policy', 
                'content' => '<h2>Refund & Return Policy</h2>
                <p>At Kare Ons Herbal, we stand behind the quality of our Ayurvedic formulations.</p>
                <h3>Returns</h3>
                <p>You may initiate a return request within <strong>7 days</strong> of receiving your order if the product is damaged, defective, or incorrect.</p>
                <ul>
                    <li>The item must be unused, sealed, and in its original packaging.</li>
                    <li>To initiate a return, please visit the "Return Requests" section in your account dashboard.</li>
                </ul>
                <h3>Refunds</h3>
                <p>Once your return is received and inspected, we will notify you of the approval or rejection of your refund. If approved, the refund will be processed to your original method of payment (via Razorpay) within 5-7 business days.</p>'
            ],
            [
                'title' => 'Shipping Policy', 
                'slug' => 'shipping-policy', 
                'content' => '<h2>Shipping & Delivery</h2>
                <p>We strive to deliver your wellness products as quickly and safely as possible.</p>
                <h3>Processing Time</h3>
                <p>All orders are processed within 1-2 business days. Orders are not shipped or delivered on Sundays or public holidays.</p>
                <h3>Shipping Rates</h3>
                <p>Shipping charges are calculated at checkout based on your delivery zone. We offer <strong>Free Shipping</strong> on orders exceeding ₹999.</p>
                <h3>Delivery Estimates</h3>
                <p>Standard delivery typically takes 3-5 business days across PAN India, depending on your location.</p>'
            ],
            [
                'title' => 'Terms & Conditions', 
                'slug' => 'terms-and-conditions', 
                'content' => '<h2>Terms of Service</h2>
                <p>By accessing or using the Kare Ons Herbal website, you agree to be bound by these Terms of Service.</p>
                <h3>1. Medical Disclaimer</h3>
                <p>The information and products on this site are based on Ayurvedic principles. They are not intended to diagnose, treat, cure, or prevent any severe medical conditions without professional medical advice.</p>
                <h3>2. Accuracy of Information</h3>
                <p>We reserve the right to update, modify, or remove product information, pricing, and availability without prior notice.</p>
                <h3>3. User Accounts</h3>
                <p>You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account.</p>'
            ],
            [
                'title' => 'FAQ', 
                'slug' => 'faq', 
                'content' => '<h2>Frequently Asked Questions</h2>
                <h3>Are your products safe?</h3>
                <p>Yes, all our products are manufactured in GMP-certified facilities and undergo rigorous quality testing to ensure absolute purity and safety.</p>
                <h3>How long does delivery take?</h3>
                <p>Standard PAN India delivery takes between 3 to 5 business days after order processing.</p>
                <h3>Do you offer Cash on Delivery (COD)?</h3>
                <p>Yes, COD is available for most pin codes. A nominal handling fee may apply.</p>
                <h3>How can I track my order?</h3>
                <p>Once your order is shipped, you will receive a tracking link via email and in your customer dashboard.</p>'
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']], 
                ['title' => $page['title'], 'content' => $page['content'], 'status' => true]
            );
        }
    }
}
