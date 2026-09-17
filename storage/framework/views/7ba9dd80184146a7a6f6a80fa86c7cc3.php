<?php $__env->startSection('title', 'Complete Payment — Porville'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center bg-[#faf7f0] py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl border border-[#e6d3a3] shadow-[0_18px_45px_rgba(184,134,44,0.1)] p-8 text-center">

        <img src="<?php echo e($brandLogoUrl); ?>" alt="Porville" class="h-16 w-16 mx-auto mb-4 rounded-full object-cover ring-1 ring-amber-500/40">

        <h1 class="font-classic text-xl font-bold text-slate-900 mb-1">Complete your payment</h1>
        <p class="text-slate-500 text-sm mb-6">
            Order #<?php echo e($order->order_number); ?> &mdash;
            <span class="font-bold text-slate-900">&#8377;<?php echo e(number_format($order->total, 2)); ?></span>
        </p>

        
        <div id="payment-cancelled-msg" class="hidden mb-6 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3">
            Payment cancelled. You can retry or cancel your order.
        </div>

        <button id="pay-btn"
            class="w-full border-2 border-amber-500 bg-black hover:bg-neutral-900 text-white font-bold uppercase tracking-wider text-sm py-3.5 rounded-xl transition">
            Pay &#8377;<?php echo e(number_format($order->total, 2)); ?>

        </button>

        <a href="<?php echo e(route('frontend.razorpay.cancel', $order->id)); ?>"
           class="mt-4 block text-sm text-slate-400 hover:text-red-500 transition">
            Cancel order
        </a>

        
        <form id="rzp-form" action="<?php echo e(route('frontend.razorpay.callback')); ?>" method="POST" class="hidden">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="razorpay_order_id"   id="rzp_order_id">
            <input type="hidden" name="razorpay_payment_id" id="rzp_payment_id">
            <input type="hidden" name="razorpay_signature"  id="rzp_signature">
        </form>

    </div>
</div>


<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    const options = {
        key:         '<?php echo e($keyId); ?>',
        amount:      <?php echo e($razorpayPayment->amount); ?>,
        currency:    'INR',
        name:        'Porville',
        description: 'Order #<?php echo e($order->order_number); ?>',
        order_id:    '<?php echo e($razorpayPayment->razorpay_order_id); ?>',
        prefill: {
            name:    '<?php echo e(addslashes($order->shipping_address["name"] ?? "")); ?>',
            email:   '<?php echo e(addslashes($order->user->email ?? "")); ?>',
            contact: '<?php echo e(addslashes($order->shipping_address["phone"] ?? "")); ?>',
        },
        <?php if($paymentMethod === 'upi'): ?>
        
        <?php endif; ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\porville-fresh-cut\resources\views/frontend/razorpay-payment.blade.php ENDPATH**/ ?>