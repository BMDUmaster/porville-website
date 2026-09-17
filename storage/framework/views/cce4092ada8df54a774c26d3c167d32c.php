<?php $__env->startSection('title', 'FAQs'); ?>
<?php $__env->startSection('page_title', 'FAQ Manager'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 md:p-6 mx-auto max-w-4xl space-y-5">

    <?php if(session('success')): ?>
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <?php echo e($errors->first()); ?>

        </div>
    <?php endif; ?>

    <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-black text-slate-900">FAQ Manager</h1>
            <p class="mt-1 text-sm text-slate-500">Add a title (e.g. "Delivery"), then add questions under it. Customers see these grouped by title on the public FAQ page.</p>
        </div>
        <button type="button" onclick="openCategoryModal()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-amber-700">
            <i class="fa-solid fa-plus"></i> Add Title
        </button>
    </div>

    <div class="space-y-4">
        <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4">
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-black text-slate-900"><?php echo e($category->title); ?></h2>
                        <?php if (! ($category->is_active)): ?>
                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-bold uppercase text-slate-500">Hidden</span>
                        <?php endif; ?>
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-500"><?php echo e($category->faqs_count); ?> <?php echo e(Str::plural('question', $category->faqs_count)); ?></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick='openFaqModal(<?php echo e($category->id); ?>)' class="text-xs font-bold text-amber-600 hover:text-amber-800"><i class="fa-solid fa-plus mr-1"></i>Add Question</button>
                        <button type="button"
                                data-category="<?php echo e(json_encode(['id' => $category->id, 'title' => $category->title, 'is_active' => $category->is_active])); ?>"
                                onclick="openCategoryModal(JSON.parse(this.dataset.category))"
                                class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-50 hover:text-slate-700"><i class="fa-solid fa-pen"></i></button>
                        <form method="POST" action="<?php echo e(route('dashboard.faqs.categories.destroy', $category)); ?>" onsubmit="return confirm('Delete this title and all its questions?');">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="flex h-8 w-8 items-center justify-center rounded-lg text-red-400 hover:bg-red-50 hover:text-red-600"><i class="fa-solid fa-trash-can"></i></button>
                        </form>
                    </div>
                </div>
                <div class="divide-y divide-slate-100">
                    <?php $__empty_2 = true; $__currentLoopData = $category->faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                        <div class="flex items-start justify-between gap-3 px-5 py-3.5">
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-slate-900"><?php echo e($faq->question); ?>

                                    <?php if (! ($faq->is_active)): ?>
                                        <span class="ml-1 rounded-full bg-slate-100 px-2 py-0.5 text-[9px] font-bold uppercase text-slate-500">Hidden</span>
                                    <?php endif; ?>
                                </p>
                                <p class="mt-1 text-xs leading-5 text-slate-500"><?php echo e(Str::limit($faq->answer, 140)); ?></p>
                            </div>
                            <div class="flex flex-shrink-0 items-center gap-2">
                                <button type="button"
                                        data-faq="<?php echo e(json_encode(['id' => $faq->id, 'question' => $faq->question, 'answer' => $faq->answer, 'is_active' => $faq->is_active])); ?>"
                                        onclick="openFaqModal(<?php echo e($category->id); ?>, JSON.parse(this.dataset.faq))"
                                        class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-50 hover:text-slate-700"><i class="fa-solid fa-pen"></i></button>
                                <form method="POST" action="<?php echo e(route('dashboard.faqs.items.destroy', $faq)); ?>" onsubmit="return confirm('Delete this question?');">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="flex h-8 w-8 items-center justify-center rounded-lg text-red-400 hover:bg-red-50 hover:text-red-600"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                        <p class="px-5 py-4 text-sm text-slate-400">No questions under this title yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="rounded-2xl border border-dashed border-slate-300 px-4 py-10 text-center text-sm text-slate-400">No FAQ titles yet. Click "Add Title" to create your first one (e.g. "Delivery", "Ordering").</p>
        <?php endif; ?>
    </div>
</div>


<div id="categoryModal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm">
    <form id="categoryForm" method="POST" class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="_method" id="categoryFormMethod" value="POST">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
            <h2 id="categoryModalTitle" class="text-lg font-black text-slate-900">Add Title</h2>
            <button type="button" onclick="closeCategoryModal()" class="text-2xl leading-none text-slate-400 hover:text-slate-700">&times;</button>
        </div>
        <div class="space-y-4 px-6 py-5">
            <div>
                <label class="mb-1.5 block text-xs font-bold text-slate-600">Title</label>
                <input type="text" name="title" id="categoryTitleInput" required placeholder="e.g. Delivery"
                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold outline-none focus:border-amber-500">
            </div>
            <label id="categoryActiveWrap" class="hidden items-center gap-2 text-sm font-semibold text-slate-600">
                <input type="checkbox" name="is_active" id="categoryActiveInput" value="1" checked class="h-4 w-4 rounded border-slate-300 text-amber-600">
                Visible on the public FAQ page
            </label>
        </div>
        <div class="flex gap-3 border-t border-slate-100 px-6 py-4">
            <button type="button" onclick="closeCategoryModal()" class="flex-1 rounded-xl bg-slate-100 py-3 text-sm font-bold text-slate-600">Cancel</button>
            <button type="submit" class="flex-1 rounded-xl bg-amber-600 py-3 text-sm font-bold text-white hover:bg-amber-700">Save</button>
        </div>
    </form>
</div>


<div id="faqModal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm">
    <form id="faqForm" method="POST" class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="_method" id="faqFormMethod" value="POST">
        <input type="hidden" name="faq_category_id" id="faqCategoryIdInput">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
            <h2 id="faqModalTitle" class="text-lg font-black text-slate-900">Add Question</h2>
            <button type="button" onclick="closeFaqModal()" class="text-2xl leading-none text-slate-400 hover:text-slate-700">&times;</button>
        </div>
        <div class="space-y-4 px-6 py-5">
            <div>
                <label class="mb-1.5 block text-xs font-bold text-slate-600">Question</label>
                <input type="text" name="question" id="faqQuestionInput" required placeholder="e.g. Do you deliver today?"
                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold outline-none focus:border-amber-500">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-bold text-slate-600">Answer</label>
                <textarea name="answer" id="faqAnswerInput" required rows="4" placeholder="Write the answer customers will see."
                          class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold outline-none focus:border-amber-500"></textarea>
            </div>
            <label id="faqActiveWrap" class="hidden items-center gap-2 text-sm font-semibold text-slate-600">
                <input type="checkbox" name="is_active" id="faqActiveInput" value="1" checked class="h-4 w-4 rounded border-slate-300 text-amber-600">
                Visible on the public FAQ page
            </label>
        </div>
        <div class="flex gap-3 border-t border-slate-100 px-6 py-4">
            <button type="button" onclick="closeFaqModal()" class="flex-1 rounded-xl bg-slate-100 py-3 text-sm font-bold text-slate-600">Cancel</button>
            <button type="submit" class="flex-1 rounded-xl bg-amber-600 py-3 text-sm font-bold text-white hover:bg-amber-700">Save</button>
        </div>
    </form>
</div>

<script>
function openCategoryModal(category = null) {
    const modal = document.getElementById('categoryModal');
    const form = document.getElementById('categoryForm');
    const title = document.getElementById('categoryModalTitle');
    const activeWrap = document.getElementById('categoryActiveWrap');

    if (category) {
        title.textContent = 'Edit Title';
        form.action = '<?php echo e(url("/faqs/categories")); ?>/' + category.id;
        document.getElementById('categoryFormMethod').value = 'PUT';
        document.getElementById('categoryTitleInput').value = category.title;
        document.getElementById('categoryActiveInput').checked = !!category.is_active;
        activeWrap.classList.remove('hidden');
        activeWrap.classList.add('flex');
    } else {
        title.textContent = 'Add Title';
        form.action = '<?php echo e(route("dashboard.faqs.categories.store")); ?>';
        document.getElementById('categoryFormMethod').value = 'POST';
        document.getElementById('categoryTitleInput').value = '';
        activeWrap.classList.add('hidden');
        activeWrap.classList.remove('flex');
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeCategoryModal() {
    const modal = document.getElementById('categoryModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function openFaqModal(categoryId, faq = null) {
    const modal = document.getElementById('faqModal');
    const form = document.getElementById('faqForm');
    const title = document.getElementById('faqModalTitle');
    const activeWrap = document.getElementById('faqActiveWrap');

    document.getElementById('faqCategoryIdInput').value = categoryId;

    if (faq) {
        title.textContent = 'Edit Question';
        form.action = '<?php echo e(url("/faqs/items")); ?>/' + faq.id;
        document.getElementById('faqFormMethod').value = 'PUT';
        document.getElementById('faqQuestionInput').value = faq.question;
        document.getElementById('faqAnswerInput').value = faq.answer;
        document.getElementById('faqActiveInput').checked = !!faq.is_active;
        activeWrap.classList.remove('hidden');
        activeWrap.classList.add('flex');
    } else {
        title.textContent = 'Add Question';
        form.action = '<?php echo e(route("dashboard.faqs.items.store")); ?>';
        document.getElementById('faqFormMethod').value = 'POST';
        document.getElementById('faqQuestionInput').value = '';
        document.getElementById('faqAnswerInput').value = '';
        activeWrap.classList.add('hidden');
        activeWrap.classList.remove('flex');
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeFaqModal() {
    const modal = document.getElementById('faqModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\porville-fresh-cut\resources\views/dashboard/faqs/index.blade.php ENDPATH**/ ?>