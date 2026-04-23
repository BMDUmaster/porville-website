
<?php $__env->startSection('title', $product->name); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto px-4 py-8">
    
    <nav class="flex items-center gap-2 text-xs text-gray-400 mb-6">
        <a href="<?php echo e(route('frontend.home')); ?>" class="hover:text-blue-600">Home</a>
        <i class="fa-solid fa-chevron-right text-[9px]"></i>
        <a href="<?php echo e(route('frontend.products')); ?>" class="hover:text-blue-600">Products</a>
        <i class="fa-solid fa-chevron-right text-[9px]"></i>
        <span class="text-gray-700 font-medium"><?php echo e($product->name); ?></span>
    </nav>

    <div class="grid md:grid-cols-2 gap-10 mb-12">
        
        <div>
            <div class="aspect-square rounded-2xl overflow-hidden bg-gray-50 border mb-3">
                <?php if($product->images && count($product->images)): ?>
                    <img src="<?php echo e(asset('storage/'.$product->images[0])); ?>" class="w-full h-full object-cover" id="mainImage">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-8xl">🥩</div>
                <?php endif; ?>
            </div>
            <?php if($product->images && count($product->images) > 1): ?>
            <div class="flex gap-2">
                <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button onclick="document.getElementById('mainImage').src='<?php echo e(asset('storage/'.$img)); ?>'"
                        class="w-16 h-16 rounded-xl overflow-hidden border-2 border-gray-200 hover:border-green-500 transition">
                    <img src="<?php echo e(asset('storage/'.$img)); ?>" class="w-full h-full object-cover">
                </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="flex flex-col gap-5">
            <div>
                <p class="text-xs text-gray-400 uppercase font-bold tracking-wider"><?php echo e($product->category->name ?? ''); ?></p>
                <h1 class="text-3xl font-extrabold text-gray-800 mt-1 leading-tight"><?php echo e($product->name); ?></h1>
                <?php if($product->description): ?>
                    <p class="text-gray-500 text-sm mt-3 leading-relaxed"><?php echo e($product->description); ?></p>
                <?php endif; ?>
            </div>

            
            <div class="bg-green-50 border border-green-200 rounded-2xl p-5">
                <div class="flex items-baseline gap-3">
                    <span class="text-4xl font-extrabold text-gray-800">₹<?php echo e(number_format($product->price, 0)); ?></span>
                    <?php if($product->mrp && $product->mrp > $product->price): ?>
                        <span class="text-lg text-gray-400 line-through">₹<?php echo e(number_format($product->mrp, 0)); ?></span>
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded">
                            -<?php echo e(round((($product->mrp - $product->price) / $product->mrp) * 100)); ?>%
                        </span>
                    <?php endif; ?>
                </div>
                <p class="text-xs text-gray-500 mt-2">Per <?php echo e($product->unit); ?></p>
            </div>

            
            <?php if($product->variants && count($product->variants)): ?>
            <div>
                <p class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Select Pack Size</p>
                <div class="flex flex-wrap gap-2">
                    <?php $__currentLoopData = $product->variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button onclick="selectVariant(<?php echo e($idx); ?>, <?php echo e($variant['selling_price'] ?? $product->price); ?>)"
                            id="variant-btn-<?php echo e($idx); ?>"
                            class="px-4 py-2 rounded-xl border-2 text-sm font-bold transition
                                   <?php echo e($idx === 0 ? 'border-green-600 bg-green-50 text-green-700' : 'border-gray-200 text-gray-600 hover:border-green-400'); ?>">
                        <?php echo e($variant['quantity'] ?? ''); ?> <?php echo e($variant['unit'] ?? ''); ?>

                        <span class="block text-xs font-normal">₹<?php echo e($variant['selling_price'] ?? ''); ?></span>
                    </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            
            <div class="flex gap-3">
                <span class="flex items-center gap-2 px-3 py-2 bg-green-50 text-green-700 border border-green-200 rounded-lg text-xs font-bold">
                    <i class="fa-solid fa-circle-check"></i>
                    <?php echo e($product->stock > 0 ? 'In Stock ('.$product->stock.')' : 'Out of Stock'); ?>

                </span>
                <span class="flex items-center gap-2 px-3 py-2 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-xs font-bold">
                    <i class="fa-solid fa-truck"></i> Same Day Delivery
                </span>
            </div>

            
            <div class="flex gap-3">
                <div class="flex items-center border-2 border-gray-200 rounded-xl overflow-hidden">
                    <button onclick="changeQty(-1)" class="w-11 h-12 flex items-center justify-center text-gray-600 hover:bg-gray-100 font-bold text-lg">−</button>
                    <span id="qty-display" class="w-12 h-12 flex items-center justify-center font-bold text-lg border-x border-gray-200">1</span>
                    <button onclick="changeQty(1)" class="w-11 h-12 flex items-center justify-center text-gray-600 hover:bg-gray-100 font-bold text-lg">+</button>
                </div>
                <button onclick="addToCartWithQty(<?php echo e($product->id); ?>)"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl text-sm transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-cart-shopping"></i> Add to Cart
                </button>
                <a href="<?php echo e(route('frontend.checkout')); ?>"
                   class="flex-1 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-xl text-sm transition flex items-center justify-center gap-2">
                    Buy Now
                </a>
            </div>
        </div>
    </div>

    
    <?php if($similar->count()): ?>
    <div>
        <h2 class="text-xl font-extrabold text-gray-800 mb-5">Similar <span class="text-green-600">Products</span></h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <?php $__currentLoopData = $similar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('frontend.product.show', $p->slug)); ?>"
               class="bg-white rounded-2xl border hover:border-green-300 hover:shadow-md transition overflow-hidden block">
                <div class="aspect-square bg-gray-50 overflow-hidden">
                    <?php if($p->images && count($p->images)): ?>
                        <img src="<?php echo e(asset('storage/'.$p->images[0])); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-4xl">🥩</div>
                    <?php endif; ?>
                </div>
                <div class="p-3">
                    <p class="text-xs font-bold text-gray-800 line-clamp-2"><?php echo e($p->name); ?></p>
                    <p class="text-sm font-extrabold text-gray-800 mt-1">₹<?php echo e(number_format($p->price, 0)); ?></p>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
let currentQty = 1;
let selectedVariant = null;

function changeQty(delta) {
    currentQty = Math.max(1, currentQty + delta);
    document.getElementById('qty-display').textContent = currentQty;
}

function selectVariant(idx, price) {
    selectedVariant = idx;
    document.querySelectorAll('[id^="variant-btn-"]').forEach(btn => {
        btn.className = btn.className.replace('border-green-600 bg-green-50 text-green-700', 'border-gray-200 text-gray-600');
    });
    const btn = document.getElementById('variant-btn-' + idx);
    btn.className = btn.className.replace('border-gray-200 text-gray-600', 'border-green-600 bg-green-50 text-green-700');
}

function addToCartWithQty(productId) {
    fetch('<?php echo e(route("frontend.cart.add")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ product_id: productId, quantity: currentQty, variant_index: selectedVariant })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('header-cart-badge').textContent = data.cart_count;
            openCart();
        }
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views\frontend\product-detail.blade.php ENDPATH**/ ?>