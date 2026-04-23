
<?php $__env->startSection('title', 'FarmSea — Fresh Meat & Seafood'); ?>

<?php $__env->startSection('content'); ?>


<section class="relative w-full h-[480px] overflow-hidden bg-gray-900">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1608835291093-394b0c943a75?auto=format&fit=crop&w=1600&q=80"
             class="w-full h-full object-cover opacity-70">
    </div>
    <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-transparent"></div>
    <div class="relative z-10 h-full flex flex-col justify-center px-8 md:px-20 max-w-xl">
        <span class="inline-block bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded mb-4 uppercase tracking-widest">🐔 Farm Fresh Daily</span>
        <h1 class="text-4xl md:text-5xl font-extrabold text-white leading-tight mb-4">
            Premium Fresh<br><span class="text-green-400">Meat & Seafood</span>
        </h1>
        <p class="text-white/75 text-sm mb-6 max-w-sm">Sourced directly from our farms. Cleaned, cut & delivered to your doorstep.</p>
        <div class="flex gap-3">
            <a href="<?php echo e(route('frontend.products')); ?>"
               class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold text-sm rounded-lg transition">Shop Now</a>
           
    </div>
</section>


<div class="bg-white border-b border-gray-100 py-4">
    <div class="max-w-6xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php $__currentLoopData = [['fa-leaf','100% Natural','No hormones or chemicals'],['fa-snowflake','Cold Chain Delivery','Fresh at every step'],['fa-bolt','Same Day Delivery','Order before 10 AM'],['fa-shield-halved','FSSAI Certified','Quality you can trust']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon,$title,$sub]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-green-600 flex items-center justify-center text-white text-sm flex-shrink-0">
                <i class="fa-solid <?php echo e($icon); ?>"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-800"><?php echo e($title); ?></p>
                <p class="text-[10px] text-gray-500"><?php echo e($sub); ?></p>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>


