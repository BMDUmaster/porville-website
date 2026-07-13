<?php $__env->startSection('title', 'About Us'); ?>

<?php $__env->startSection('content'); ?>
<section class="bg-white py-12 md:py-16">
    <div class="mx-auto max-w-6xl px-4">
        <div class="grid items-center gap-10 lg:grid-cols-[1.05fr_0.95fr]">
            <div>
                <h1 class="nunito max-w-[460px] text-4xl font-extrabold leading-[0.95] text-slate-900 md:text-5xl">
                    Fresh From Farm<br><span class="text-green-500">To Your Home</span>
                </h1>
                <p class="mt-5 max-w-[520px] text-[15px] leading-7 text-slate-600">
                    FarmSea brings you fresh vegetables, fruits, meat, and seafood directly from trusted farms and suppliers.
                    Quality, hygiene, and freshness delivered at your doorstep.
                </p>
                <a href="<?php echo e(route('frontend.products')); ?>" class="mt-7 inline-flex rounded-xl bg-green-600 px-6 py-3 text-sm font-bold text-white transition hover:-translate-y-0.5 hover:bg-green-700 hover:shadow-lg">
                    Shop Now
                </a>
            </div>

            <div class="justify-self-end">
                <div class="group relative overflow-hidden rounded-[22px] border border-slate-200 bg-white shadow-[0_16px_40px_rgba(15,23,42,0.08)] transition duration-300 hover:-translate-y-1 hover:border-green-200 hover:shadow-[0_24px_56px_rgba(15,23,42,0.14)]">
                    <img
                        src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1200&q=80"
                        alt="Fresh produce display"
                        class="h-[260px] w-full object-cover transition duration-700 group-hover:scale-105 md:h-[290px] lg:w-[500px]"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/20 via-transparent to-transparent opacity-0 transition duration-300 group-hover:opacity-100"></div>
                    <div class="absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1 text-[11px] font-extrabold uppercase tracking-[0.18em] text-slate-900 shadow-sm transition duration-300 group-hover:-translate-y-0.5">
                        Fresh & Hygienic
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-14 grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white px-5 py-5 shadow-sm transition hover:-translate-y-1 hover:border-green-200 hover:shadow-lg">
                <h3 class="text-[15px] font-extrabold text-slate-900">Farm Fresh Products</h3>
                <p class="mt-2 text-[13px] leading-6 text-slate-500">Directly sourced from local farms.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white px-5 py-5 shadow-sm transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                <h3 class="text-[15px] font-extrabold text-slate-900">Fast Delivery</h3>
                <p class="mt-2 text-[13px] leading-6 text-slate-500">Quick and hygienic doorstep delivery.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white px-5 py-5 shadow-sm transition hover:-translate-y-1 hover:border-amber-200 hover:shadow-lg">
                <h3 class="text-[15px] font-extrabold text-slate-900">Best Quality</h3>
                <p class="mt-2 text-[13px] leading-6 text-slate-500">Fresh, clean, and high-quality food items.</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#fafbfc] py-14 md:py-16">
    <div class="mx-auto max-w-6xl px-4">
        <div class="grid items-center gap-10 lg:grid-cols-[0.95fr_1.05fr]">
            <div>
                <div class="group relative overflow-hidden rounded-[20px] border border-slate-200 bg-white shadow-[0_12px_32px_rgba(15,23,42,0.08)] transition duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-[0_22px_48px_rgba(15,23,42,0.14)]">
                    <img
                        src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1200&q=80"
                        alt="Farm landscape"
                        class="h-[250px] w-full object-cover transition duration-700 group-hover:scale-105 md:h-[300px]"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/25 via-transparent to-transparent opacity-0 transition duration-300 group-hover:opacity-100"></div>
                    <div class="absolute bottom-4 left-4 rounded-2xl bg-white/92 px-4 py-3 shadow-lg backdrop-blur transition duration-300 group-hover:translate-y-0.5">
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-green-600">FarmSea Promise</p>
                        <p class="mt-1 text-sm font-bold text-slate-900">Fresh sourcing with reliable delivery</p>
                    </div>
                </div>
                <p class="mt-5 max-w-[520px] text-[14px] leading-7 text-slate-600">
                    FarmSea is a modern marketplace connecting farmers and consumers directly. We ensure fresh produce,
                    fair pricing, and reliable delivery for all your daily needs.
                </p>
            </div>

            <div>
                <h2 class="nunito text-3xl font-extrabold text-slate-900 md:text-4xl">About FarmSea</h2>
                <p class="mt-4 text-[15px] leading-8 text-slate-600">
                    We are building a cleaner and smarter way to buy everyday essentials. From farm produce to premium
                    meat and seafood, our goal is to make fresh food more accessible, more reliable, and more affordable.
                </p>

                <div class="mt-8 space-y-4">
                    <div class="flex items-start gap-3 rounded-2xl bg-white px-4 py-4 shadow-sm">
                        <i class="fa-solid fa-check mt-1 text-green-600"></i>
                        <div>
                            <p class="font-bold text-slate-900">Fresh Vegetables & Fruits</p>
                            <p class="text-sm text-slate-500">Seasonal produce sourced from trusted growers.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 rounded-2xl bg-white px-4 py-4 shadow-sm">
                        <i class="fa-solid fa-check mt-1 text-green-600"></i>
                        <div>
                            <p class="font-bold text-slate-900">Premium Meat & Seafood</p>
                            <p class="text-sm text-slate-500">Handled hygienically and delivered in fresh condition.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 rounded-2xl bg-white px-4 py-4 shadow-sm">
                        <i class="fa-solid fa-check mt-1 text-green-600"></i>
                        <div>
                            <p class="font-bold text-slate-900">Daily & Dairy Essentials</p>
                            <p class="text-sm text-slate-500">Everyday staples delivered quickly and safely.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="faq" class="bg-white py-14 md:py-16">
    <div class="mx-auto max-w-4xl px-4">
        <div class="mb-8 text-center">
            <h2 class="nunito text-3xl font-extrabold text-slate-900 md:text-4xl">FAQs</h2>
        </div>

        <div class="space-y-4">
            <details class="group rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm transition open:shadow-md">
                <summary class="flex cursor-pointer list-none items-center justify-between text-[15px] font-bold text-slate-900">
                    <span>Where do products come from?</span>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition group-open:rotate-180"></i>
                </summary>
                <p class="pt-4 text-[14px] leading-7 text-slate-600">
                    Our products come from trusted farms, fisheries, and verified suppliers who meet our hygiene and quality standards.
                </p>
            </details>

            <details class="group rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm transition open:shadow-md">
                <summary class="flex cursor-pointer list-none items-center justify-between text-[15px] font-bold text-slate-900">
                    <span>Do you deliver daily?</span>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition group-open:rotate-180"></i>
                </summary>
                <p class="pt-4 text-[14px] leading-7 text-slate-600">
                    Yes, we support regular delivery slots in serviceable areas so you can get fresh essentials on time.
                </p>
            </details>

            <details class="group rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm transition open:shadow-md">
                <summary class="flex cursor-pointer list-none items-center justify-between text-[15px] font-bold text-slate-900">
                    <span>How do you maintain freshness?</span>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition group-open:rotate-180"></i>
                </summary>
                <p class="pt-4 text-[14px] leading-7 text-slate-600">
                    We use hygienic handling, careful packaging, and a cold-chain aware delivery process for meat and seafood products.
                </p>
            </details>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views/frontend/pages/about-us.blade.php ENDPATH**/ ?>