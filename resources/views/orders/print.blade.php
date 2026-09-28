<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - #{{ $order->order_number }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #333;
            background-color: #f8fafc;
            padding: 30px 15px;
            font-size: 14px;
            line-height: 1.5;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 24px;
            margin-bottom: 24px;
        }
        .company-info .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }
        .company-info .logo-img {
            max-height: 50px;
            max-width: 180px;
            object-fit: contain;
        }
        .company-info h1 {
            font-size: 26px;
            color: #0f172a;
            font-weight: 700;
        }
        .company-info p {
            color: #64748b;
            font-size: 13px;
        }
        .invoice-meta {
            text-align: right;
        }
        .invoice-meta h2 {
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #2563eb;
            margin-bottom: 6px;
        }
        .invoice-meta p {
            color: #64748b;
            font-size: 13px;
            margin-bottom: 2px;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 4px;
            text-transform: uppercase;
            margin-top: 6px;
        }
        .badge-paid {
            background-color: #dcfce7;
            color: #15803d;
        }
        .badge-pending {
            background-color: #fef9c3;
            color: #a16207;
        }
        .badge-failed {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .address-section {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 30px;
        }
        .address-card {
            flex: 1;
            background: #f8fafc;
            padding: 16px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }
        .address-card h3 {
            font-size: 13px;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .address-card strong {
            display: block;
            font-size: 14px;
            color: #1e293b;
            margin-bottom: 4px;
        }
        .address-card p {
            color: #475569;
            font-size: 13px;
            line-height: 1.4;
        }
        table.invoice-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.invoice-items th {
            background: #f1f5f9;
            color: #475569;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px;
            border-bottom: 2px solid #cbd5e1;
        }
        table.invoice-items td {
            padding: 14px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
            color: #334155;
        }
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .item-title {
            font-weight: 600;
            color: #1e293b;
        }
        .item-variant {
            font-size: 12px;
            color: #64748b;
        }
        .summary-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }
        .summary-table {
            width: 320px;
            border-collapse: collapse;
        }
        .summary-table tr td {
            padding: 6px 12px;
            font-size: 13px;
            color: #475569;
        }
        .summary-table tr.total-row td {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            border-top: 2px solid #cbd5e1;
            padding-top: 12px;
        }
        .footer-note {
            text-align: center;
            padding-top: 24px;
            border-top: 1px solid #e2e8f0;
            color: #94a3b8;
            font-size: 12px;
        }
        .no-print-toolbar {
            max-width: 800px;
            margin: 0 auto 20px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
        }
        .btn:hover {
            background: #1d4ed8;
        }
        .btn-secondary {
            background: #64748b;
        }
        .btn-secondary:hover {
            background: #475569;
        }

        @media print {
            body {
                background-color: #ffffff;
                padding: 0;
            }
            .invoice-container {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            .no-print-toolbar {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="no-print-toolbar">
    <button onclick="window.history.back()" class="btn btn-secondary">
        &larr; Back
    </button>
    <button onclick="window.print()" class="btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
            <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v4a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
        </svg>
        Print Invoice
    </button>
</div>

<div class="invoice-container">
    <div class="invoice-header">
        <div class="company-info">
            <div class="logo-container">
                <h1 style="color: #000000; font-size: 26px; font-weight: 700; margin: 0;">Acadevo</h1>
            </div>
            <p><strong>Acadevo</strong></p>
            <p>Email: support@acadevo.com</p>
        </div>

        <div class="invoice-meta">
            <h2>INVOICE</h2>
            <p><strong>Invoice #:</strong> {{ $order->order_number }}</p>
            <p><strong>Date:</strong> {{ $order->placed_at ? $order->placed_at->format('M d, Y') : $order->created_at->format('M d, Y') }}</p>
            <p><strong>Payment Method:</strong> {{ strtoupper($order->payment_method ?? 'N/A') }}</p>
            
            @php
                $status = strtolower($order->payment_status ?? 'pending');
                $badgeClass = match($status) {
                    'paid' => 'badge-paid',
                    'failed', 'cancelled' => 'badge-failed',
                    default => 'badge-pending',
                };
            @endphp
            <span class="badge {{ $badgeClass }}">{{ ucfirst($order->payment_status ?? 'Pending') }}</span>
        </div>
    </div>

    <div class="address-section">
        <div class="address-card">
            <h3>Billed To:</h3>
            @if(is_array($order->billing_address))
                <strong>{{ $order->billing_address['name'] ?? $order->customer->name ?? 'Customer' }}</strong>
                <p>{{ $order->billing_address['address'] ?? '' }}</p>
                <p>{{ $order->billing_address['city'] ?? '' }}{{ !empty($order->billing_address['pincode']) ? ' - ' . $order->billing_address['pincode'] : '' }}</p>
                <p>{{ $order->billing_address['country'] ?? '' }}</p>
                @if(!empty($order->billing_address['phone']))
                    <p>Phone: {{ $order->billing_address['phone'] }}</p>
                @endif
                @if(!empty($order->billing_address['email']))
                    <p>Email: {{ $order->billing_address['email'] }}</p>
                @endif
            @else
                <strong>{{ $order->customer->name ?? 'Customer' }}</strong>
                <p>{{ $order->customer->email ?? '' }}</p>
            @endif
        </div>

        <div class="address-card">
            <h3>Shipped To:</h3>
            @if(is_array($order->shipping_address))
                <strong>{{ $order->shipping_address['name'] ?? $order->customer->name ?? 'Customer' }}</strong>
                <p>{{ $order->shipping_address['address'] ?? '' }}</p>
                <p>{{ $order->shipping_address['city'] ?? '' }}{{ !empty($order->shipping_address['pincode']) ? ' - ' . $order->shipping_address['pincode'] : '' }}</p>
                <p>{{ $order->shipping_address['country'] ?? '' }}</p>
                @if(!empty($order->shipping_address['phone']))
                    <p>Phone: {{ $order->shipping_address['phone'] }}</p>
                @endif
            @else
                <p>Same as Billing Address</p>
            @endif
        </div>
    </div>

    <table class="invoice-items">
        <thead>
            <tr>
                <th class="text-left" style="width: 5%;">#</th>
                <th class="text-left" style="width: 50%;">Item & Description</th>
                <th class="text-center" style="width: 15%;">Qty</th>
                <th class="text-right" style="width: 15%;">Unit Price</th>
                <th class="text-right" style="width: 15%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($order->items as $index => $item)
                <tr>
                    <td class="text-left">{{ $index + 1 }}</td>
                    <td class="text-left">
                        <div class="item-title">{{ $item->title ?? $item->product->name ?? 'Product Item' }}</div>
                        @if(!empty($item->variant_label))
                            <div class="item-variant">Variant: {{ $item->variant_label }}</div>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">₹{{ number_format($item->price, 2) }}</td>
                    <td class="text-right">₹{{ number_format($item->total ?? ($item->price * $item->quantity), 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px; color: #94a3b8;">No items found in this order.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary-section">
        <table class="summary-table">
            <tr>
                <td class="text-left">Subtotal:</td>
                <td class="text-right">₹{{ number_format($order->subtotal ?? 0, 2) }}</td>
            </tr>
            @if(($order->discount_total ?? 0) > 0 || ($order->coupon_discount ?? 0) > 0)
                <tr>
                    <td class="text-left">Discount @if($order->coupon_code) ({{ $order->coupon_code }}) @endif:</td>
                    <td class="text-right">-₹{{ number_format($order->discount_total ?? $order->coupon_discount, 2) }}</td>
                </tr>
            @endif
            @if(($order->tax_total ?? 0) > 0)
                <tr>
                    <td class="text-left">Tax:</td>
                    <td class="text-right">₹{{ number_format($order->tax_total, 2) }}</td>
                </tr>
            @endif
            @if(($order->shipping_total ?? 0) > 0)
                <tr>
                    <td class="text-left">Shipping:</td>
                    <td class="text-right">₹{{ number_format($order->shipping_total, 2) }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td class="text-left">Total:</td>
                <td class="text-right">₹{{ number_format($order->total, 2) }}</td>
            </tr>
        </table>
    </div>

    @if(!empty($order->notes))
        <div style="margin-bottom: 24px; padding: 12px 16px; background: #fffbeb; border-left: 4px solid #f59e0b; border-radius: 4px;">
            <strong style="color: #92400e; font-size: 13px;">Order Notes:</strong>
            <p style="color: #78350f; font-size: 13px; margin-top: 4px;">{{ $order->notes }}</p>
        </div>
    @endif

    <div class="footer-note">
        <p>Thank you for your business with {{ config('app.name', 'Acadevo') }}!</p>
        <p>If you have any questions about this invoice, please contact support.</p>
    </div>
</div>

<script>
    // Automatically open print dialog on page load if ?autoprint=1 is passed
    if (new URLSearchParams(window.location.search).get('autoprint') === '1') {
        window.addEventListener('load', () => {
            window.print();
        });
    }
</script>

</body>
</html>
