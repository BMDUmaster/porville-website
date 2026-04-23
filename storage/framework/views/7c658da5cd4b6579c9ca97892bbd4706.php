
<?php $__env->startSection('title', 'All Products'); ?>

<?php $__env->startSection('content'); ?>

<div class="bg-green-700 py-10 px-6 text-white">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-extrabold">All <span class="text-green-300">Products</span></h1>
        <p class="text-white/70 text-sm mt-2">Sourced fresh from our farms and coastal waters.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8 flex flex-col lg:flex-row gap-8">

    
    <aside class="w-full lg:w-64 flex-shrink-0">
        <form method="GET" action="<?php echo e(route('frontend.products')); ?>" id="filterForm">
            <div class="bg-white rounded-2xl border p-5 space-y-5">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-sm text-gray-800">Filter Products</h3>
                    <a href="<?php echo e(route('frontend.products')); ?>" class="text-xs text-blue-600 font-semibold hover:underline">Clear All</a>
                </div>

                
                <div>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search products..."
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-green-500">
                </div>

                
                <div>
                    <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Category</h4>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="flex items-center gap-2 py-1.5 cursor-pointer">
                        <input type="radio" name="category" value="<?php echo e($cat->slug); ?>"
                               <?php echo e(request('category') == $cat->slug ? 'checked' : ''); ?>

                               class="accent-green-600" onchange="document.getElementById('filterForm').submit()">
                        <span class="text-sm text-gray-600"><?php echo e($cat->name); ?></span>
                    </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                
                <div>
                    <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Sort By</h4>
                    <select name="sort" onchange="document.getElementById('filterForm').submit()"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none">
                        <option value="latest" <?php echo e(request('sort','latest') == 'latest' ? 'selected' : ''); ?>>Latest</option>
                        <option value="price_asc" <?php echo e(request('sort') == 'price_asc' ? 'selected' : ''); ?>>Price: Low to High</option>
                        <option value="price_desc" <?php echo e(request('sort') == 'price_desc' ? 'selected' : ''); ?>>Price: High to Low</option>
                        <option value="name" <?php echo e(request('sort') == 'name' ? 'selected' : ''); ?>>Name A-Z</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-lg text-sm transition">
                    Apply Filters
                </button>
            </div>
        </form>
    </aside>

    
    <div class="flex-1">
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-gray-500 font-semibold"><?php echo e($products->total()); ?> products found</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5">
            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-2xl border border-gray-100 hover:border-green-300 hover:shadow-lg transition overflow-hidden flex flex-col">
                <a href="<?php echo e(route('frontend.product.show', $product->slug)); ?>" class="block aspect-square overflow-hidden bg-gray-50 relative">
                    <?php if($product->images && count($product->images)): ?>
                        <img src="<?php echo e(asset('storage/'.$product->images[0])); ?>" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-5xl">🥩</div>
                    <?php endif; ?>
                    <?php if($product->mrp && $product->mrp > $product->price): ?>
                        <span class="absolute top-2 right-2 bg-red-500 text-white text-[9px] font-bold px-2 py-0.5 rounded">
                            -<?php echo e(round((($product->mrp - $product->price) / $product->mrp) * 100)); ?>%
                        </span>
                    <?php endif; ?>
                </a>
                <div class="p-3 flex flex-col flex-1">
                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider"><?php echo e($product->unit); ?></p>
                    <a href="<?php echo e(route('frontend.product.show', $product->slug)); ?>"
                       class="text-sm font-bold text-gray-800 hover:text-green-700 mt-1 leading-snug line-clamp-2"><?php echo e($product->name); ?></a>
                    <p class="text-[10px] text-gray-400 mt-1"><?php echo e($product->category->name ?? ''); ?></p>
                    <div class="flex items-center justify-between mt-auto pt-3">
                        <div>
                            <p class="text-lg font-extrabold text-gray-800">₹<?php echo e(number_format($product->price, 0)); ?></p>
                            <?php if($product->mrp && $product->mrp > $product->price): ?>
                                <p class="text-xs text-gray-400 line-through">₹<?php echo e(number_format($product->mrp, 0)); ?></p>
                            <?php endif; ?>
                        </div>
                        <button onclick="addToCart(<?php echo e($product->id); ?>)"
                                class="w-9 h-9 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center justify-center text-sm transition">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-4 text-center py-16 text-gray-400">
                <i class="fa-solid fa-box-open text-5xl mb-4 block"></i>
                <p class="font-semibold">No products found.</p>
                <a href="<?php echo e(route('frontend.products')); ?>" class="text-blue-600 text-sm mt-2 inline-block hover:underline">Clear filters</a>
            </div>
            <?php endif; ?>
        </div>

        <div class="mt-8"><?php echo e($products->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views\frontend\products.blade.php ENDPATH**/ ?>