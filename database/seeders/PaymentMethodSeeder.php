<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        // Idempotent: keyed on `code` so re-seeding on deploy updates rather than
        // duplicating checkout options. COD ships enabled so the store can take
        // orders out of the box; Razorpay is enabled but only works once its keys
        // are set in Admin → Settings → Payment. UPI is off until configured.
        $methods = [
            [
                'code' => 'cod',
                'name' => 'Cash on Delivery (COD)',
                'status' => 1,
                'instructions' => 'Pay cash upon delivery of the product.',
            ],
            [
                'code' => 'razorpay',
                'name' => 'Razorpay (Cards, NetBanking, UPI, Wallets)',
                'status' => 1,
                'instructions' => 'Pay securely using Razorpay gateway.',
            ],
            [
                'code' => 'upi',
                'name' => 'Direct UPI / Bank Transfer',
                'status' => 0,
                'instructions' => 'Please scan the QR code or pay to our UPI ID: example@upi. Share a screenshot of your payment on WhatsApp.',
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(
                ['code' => $method['code']],
                array_merge($method, ['updated_at' => now()])
            );
        }
    }
}
