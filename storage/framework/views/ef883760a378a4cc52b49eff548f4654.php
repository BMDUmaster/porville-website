
<?php $__env->startSection('title', 'Shipping Policy'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    html {
        scroll-behavior: smooth;
    }

    :root {
        --policy-nav-offset: 150px;
    }

    .policy-anchor {
        scroll-margin-top: calc(var(--policy-nav-offset) + 28px);
    }

    .policy-side-link {
        transition: all .2s ease;
    }

    .policy-side-link.active {
        background: #1d4ed8;
        color: #fff;
        border-color: #1d4ed8;
        box-shadow: 0 10px 22px rgba(29, 78, 216, 0.18);
    }

    .policy-mobile-link.active {
        background: #1d4ed8;
        color: #fff;
        border-color: #1d4ed8;
        box-shadow: 0 8px 20px rgba(29, 78, 216, 0.16);
    }

    .policy-mobile-nav {
        position: fixed;
        top: calc(var(--policy-nav-offset) + 104px);
        left: 28px;
        right: 12px;
    }

    .policy-mobile-nav::-webkit-scrollbar,
    .policy-desktop-nav::-webkit-scrollbar {
        display: none;
    }

    .policy-desktop-nav {
        max-height: calc(100vh - var(--policy-nav-offset) - 36px);
        overflow-y: auto;
    }

    @media (min-width: 1024px) {
        .policy-desktop-wrapper {
            position: -webkit-sticky;
            position: sticky;
            top: calc(var(--policy-nav-offset) + 18px);
            align-self: start;
            z-index: 20;
        }
    }

    .policy-mobile-nav,
    .policy-desktop-nav {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $effectiveDate = 'April 24, 2026';

    $sections = [
        [
            'id' => 'order-processing',
            'title' => 'Order Processing',
            'summary' => 'All FarmSea orders are processed once payment is confirmed.',
            'points' => [
                'Orders placed before the daily cut-off are prepared for the earliest available dispatch slot.',
                'Orders placed late at night, on public holidays, or during heavy demand may be processed on the next working day.',
                'You will receive confirmation after your order is accepted into our processing queue.',
            ],
            'note' => ['type' => 'info', 'text' => 'Customers receive an order confirmation and a delivery update once the shipment is dispatched.'],
        ],
        [
            'id' => 'delivery-methods',
            'title' => 'Delivery Methods',
            'summary' => 'FarmSea uses trusted local delivery partners and in-house riders depending on your area.',
            'points' => [
                'Delivery mode is selected automatically based on freshness, route availability, and order size.',
                'Temperature-sensitive items are packed for safe transport during the full delivery journey.',
                'Contactless doorstep handover is available in most service locations.',
            ],
            'note' => ['type' => 'info', 'text' => 'We aim to ensure products arrive chilled, sealed, and ready for storage or cooking.'],
        ],
        [
            'id' => 'delivery-areas',
            'title' => 'Delivery Areas',
            'summary' => 'We currently serve selected cities and nearby coverage zones.',
            'points' => [
                'Service availability depends on your pin code and rider coverage at the time of order.',
                'Some remote or high-demand areas may have limited slots on certain days.',
                'If a location is temporarily unavailable, you may be asked to reschedule or update the address.',
            ],
            'note' => ['type' => 'warn', 'text' => 'Availability may change based on stock, route conditions, weather, or local restrictions.'],
        ],
        [
            'id' => 'delivery-time',
            'title' => 'Delivery Time',
            'summary' => 'Standard delivery usually takes place within the same day or the next available slot.',
            'points' => [
                'Estimated delivery windows are shown during checkout whenever available.',
                'Peak traffic, weather conditions, or local disruptions may cause delays.',
                'Orders with multiple categories may take longer if they require separate handling.',
            ],
            'note' => ['type' => 'danger', 'text' => 'Delivery timelines are estimates only and may vary due to operational factors outside our direct control.'],
        ],
        [
            'id' => 'order-updates',
            'title' => 'Order Updates',
            'summary' => 'Customers are informed through order status updates after confirmation.',
            'points' => [
                'You may receive updates by SMS, email, or account notifications, depending on the details shared at checkout.',
                'If our team needs clarification about address, stock, or timing, we may contact you directly.',
                'You can also track the latest status from the FarmSea order tracking page.',
            ],
            'note' => ['type' => 'info', 'text' => 'For urgent help, contact support with your order number for faster assistance.'],
        ],
        [
            'id' => 'delivery-charges',
            'title' => 'Delivery Charges',
            'summary' => 'Delivery charges depend on order value, campaign offers, and service zone.',
            'points' => [
                'Free delivery may be available for eligible order values or limited-time promotions.',
                'Certain low-value or long-distance orders may include an additional delivery fee.',
                'Any applicable delivery charge is shown clearly before final payment.',
            ],
            'note' => ['type' => 'warn', 'text' => 'Delivery fees can change without notice during special events, heavy demand, or area-specific operational conditions.'],
        ],
        [
            'id' => 'product-fulfillment',
            'title' => 'Product Fulfillment',
            'summary' => 'Products are fulfilled by FarmSea inventory and approved sourcing partners.',
            'points' => [
                'Items are packed based on availability, freshness checks, and quality control standards.',
                'Equivalent substitutions are not made without operational approval or customer communication.',
                'Naturally sourced items may show slight differences in cut, size, color, or trim.',
            ],
            'note' => ['type' => 'info', 'text' => 'Our team performs quality checks before dispatch to maintain hygiene and freshness standards.'],
        ],
        [
            'id' => 'damaged-orders',
            'title' => 'Damaged or Incorrect Orders',
            'summary' => 'Please report damaged, missing, or incorrect items as soon as possible after delivery.',
            'points' => [
                'Customers should share clear photos and the order number when raising a quality issue.',
                'Claims are reviewed based on delivery timing, packaging condition, and issue details.',
                'Verified cases may be resolved through replacement, refund, or wallet credit at FarmSea discretion.',
            ],
            'note' => ['type' => 'danger', 'text' => 'Claims raised too late after delivery may not be eligible for action because perishables are time-sensitive.'],
        ],
        [
            'id' => 'returns',
            'title' => 'Returns',
            'summary' => 'Due to hygiene and safety standards, most perishable products are not eligible for general return.',
            'points' => [
                'Return requests are considered only for verified damage, wrong delivery, or quality issues.',
                'Opened, consumed, or improperly stored items may not qualify for return or refund.',
                'If approved, the resolution may be handled without physical reverse pickup in some cases.',
            ],
            'note' => ['type' => 'warn', 'text' => 'Returns are not accepted for change-of-mind requests on perishable items.'],
        ],
        [
            'id' => 'address-details',
            'title' => 'Address Details',
            'summary' => 'Customers must provide a complete and accurate delivery address.',
            'points' => [
                'Please include house number, street, landmark, phone number, and pin code where applicable.',
                'Incorrect or incomplete details can delay delivery or cause rescheduling.',
                'Repeated failed delivery attempts due to wrong address details may lead to cancellation.',
            ],
            'note' => ['type' => 'danger', 'text' => 'FarmSea cannot be held responsible for delays caused by incorrect address details shared during checkout.'],
        ],
        [
            'id' => 'support',
            'title' => 'Support',
            'summary' => 'Our support team is available to help with delivery-related concerns.',
            'points' => [
                'For assistance, use the track order page, contact page, or the support email listed below.',
                'Please keep your order number ready when contacting support for faster resolution.',
                'Operational response time may vary during weekends, holidays, or major sale periods.',
            ],
            'note' => ['type' => 'info', 'text' => 'Email support: support@farmsea.com'],
        ],
        [
            'id' => 'policy-updates',
            'title' => 'Policy Updates',
            'summary' => 'FarmSea may update this shipping policy from time to time.',
            'points' => [
                'Changes become effective when published on this page unless stated otherwise.',
                'Continued use of our platform after updates indicates acceptance of the revised policy.',
                'We recommend reviewing this page periodically for the latest shipping terms.',
            ],
            'note' => ['type' => 'danger', 'text' => 'Using the FarmSea website after policy changes means you agree to the updated shipping terms.'],
        ],
    ];

    $noteStyles = [
        'info' => 'border-blue-100 bg-blue-50 text-blue-900',
        'warn' => 'border-amber-100 bg-amber-50 text-amber-900',
        'danger' => 'border-rose-100 bg-rose-50 text-rose-900',
    ];
?>

<section id="top" class="relative bg-[linear-gradient(180deg,#eef4ff_0%,#f9fbff_42%,#ffffff_100%)]">
    <div class="absolute inset-x-0 top-0 h-56 bg-[radial-gradient(circle_at_top_left,rgba(59,130,246,0.14),transparent_52%),radial-gradient(circle_at_top_right,rgba(34,197,94,0.12),transparent_44%)]"></div>

    <div class="relative mx-auto max-w-7xl px-4 py-10 md:px-6 md:py-14">
        <div class="rounded-[28px] border border-blue-100 bg-white/90 p-6 shadow-[0_24px_80px_rgba(15,23,42,0.08)] md:p-8 lg:p-10">
            <div class="flex flex-col gap-6 border-b border-slate-100 pb-8 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <span class="inline-flex rounded-full bg-blue-600 px-4 py-1.5 text-[11px] font-extrabold uppercase tracking-[0.2em] text-white shadow-sm">
                        Shipping Policy
                    </span>
                    <h1 class="mt-5 text-3xl font-extrabold leading-tight text-slate-950 md:text-5xl">
                        Delivery Information
                        <span class="block text-slate-300">Made Clear & Simple</span>
                    </h1>
                    <p class="mt-4 max-w-2xl text-[15px] leading-7 text-slate-600">
                        This page explains how FarmSea processes, dispatches, and delivers orders. Use the section menu to jump directly to the shipping topic you want to read.
                    </p>
                </div>

                <div class="grid gap-3 sm:grid-cols-3 lg:min-w-[360px]">
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Effective Date</p>
                        <p class="mt-2 text-sm font-extrabold text-slate-900"><?php echo e($effectiveDate); ?></p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Support Email</p>
                        <p class="mt-2 text-sm font-extrabold text-slate-900">support@farmsea.com</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Quick Help</p>
                        <a href="<?php echo e(route('frontend.track')); ?>" class="mt-2 inline-flex text-sm font-extrabold text-blue-700 transition hover:text-blue-900">
                            Track Your Order
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-6 h-[70px] lg:hidden"></div>
            <div class="policy-mobile-nav z-30 flex gap-2 overflow-x-auto rounded-[22px] border border-slate-100 bg-white/95 p-2 shadow-[0_16px_32px_rgba(15,23,42,0.08)] backdrop-blur lg:hidden">
                <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a
                        href="#<?php echo e($section['id']); ?>"
                        data-policy-mobile-link="<?php echo e($section['id']); ?>"
                        class="policy-mobile-link whitespace-nowrap rounded-full border border-slate-200 bg-white px-4 py-2 text-[11px] font-extrabold uppercase tracking-[0.14em] text-slate-700 shadow-sm transition"
                    >
                        <?php echo e($section['title']); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="mt-8 grid gap-8 lg:grid-cols-[250px_minmax(0,1fr)]">
                <aside class="policy-desktop-wrapper hidden lg:block" data-policy-desktop-wrapper>
                    <div class="policy-desktop-nav rounded-[24px] border border-slate-100 bg-[#f8fbff] p-4 shadow-[0_14px_40px_rgba(15,23,42,0.06)]">
                        <p class="px-2 text-[11px] font-extrabold uppercase tracking-[0.26em] text-slate-400">On This Page</p>
                        <div class="mt-4 space-y-2">
                            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a
                                    href="#<?php echo e($section['id']); ?>"
                                    data-policy-link="<?php echo e($section['id']); ?>"
                                    class="policy-side-link flex rounded-2xl border border-transparent px-3 py-3 text-[13px] font-bold text-slate-700 hover:border-blue-100 hover:bg-white hover:text-blue-700"
                                >
                                    <?php echo e($section['title']); ?>

                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </aside>

                <div class="space-y-5">
                    <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <section
                            id="<?php echo e($section['id']); ?>"
                            data-policy-section="<?php echo e($section['id']); ?>"
                            class="policy-anchor rounded-[26px] border border-slate-100 bg-white p-5 shadow-[0_18px_45px_rgba(15,23,42,0.05)] md:p-7"
                        >
                            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                                <div class="max-w-2xl">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-sm font-extrabold text-blue-700">
                                            <?php echo e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?>

                                        </span>
                                        <div>
                                            <p class="text-[11px] font-extrabold uppercase tracking-[0.24em] text-slate-400">Shipping Section</p>
                                            <h2 class="mt-1 text-2xl font-extrabold text-slate-950 md:text-[30px]"><?php echo e($section['title']); ?></h2>
                                        </div>
                                    </div>
                                    <p class="mt-5 text-[15px] leading-7 text-slate-600">
                                        <?php echo e($section['summary']); ?>

                                    </p>
                                </div>

                                <a
                                    href="#top"
                                    class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-[11px] font-extrabold uppercase tracking-[0.16em] text-slate-700 transition hover:border-blue-100 hover:bg-blue-50 hover:text-blue-700"
                                >
                                    Back To Top
                                    <i class="fa-solid fa-arrow-up text-[10px]"></i>
                                </a>
                            </div>

                            <div class="mt-6 grid gap-3">
                                <?php $__currentLoopData = $section['points']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex gap-3 rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4">
                                        <span class="mt-1 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-white text-[11px] text-green-700 shadow-sm">
                                            <i class="fa-solid fa-check"></i>
                                        </span>
                                        <p class="text-[14px] leading-6 text-slate-700"><?php echo e($point); ?></p>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <div class="mt-5 rounded-2xl border px-4 py-4 <?php echo e($noteStyles[$section['note']['type']]); ?>">
                                <p class="text-[13px] font-semibold leading-6">
                                    <?php echo e($section['note']['text']); ?>

                                </p>
                            </div>
                        </section>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const header = document.querySelector('header');
        const sections = document.querySelectorAll('[data-policy-section]');
        const desktopLinks = document.querySelectorAll('[data-policy-link]');
        const mobileLinks = document.querySelectorAll('[data-policy-mobile-link]');
        const allLinks = [...desktopLinks, ...mobileLinks];

        const updateNavOffset = () => {
            const headerHeight = header ? header.offsetHeight : 138;
            document.documentElement.style.setProperty('--policy-nav-offset', `${headerHeight}px`);
        };

        updateNavOffset();
        window.addEventListener('resize', updateNavOffset);

        if (!sections.length || !allLinks.length) {
            return;
        }

        const setActive = (id) => {
            desktopLinks.forEach((link) => {
                link.classList.toggle('active', link.dataset.policyLink === id);
            });

            mobileLinks.forEach((link) => {
                link.classList.toggle('active', link.dataset.policyMobileLink === id);
            });
        };

        const scrollToSection = (id) => {
            const target = document.getElementById(id);
            if (!target) return;

            const navOffset = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--policy-nav-offset'), 10) || 150;
            const offset = navOffset + 24;
            const top = target.getBoundingClientRect().top + window.scrollY - offset;

            window.scrollTo({
                top,
                behavior: 'smooth',
            });
        };

        allLinks.forEach((link) => {
            link.addEventListener('click', (event) => {
                const id = link.dataset.policyLink || link.dataset.policyMobileLink;
                if (!id) return;

                event.preventDefault();
                setActive(id);
                scrollToSection(id);
                if (link.dataset.policyMobileLink) {
                    link.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                }
            });
        });

        if (!('IntersectionObserver' in window)) {
            setActive(sections[0].dataset.policySection);
            return;
        }

        const observer = new IntersectionObserver((entries) => {
            const visible = entries
                .filter((entry) => entry.isIntersecting)
                .sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0];

            if (visible) {
                setActive(visible.target.dataset.policySection);
            }
        }, {
            rootMargin: '-20% 0px -55% 0px',
            threshold: [0.2, 0.45, 0.7],
        });

        sections.forEach((section) => observer.observe(section));
        setActive(sections[0].dataset.policySection);
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views\frontend\pages\shipping-policy.blade.php ENDPATH**/ ?>