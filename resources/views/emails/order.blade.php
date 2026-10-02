{{-- Order email for the customer (placed + every status change) and the admin copy. --}}
@extends('emails.layout')
@section('title', $heading . ' - Porville')

@section('content')
@php
    $money = fn ($value) => '₹' . number_format((float) $value, 2);
    $address = $order->shipping_address ?? [];
    $addressLine = collect([$address['address'] ?? null, $address['sector'] ?? null, $address['city'] ?? null])
        ->filter()->join(', ') . (! empty($address['pincode']) ? ' - ' . $address['pincode'] : '');
@endphp
<tr>
  <td align="center" style="padding:38px 40px 20px;">
    <div style="font-size:42px;line-height:1;margin-bottom:10px;color:#9a721b;">{{ $icon ?? '✓' }}</div>
    <h1 style="margin:0;color:#211a13;font-size:27px;">{{ $heading }}</h1>
    <p style="margin:12px 0 0;color:#777;font-size:14px;line-height:22px;">{{ $intro }}</p>
  </td>
</tr>

<tr>
  <td style="padding:15px 45px;">
    <p style="font-size:15px;color:#333;margin:0 0 8px;">Hello <strong>{{ $greetingName }}</strong>,</p>
    <p style="font-size:14px;color:#666;line-height:22px;margin:0;">{{ $messageText }}</p>
  </td>
</tr>

<!-- ORDER SUMMARY -->
<tr>
  <td style="padding:20px 45px;">
    <div style="font-size:17px;font-weight:bold;color:#211a13;margin-bottom:12px;">Order Summary</div>
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#faf5e8;border:1px solid #ead9ad;border-radius:9px;">
      <tr>
        <td style="padding:20px;font-size:14px;color:#555;line-height:27px;">
          <table width="100%" cellpadding="0" cellspacing="0">
            <tr><td style="color:#211a13;font-weight:bold;">Order Number:</td><td align="right">{{ $order->order_number ?: '#' . $order->id }}</td></tr>
            <tr><td style="color:#211a13;font-weight:bold;">Order Status:</td><td align="right" style="color:#9a721b;font-weight:bold;">{{ $order->status_label }}</td></tr>
            <tr><td style="color:#211a13;font-weight:bold;">Payment Method:</td><td align="right">{{ strtoupper($order->payment_method ?? 'COD') }}</td></tr>
            <tr><td style="color:#211a13;font-weight:bold;">Delivery Slot:</td><td align="right">{{ $order->delivery_slot_label ?? $order->delivery_slot ?? 'Standard Slot' }}</td></tr>
            @if($order->delivery_date)
                <tr><td style="color:#211a13;font-weight:bold;">Delivery Date:</td><td align="right">{{ $order->delivery_date->format('d M Y') }}</td></tr>
            @endif
          </table>
        </td>
      </tr>

      @if($order->items->isNotEmpty())
        <tr>
          <td style="border-top:1px solid #e3d2a5;padding:14px 20px;">
            <table width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;color:#555;line-height:22px;">
              @foreach($order->items as $item)
                <tr>
                  <td style="padding:3px 0;">{{ $item->product->name ?? 'Product' }}@if($item->variant_label) <span style="color:#999;">({{ $item->variant_label }})</span>@endif &times; {{ $item->quantity }}</td>
                  <td align="right" style="padding:3px 0;white-space:nowrap;">{{ $money($item->subtotal) }}</td>
                </tr>
              @endforeach
            </table>
          </td>
        </tr>
      @endif

      <tr>
        <td style="border-top:1px solid #e3d2a5;padding:15px 20px;font-size:16px;color:#211a13;font-weight:bold;">
          <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td style="font-size:16px;color:#211a13;font-weight:bold;">Total Amount</td>
              <td align="right" style="color:#9a721b;font-size:18px;font-weight:bold;">{{ $money($order->total) }}</td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>

<!-- DELIVERY DETAILS -->
<tr>
  <td style="padding:5px 45px 25px;">
    <div style="font-size:17px;font-weight:bold;color:#211a13;margin-bottom:12px;">Delivery Details</div>
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#fafafa;border-left:4px solid #d5aa3d;border-radius:5px;">
      <tr>
        <td style="padding:16px 18px;font-size:14px;color:#555;line-height:22px;">
          <strong style="color:#211a13;">Delivery Address</strong><br>
          @if(! empty($address['name'])){{ $address['name'] }}@if(! empty($address['phone'])) &middot; {{ $address['phone'] }}@endif<br>@endif
          {{ $addressLine ?: 'Address not available' }}
          @if(! empty($deliveryPartner))
            <br><strong style="color:#211a13;">Delivery Partner:</strong> {{ $deliveryPartner }}
          @endif
        </td>
      </tr>
    </table>
  </td>
</tr>

<!-- CTA -->
<tr>
  <td align="center" style="padding:5px 45px 35px;">
    <table cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" style="background:#1c1712;border-radius:7px;">
          <a href="{{ $buttonUrl }}" style="display:inline-block;padding:14px 30px;color:#e6bd55;text-decoration:none;font-size:14px;font-weight:bold;">{{ $buttonText }}</a>
        </td>
      </tr>
    </table>

    @if(! empty($reviewUrl))
      <p style="margin:22px 0 0;color:#555;font-size:13px;line-height:20px;">
        We would love your feedback! <a href="{{ $reviewUrl }}" style="color:#9a721b;font-weight:bold;">Review your order</a> (link valid for 30 days).
      </p>
    @endif

    <p style="margin:25px 0 0;color:#777;font-size:13px;line-height:20px;">
      Have questions about your order?<br>Our Porville support team is here to help.
    </p>
  </td>
</tr>
@endsection
