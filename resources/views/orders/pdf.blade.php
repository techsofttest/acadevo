<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1e293b;
            font-size: 12px;
            line-height: 1.4;
            padding: 30px;
            background: #ffffff;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header-table td {
            vertical-align: top;
        }
        .brand-logo {
            font-size: 26px;
            font-weight: bold;
            color: #000000;
            text-transform: none;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }
        .company-subtitle {
            font-size: 11px;
            color: #64748b;
        }
        .invoice-title {
            font-size: 22px;
            font-weight: bold;
            color: #2563eb;
            text-align: right;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .invoice-meta-text {
            text-align: right;
            font-size: 11px;
            color: #475569;
            line-height: 1.5;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 3px;
            text-transform: uppercase;
            margin-top: 4px;
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
        .address-table {
            width: 100%;
            margin-bottom: 25px;
        }
        .address-table td {
            width: 48%;
            vertical-align: top;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 12px 14px;
            border-radius: 4px;
        }
        .address-header {
            font-size: 11px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
        }
        .address-name {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 3px;
        }
        .address-line {
            font-size: 11px;
            color: #475569;
            line-height: 1.4;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 10px;
            border-top: 1px solid #cbd5e1;
            border-bottom: 2px solid #cbd5e1;
        }
        .items-table td {
            padding: 9px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
            color: #334155;
            vertical-align: top;
        }
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .item-name {
            font-weight: bold;
            color: #0f172a;
            font-size: 11px;
        }
        .item-variant {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }
        .summary-container-table {
            width: 100%;
            margin-bottom: 25px;
        }
        .summary-container-table td {
            vertical-align: top;
        }
        .summary-table {
            width: 280px;
            margin-left: auto;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 4px 8px;
            font-size: 11px;
            color: #475569;
        }
        .summary-table tr.total-row td {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            border-top: 2px solid #cbd5e1;
            padding-top: 8px;
        }
        .notes-box {
            background-color: #fffbeb;
            border-left: 3px solid #f59e0b;
            padding: 8px 12px;
            margin-bottom: 20px;
            font-size: 11px;
        }
        .footer-note {
            text-align: center;
            padding-top: 18px;
            border-top: 1px solid #e2e8f0;
            color: #94a3b8;
            font-size: 10px;
            line-height: 1.5;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="width: 55%;">
                <div class="brand-logo">Acadevo</div>
                <div class="company-subtitle">Email: support@acadevo.com</div>
            </td>
            <td style="width: 45%;">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-meta-text">
                    <strong>Invoice #:</strong> {{ $order->order_number }}<br>
                    <strong>Date:</strong> {{ $order->placed_at ? $order->placed_at->format('M d, Y') : $order->created_at->format('M d, Y') }}<br>
                    <strong>Payment Method:</strong> {{ strtoupper($order->payment_method ?? 'N/A') }}<br>
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
            </td>
        </tr>
    </table>

    <table class="address-table">
        <tr>
            <td>
                <div class="address-header">Billed To</div>
                @if(is_array($order->billing_address))
                    <div class="address-name">{{ $order->billing_address['name'] ?? $order->customer->name ?? 'Customer' }}</div>
                    @if(!empty($order->billing_address['address']))
                        <div class="address-line">{{ $order->billing_address['address'] }}</div>
                    @endif
                    @if(!empty($order->billing_address['city']) || !empty($order->billing_address['pincode']))
                        <div class="address-line">{{ $order->billing_address['city'] ?? '' }}{{ !empty($order->billing_address['pincode']) ? ' - ' . $order->billing_address['pincode'] : '' }}</div>
                    @endif
                    @if(!empty($order->billing_address['country']))
                        <div class="address-line">{{ $order->billing_address['country'] }}</div>
                    @endif
                    @if(!empty($order->billing_address['phone']))
                        <div class="address-line">Phone: {{ $order->billing_address['phone'] }}</div>
                    @endif
                    @if(!empty($order->billing_address['email']))
                        <div class="address-line">Email: {{ $order->billing_address['email'] }}</div>
                    @endif
                @else
                    <div class="address-name">{{ $order->customer->name ?? 'Customer' }}</div>
                    <div class="address-line">{{ $order->customer->email ?? '' }}</div>
                @endif
            </td>
            <td style="width: 4%;"></td>
            <td>
                <div class="address-header">Shipped To</div>
                @if(is_array($order->shipping_address))
                    <div class="address-name">{{ $order->shipping_address['name'] ?? $order->customer->name ?? 'Customer' }}</div>
                    @if(!empty($order->shipping_address['address']))
                        <div class="address-line">{{ $order->shipping_address['address'] }}</div>
                    @endif
                    @if(!empty($order->shipping_address['city']) || !empty($order->shipping_address['pincode']))
                        <div class="address-line">{{ $order->shipping_address['city'] ?? '' }}{{ !empty($order->shipping_address['pincode']) ? ' - ' . $order->shipping_address['pincode'] : '' }}</div>
                    @endif
                    @if(!empty($order->shipping_address['country']))
                        <div class="address-line">{{ $order->shipping_address['country'] }}</div>
                    @endif
                    @if(!empty($order->shipping_address['phone']))
                        <div class="address-line">Phone: {{ $order->shipping_address['phone'] }}</div>
                    @endif
                @else
                    <div class="address-line">Same as Billing Address</div>
                @endif
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th class="text-left" style="width: 6%;">#</th>
                <th class="text-left" style="width: 48%;">Item & Description</th>
                <th class="text-center" style="width: 12%;">Qty</th>
                <th class="text-right" style="width: 17%;">Unit Price</th>
                <th class="text-right" style="width: 17%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($order->items as $index => $item)
                <tr>
                    <td class="text-left">{{ $index + 1 }}</td>
                    <td class="text-left">
                        <div class="item-name">{{ $item->title ?? $item->product->name ?? 'Product Item' }}</div>
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
                    <td colspan="5" class="text-center" style="padding: 15px; color: #94a3b8;">No items found in this order.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="summary-container-table">
        <tr>
            <td style="width: 45%;">
                @if(!empty($order->notes))
                    <div class="notes-box">
                        <strong style="color: #92400e;">Order Notes:</strong><br>
                        <span style="color: #78350f;">{{ $order->notes }}</span>
                    </div>
                @endif
            </td>
            <td style="width: 55%;">
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
            </td>
        </tr>
    </table>

    <div class="footer-note">
        <p>Thank you for your business with Acadevo!</p>
        <p>If you have any questions about this invoice, please contact support@acadevo.com</p>
    </div>

</body>
</html>