<section class="py-14 bg-white">
    <div class="max-w-6xl mx-auto px-4">

        
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-xs font-bold text-green-600 uppercase tracking-widest mb-1">Browse</p>
                <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">Shop by <span class="text-blue-600">Category</span></h2>
                <p class="text-sm text-gray-400 mt-1">Farm-to-table freshness across every cut and kind.</p>
            </div>
            <a href="<?php echo e(route('frontend.categories')); ?>"
               class="hidden sm:flex items-center gap-1.5 text-blue-600 font-bold text-xs uppercase tracking-wider hover:text-blue-800 transition">
                See All <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <?php
        $catColors = [
            0 => ['bg' => 'from-green-50 to-green-100', 'border' => 'border-green-200', 'hover' => 'hover:border-green-400', 'badge' => 'bg-green-600', 'text' => 'text-green-700'],
            1 => ['bg' => 'from-red-50 to-orange-50', 'border' => 'border-red-200', 'hover' => 'hover:border-red-400', 'badge' => 'bg-red-500', 'text' => 'text-red-600'],
            2 => ['bg' => 'from-blue-50 to-indigo-50', 'border' => 'border-blue-200', 'hover' => 'hover:border-blue-400', 'badge' => 'bg-blue-600', 'text' => 'text-blue-600'],
            3 => ['bg' => 'from-amber-50 to-yellow-50', 'border' => 'border-amber-200', 'hover' => 'hover:border-amber-400', 'badge' => 'bg-amber-500', 'text' => 'text-amber-600'],
            4 => ['bg' => 'from-purple-50 to-pink-50', 'border' => 'border-purple-200', 'hover' => 'hover:border-purple-400', 'badge' => 'bg-purple-600', 'text' => 'text-purple-600'],
            5 => ['bg' => 'from-teal-50 to-cyan-50', 'border' => 'border-teal-200', 'hover' => 'hover:border-teal-400', 'badge' => 'bg-teal-600', 'text' => 'text-teal-600'],
            6 => ['bg' => 'from-rose-50 to-red-50', 'border' => 'border-rose-200', 'hover' => 'hover:border-rose-400', 'badge' => 'bg-rose-500', 'text' => 'text-rose-600'],
            7 => ['bg' => 'from-lime-50 to-green-50', 'border' => 'border-lime-200', 'hover' => 'hover:border-lime-400', 'badge' => 'bg-lime-600', 'text' => 'text-lime-700'],
        ];
        $catEmojis = ['🐔','🥩','🐟','🥦','🍎','🥚','🦐','🍳'];
        ?>

        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-4 md:gap-5">
            <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php $c = $catColors[$i % 8]; ?>
            <a href="<?php echo e(route('frontend.products', ['category' => $cat->slug])); ?>"
               class="group relative rounded-2xl border <?php echo e($c['border']); ?> <?php echo e($c['hover']); ?> bg-gradient-to-br <?php echo e($c['bg']); ?> overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 block">

                
                <div class="relative aspect-square overflow-hidden">
                    <?php if($cat->image): ?>
                        <img src="<?php echo e(asset('storage/'.$cat->image)); ?>"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-5xl md:text-6xl bg-white/40">
                            <?php echo e($catEmojis[$i % 8]); ?>

                        </div>
                    <?php endif; ?>
                    
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-300"></div>
                    
                    <div class="absolute top-2.5 right-2.5">
                        <span class="<?php echo e($c['badge']); ?> text-white text-[9px] font-extrabold px-2 py-0.5 rounded-full uppercase tracking-wider shadow-sm">
                            <?php echo e($cat->products_count); ?> items
                        </span>
                    </div>
                </div>

                
                <div class="px-3 py-3">
                    <p class="font-extrabold text-sm text-gray-800 group-hover:<?php echo e($c['text']); ?> transition-colors leading-tight">
                        <?php echo e($cat->name); ?>

                    </p>
                    <div class="flex items-center gap-1 mt-1.5">
                        <span class="<?php echo e($c['text']); ?> text-[10px] font-semibold">Shop now</span>
                        <i class="fa-solid fa-arrow-right <?php echo e($c['text']); ?> text-[9px] group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-4 text-center py-12 text-gray-400">
                <i class="fa-solid fa-layer-group text-4xl mb-3 block opacity-30"></i>
                <p class="text-sm">No categories yet.</p>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="mt-6 text-center sm:hidden">
            <a href="<?php echo e(route('frontend.categories')); ?>"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm px-6 py-2.5 rounded-xl transition">
                See All Categories <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

    </div>
</section>


<section class="py-12 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-end justify-between mb-6">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-800">New <span class="text-blue-600">Arrivals</span></h2>
                <p class="text-xs text-gray-500 mt-1">The freshest additions to our selection.</p>
            </div>
            <a href="<?php echo e(route('frontend.products')); ?>" class="text-blue-600 font-bold text-xs uppercase tracking-wider hover:underline">See All →</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            <?php $__empty_1 = true; $__currentLoopData = $newArrivals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-2xl border border-gray-100 hover:border-green-300 hover:shadow-lg transition overflow-hidden flex flex-col">
                <a href="<?php echo e(route('frontend.product.show', $product->slug)); ?>" class="block aspect-square overflow-hidden bg-gray-50">
                    <?php if($product->images && count($product->images)): ?>
                        <img src="<?php echo e(asset('storage/'.$product->images[0])); ?>" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-5xl">🥩</div>
                    <?php endif; ?>
                </a>
                <div class="p-3 flex flex-col flex-1">
                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider"><?php echo e($product->unit); ?></p>
                    <a href="<?php echo e(route('frontend.product.show', $product->slug)); ?>"
                       class="text-sm font-bold text-gray-800 hover:text-green-700 mt-1 leading-snug line-clamp-2"><?php echo e($product->name); ?></a>
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
            <p class="col-span-4 text-center text-gray-400 py-8">No products yet.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views\frontend\home.blade.php ENDPATH**/ ?>