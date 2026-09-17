@extends('frontend.layouts.app')

@section('title', 'Complete Payment — Porville')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#faf7f0] py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl border border-[#e6d3a3] shadow-[0_18px_45px_rgba(184,134,44,0.1)] p-8 text-center">

        <img src="{{ $brandLogoUrl }}" alt="Porville" class="h-16 w-16 mx-auto mb-4 rounded-full object-cover ring-1 ring-amber-500/40">

        <h1 class="font-classic text-xl font-bold text-slate-900 mb-1">Complete your payment</h1>
        <p class="text-slate-500 text-sm mb-6">
            Order #{{ $order->order_number }} &mdash;
            <span class="font-bold text-slate-900">&#8377;{{ number_format($order->total, 2) }}</span>
        </p>

        {{-- Post-dismiss UI (hidden until JS triggers it on modal close) --}}
        <div id="payment-cancelled-msg" class="hidden mb-6 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3">
            Payment cancelled. You can retry or cancel your order.
        </div>

        <button id="pay-btn"
            class="w-full border-2 border-amber-500 bg-black hover:bg-neutral-900 text-white font-bold uppercase tracking-wider text-sm py-3.5 rounded-xl transition">
            Pay &#8377;{{ number_format($order->total, 2) }}
        </button>

        <a href="{{ route('frontend.razorpay.cancel', $order->id) }}"
           class="mt-4 block text-sm text-slate-400 hover:text-red-500 transition">
            Cancel order
        </a>

        {{-- Hidden form submitted by Razorpay JS handler on successful payment --}}
        <form id="rzp-form" action="{{ route('frontend.razorpay.callback') }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="razorpay_order_id"   id="rzp_order_id">
            <input type="hidden" name="razorpay_payment_id" id="rzp_payment_id">
            <input type="hidden" name="razorpay_signature"  id="rzp_signature">
        </form>

    </div>
</div>

{{-- Razorpay checkout script (inline because the layout has no @stack('scripts')) --}}
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    const options = {
        key:         '{{ $keyId }}',
        amount:      {{ $razorpayPayment->amount }},
        currency:    'INR',
        name:        'Porville',
        description: 'Order #{{ $order->order_number }}',
        order_id:    '{{ $razorpayPayment->razorpay_order_id }}',
        prefill: {
            name:    '{{ addslashes($order->shipping_address["name"] ?? "") }}',
            email:   '{{ addslashes($order->user->email ?? "") }}',
            contact: '{{ addslashes($order->shipping_address["phone"] ?? "") }}',
        },
        @if($paymentMethod === 'upi')
        {{-- UPI: remove method restrictions, let Razorpay show all UPI options --}}
        @endif
        theme: { color: '#b8862c' },
        handler: function (response) {
            document.getElementById('rzp_order_id').value   = response.razorpay_order_id;
            document.getElementById('rzp_payment_id').value = response.razorpay_payment_id;
            document.getElementById('rzp_signature').value  = response.razorpay_signature;
            document.getElementById('rzp-form').submit();
        },
        modal: {
            ondismiss: function () {
                document.getElementById('payment-cancelled-msg').classList.remove('hidden');
                document.getElementById('pay-btn').textContent = 'Retry Payment';
            }
        }
    };

    const rzp = new Razorpay(options);

    // Auto-open modal as soon as the DOM is ready
    document.addEventListener('DOMContentLoaded', function () {
        rzp.open();
    });

    document.getElementById('pay-btn').addEventListener('click', function () {
        rzp.open();
    });
</script>
@endsection
