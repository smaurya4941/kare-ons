<x-mail::message>
# ⚠️ {{ setting('site_name') }} {{ $product->stock_quantity <= 0 ? 'Out of Stock' : 'Low Stock' }} Alert

<x-mail::panel>
**Product:** {{ $product->name }}<br>
**SKU:** {{ $product->sku }}<br>
**Current Stock:** {{ $product->stock_quantity }}<br>
**Minimum Stock:** {{ $threshold }}
</x-mail::panel>

Please restock this product soon to avoid missed sales.

<x-mail::button :url="route('admin.inventory.index')">
View Inventory →
</x-mail::button>

{{ setting('site_name') }} Admin Notifications
</x-mail::message>
