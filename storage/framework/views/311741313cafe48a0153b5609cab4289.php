
<?php $__env->startSection('title', 'Contact Us'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto px-4 py-12">
    <h1 class="nunito font-extrabold text-3xl text-gray-800 mb-4">Contact Us</h1>
    <div class="grid md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl border p-6">
            <i class="fa-solid fa-phone text-green-600 text-2xl mb-3 block"></i>
            <h3 class="font-bold text-gray-800 mb-1">Phone</h3>
            <p class="text-gray-600 text-sm">+91 98765 43210</p>
        </div>
        <div class="bg-white rounded-2xl border p-6">
            <i class="fa-solid fa-envelope text-blue-600 text-2xl mb-3 block"></i>
            <h3 class="font-bold text-gray-800 mb-1">Email</h3>
            <p class="text-gray-600 text-sm">support@farmsea.com</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border p-6">
        <h2 class="font-bold text-gray-800 mb-4">Send us a message</h2>
        <form class="space-y-4">
            <input type="text" placeholder="Your Name" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-green-500">
            <input type="email" placeholder="Your Email" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-green-500">
            <textarea rows="4" placeholder="Your Message" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none resize-none focus:border-green-500"></textarea>
            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl text-sm transition">Send Message</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views/frontend/pages/contact-us.blade.php ENDPATH**/ ?>