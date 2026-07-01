<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        PaymentMethod::insert([
            [
                'name' => 'Razorpay (Cards, NetBanking, UPI, Wallets)',
                'code' => 'razorpay',
                'status' => 1,
                'instructions' => 'Pay securely using Razorpay gateway.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cash on Delivery (COD)',
                'code' => 'cod',
                'status' => 1,
                'instructions' => 'Pay cash upon delivery of the product.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Direct UPI / Bank Transfer',
                'code' => 'upi',
                'status' => 0,
                'instructions' => 'Please scan the QR code or pay to our UPI ID: example@upi. Share a screenshot of your payment on WhatsApp.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
