<?php

namespace App\Mail;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminLowStockNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Product $product;
    public int $threshold;

    public function __construct(Product $product, int $threshold)
    {
        $this->product = $product;
        $this->threshold = $threshold;
    }

    public function envelope(): Envelope
    {
        $prefix = $this->product->stock_quantity <= 0 ? 'Out of Stock' : 'Low Stock Alert';

        return new Envelope(
            subject: "⚠️ {$prefix}: {$this->product->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.admin.low_stock',
        );
    }
}
