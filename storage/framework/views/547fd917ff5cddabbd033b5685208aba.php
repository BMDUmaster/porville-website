<?php $__env->startSection('title', 'FAQs — Porville'); ?>

<?php $__env->startSection('content'); ?>

<section class="bg-black py-14 md:py-20">
    <div class="mx-auto max-w-5xl px-4 text-center">
        <p class="text-[11px] font-extrabold uppercase tracking-[0.3em] text-amber-400">Help Center</p>
        <h1 class="font-classic mt-3 text-4xl font-bold text-white md:text-5xl">Frequently Asked Questions</h1>
        <p class="mt-4 text-sm leading-7 text-stone-300 md:text-base">
            Everything you need to know about ordering, delivery and our fresh-cut standards.
        </p>
    </div>
</section>

<section class="bg-[#faf7f0] py-12 md:py-16">
    <div class="mx-auto max-w-5xl px-4">
        <?php if($categories->isEmpty()): ?>
            <p class="rounded-2xl border border-dashed border-amber-200 bg-white px-6 py-12 text-center text-sm text-slate-500">
                FAQs will appear here soon.
            </p>
        <?php else: ?>
            <div class="flex flex-wrap justify-center gap-2.5" role="tablist">
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button type="button"
                            data-faq-tab="<?php echo e($category->slug); ?>"
                            onclick="selectFaqTab('<?php echo e($category->slug); ?>')"
                            class="faq-tab-btn <?php echo e($index === 0 ? 'is-active' : ''); ?> rounded-full border px-5 py-2.5 text-[12px] font-bold uppercase tracking-[0.12em] transition">
                        <?php echo e($category->title); ?>

                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="mx-auto mt-10 max-w-3xl">
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div data-faq-panel="<?php echo e($category->slug); ?>" class="<?php echo e($index === 0 ? '' : 'hidden'); ?> space-y-3">
                        <h2 class="font-classic mb-5 text-center text-2xl font-bold text-slate-900"><?php echo e($category->title); ?></h2>
                        <?php $__currentLoopData = $category->faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <details class="group rounded-2xl border border-amber-100 bg-white px-5 py-4 shadow-sm transition open:border-amber-300 open:shadow-md">
                                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-[15px] font-bold text-slate-900">
                                    <span><?php echo e($faq->question); ?></span>
                                    <i class="fa-solid fa-chevron-down flex-shrink-0 text-xs text-amber-500 transition group-open:rotate-180"></i>
                                </summary>
                                <p class="pt-4 text-[14px] leading-7 text-slate-600"><?php echo e($faq->answer); ?></p>
                            </details>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <div class="mx-auto mt-14 max-w-2xl rounded-2xl border border-amber-100 bg-white px-6 py-6 text-center shadow-sm">
            <p class="text-sm font-bold text-slate-900">Still have a question?</p>
            <p class="mt-1 text-sm text-slate-500">Our team is happy to help with orders, delivery or bulk requests.</p>
            <a href="<?php echo e(route('frontend.contact')); ?>" class="mt-4 inline-flex items-center gap-2 rounded-xl border border-amber-500 bg-black px-6 py-3 text-[12px] font-bold uppercase tracking-[0.14em] text-white transition hover:bg-neutral-900">
                <i class="fa-solid fa-headset text-amber-400"></i> Contact Us
            </a>
        </div>
    </div>
</section>

<style>
.faq-tab-btn {
    border-color: #e6d3a3;
    background: #ffffff;
    color: #6b5d47;
}
.faq-tab-btn.is-active {
    border-color: #000;
    background: #000;
    color: #fbbf24;
}
</style>

<script>
function selectFaqTab(slug) {
    document.querySelectorAll('[data-faq-tab]').forEach((btn) => {
        btn.classList.toggle('is-active', btn.dataset.faqTab === slug);
    });
    document.querySelectorAll('[data-faq-panel]').forEach((panel) => {
        panel.classList.toggle('hidden', panel.dataset.faqPanel !== slug);
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\porville-fresh-cut\resources\views/frontend/pages/faq.blade.php ENDPATH**/ ?>