<?php $__env->startSection('title', 'Privacy Policy'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    html { scroll-behavior: smooth; }
    .privacy-anchor { scroll-margin-top: 205px; }
    .privacy-link { transition: background-color .2s ease, color .2s ease, transform .2s ease; }
    .privacy-link:hover { transform: translateX(3px); }

    @media (min-width: 1024px) {
        .privacy-sidebar {
            position: -webkit-sticky;
            position: sticky;
            top: 195px;
            align-self: start;
            z-index: 20;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $effectiveDate = 'July 30, 2026';
    $sections = [
        ['id' => 'information', 'icon' => 'fa-database', 'title' => 'Information We Collect'],
        ['id' => 'usage', 'icon' => 'fa-sliders', 'title' => 'How We Use Information'],
        ['id' => 'sharing', 'icon' => 'fa-share-nodes', 'title' => 'When We Share Information'],
        ['id' => 'payments', 'icon' => 'fa-credit-card', 'title' => 'Payments & Third Parties'],
        ['id' => 'cookies', 'icon' => 'fa-cookie-bite', 'title' => 'Cookies & Device Data'],
        ['id' => 'security', 'icon' => 'fa-shield-halved', 'title' => 'Security & Retention'],
        ['id' => 'rights', 'icon' => 'fa-user-check', 'title' => 'Your Choices & Rights'],
        ['id' => 'children', 'icon' => 'fa-child-reaching', 'title' => "Children's Privacy"],
        ['id' => 'updates', 'icon' => 'fa-rotate', 'title' => 'Policy Updates & Contact'],
    ];
?>

<section id="privacy-top" class="relative bg-[linear-gradient(180deg,#eff6ff_0%,#f8fafc_40%,#ffffff_100%)]">
    <div class="pointer-events-none absolute inset-x-0 top-0 h-[420px] bg-[radial-gradient(circle_at_12%_10%,rgba(37,99,235,0.15),transparent_34%),radial-gradient(circle_at_88%_18%,rgba(34,197,94,0.14),transparent_32%)]"></div>

    <div class="relative mx-auto max-w-7xl px-4 py-10 md:px-6 md:py-14">
        <div class="rounded-[30px] border border-white/70 bg-white/90 shadow-[0_24px_80px_rgba(15,23,42,0.09)]">
            <div class="relative overflow-hidden border-b border-slate-100 px-6 py-9 md:px-10 md:py-12">
                <div class="absolute -right-16 -top-20 h-64 w-64 rounded-full bg-blue-50"></div>
                <div class="relative flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <span class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-4 py-2 text-[11px] font-extrabold uppercase tracking-[0.2em] text-white shadow-sm">
                            <i class="fa-solid fa-lock text-[10px]"></i> Privacy Policy
                        </span>
                        <h1 class="mt-5 text-3xl font-extrabold leading-tight text-slate-950 md:text-5xl">
                            Your privacy matters
                            <span class="block text-blue-600">at every FarmSea order.</span>
                        </h1>
                        <p class="mt-5 max-w-2xl text-[15px] leading-7 text-slate-600">
                            This policy explains what information FarmSea collects when you browse, create an account, place an order, contact support, or use our delivery services—and how we keep that information responsible and secure.
                        </p>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2 lg:min-w-[390px]">
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Effective date</p>
                            <p class="mt-2 text-sm font-extrabold text-slate-900"><?php echo e($effectiveDate); ?></p>
                        </div>
                        <div class="rounded-2xl border border-green-100 bg-green-50 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-green-600">Privacy support</p>
                            <a href="mailto:support@farmsea.com" class="mt-2 block text-sm font-extrabold text-green-800 hover:text-green-950">support@farmsea.com</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-8 p-5 md:p-8 lg:grid-cols-[270px_minmax(0,1fr)] lg:p-10">
                <aside class="privacy-sidebar">
                    <div class="rounded-[24px] border border-slate-100 bg-slate-50 p-4 shadow-[0_12px_30px_rgba(15,23,42,0.05)]">
                        <p class="px-2 text-[10px] font-extrabold uppercase tracking-[0.24em] text-slate-400">On this page</p>
                        <nav class="mt-3 grid grid-cols-1 gap-1 sm:grid-cols-2 lg:grid-cols-1">
                            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="#<?php echo e($section['id']); ?>" class="privacy-link flex items-center gap-3 rounded-xl px-3 py-2.5 text-[12px] font-bold text-slate-600 hover:bg-white hover:text-blue-700">
                                    <i class="fa-solid <?php echo e($section['icon']); ?> w-4 text-center text-blue-500"></i>
                                    <?php echo e($section['title']); ?>

                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </nav>
                    </div>
                </aside>

                <div class="min-w-0 space-y-6 text-[14px] leading-7 text-slate-600">
                    <div class="rounded-2xl border border-amber-100 bg-amber-50 px-5 py-4 text-[13px] leading-6 text-amber-900">
                        <strong class="font-extrabold">Please note:</strong> By using the FarmSea website or services, you acknowledge this policy. If you do not agree, please discontinue use of the platform. We may update this policy when our services, operations, or legal obligations change.
                    </div>

                    <article id="information" class="privacy-anchor rounded-[24px] border border-slate-100 bg-white p-6 shadow-[0_12px_35px_rgba(15,23,42,0.045)] md:p-8">
                        <h2 class="text-2xl font-extrabold text-slate-950">1. Information We Collect</h2>
                        <p class="mt-4">We collect only the information reasonably required to provide and improve FarmSea services. Depending on how you use the platform, this may include:</p>
                        <div class="mt-5 grid gap-3 md:grid-cols-2">
                            <?php $__currentLoopData = [
                                ['Account details', 'Your name, mobile number, email address, login credentials, and saved profile details.'],
                                ['Order & delivery details', 'Products ordered, delivery address, pin code, sector, delivery slot, recipient details, order history, coupons, and special instructions.'],
                                ['Support & feedback', 'Messages, reviews, enquiries, complaint details, photographs you submit, and records needed to resolve your request.'],
                                ['Technical information', 'IP address, browser and device type, pages visited, access time, referral source, session activity, and diagnostic logs.'],
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $text]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                    <h3 class="font-extrabold text-slate-900"><?php echo e($label); ?></h3>
                                    <p class="mt-1 text-[13px] leading-6"><?php echo e($text); ?></p>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <p class="mt-5">If you order for another person, you are responsible for ensuring that they agree to FarmSea using their contact and address details for order fulfilment.</p>
                    </article>

                    <article id="usage" class="privacy-anchor rounded-[24px] border border-slate-100 bg-white p-6 shadow-[0_12px_35px_rgba(15,23,42,0.045)] md:p-8">
                        <h2 class="text-2xl font-extrabold text-slate-950">2. How We Use Information</h2>
                        <p class="mt-4">We use information to operate FarmSea and serve you, including to:</p>
                        <ul class="mt-4 grid gap-3 md:grid-cols-2">
                            <?php $__currentLoopData = ['Create and manage your account.', 'Confirm, prepare, deliver, track, cancel, or refund orders.', 'Verify delivery availability, addresses, and payment status.', 'Send service messages, invoices, order updates, and support responses.', 'Apply coupons, prevent misuse, and detect suspicious activity.', 'Improve product selection, website performance, safety, and customer experience.', 'Show relevant products or offers where permitted.', 'Meet tax, accounting, fraud-prevention, and other legal obligations.']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="flex gap-3 rounded-xl bg-blue-50/60 px-4 py-3 text-[13px] text-slate-700"><i class="fa-solid fa-check mt-1 text-green-600"></i><span><?php echo e($point); ?></span></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </article>

                    <article id="sharing" class="privacy-anchor rounded-[24px] border border-slate-100 bg-white p-6 shadow-[0_12px_35px_rgba(15,23,42,0.045)] md:p-8">
                        <h2 class="text-2xl font-extrabold text-slate-950">3. When We Share Information</h2>
                        <p class="mt-4 font-bold text-slate-800">FarmSea does not sell your personal information.</p>
                        <p class="mt-3">We may share limited information on a need-to-know basis with delivery partners, payment processors, cloud and hosting providers, communication providers, analytics or security services, professional advisers, and authorities where required by law. Delivery personnel receive only the details needed to complete or support your order.</p>
                        <p class="mt-3">Information may also be transferred as part of a merger, acquisition, reorganisation, or sale of business assets, subject to applicable law and appropriate confidentiality safeguards.</p>
                    </article>

                    <article id="payments" class="privacy-anchor rounded-[24px] border border-slate-100 bg-white p-6 shadow-[0_12px_35px_rgba(15,23,42,0.045)] md:p-8">
                        <h2 class="text-2xl font-extrabold text-slate-950">4. Payments & Third-Party Services</h2>
                        <p class="mt-4">FarmSea may offer Cash on Delivery, UPI, or online payment options. Online payment details are handled through the applicable payment provider and are governed by that provider's privacy and security terms. FarmSea may receive transaction identifiers, payment method, amount, and payment status, but does not intend to store complete card numbers, CVV, UPI PINs, or banking passwords.</p>
                        <p class="mt-3">Links to external websites or services are provided for convenience. Their privacy practices are outside FarmSea's control, so please review their policies before submitting information.</p>
                    </article>

                    <article id="cookies" class="privacy-anchor rounded-[24px] border border-slate-100 bg-white p-6 shadow-[0_12px_35px_rgba(15,23,42,0.045)] md:p-8">
                        <h2 class="text-2xl font-extrabold text-slate-950">5. Cookies & Device Data</h2>
                        <p class="mt-4">Cookies and similar technologies help keep you signed in, remember cart and preference information, protect sessions, understand website performance, and improve navigation. You can block or remove cookies through your browser settings, although login, cart, checkout, or other features may then work incorrectly.</p>
                    </article>

                    <article id="security" class="privacy-anchor rounded-[24px] border border-slate-100 bg-white p-6 shadow-[0_12px_35px_rgba(15,23,42,0.045)] md:p-8">
                        <h2 class="text-2xl font-extrabold text-slate-950">6. Security & Data Retention</h2>
                        <p class="mt-4">We use reasonable administrative, technical, and organisational safeguards designed to protect information from unauthorised access, alteration, loss, or disclosure. Access is restricted to people and service providers who need the information for their work.</p>
                        <p class="mt-3">No internet transmission or storage system is completely secure. We retain information only for as long as needed for the purposes described here, including order support, record keeping, dispute resolution, fraud prevention, and legal or tax compliance. Information may then be deleted or anonymised where reasonably possible.</p>
                    </article>

                    <article id="rights" class="privacy-anchor rounded-[24px] border border-slate-100 bg-white p-6 shadow-[0_12px_35px_rgba(15,23,42,0.045)] md:p-8">
                        <h2 class="text-2xl font-extrabold text-slate-950">7. Your Choices & Rights</h2>
                        <p class="mt-4">Subject to applicable law, you may request access to or correction of your personal information, ask us to delete information that is no longer required, withdraw consent for optional processing, or opt out of promotional communications. You may also update available profile and address details through your account.</p>
                        <p class="mt-3">Send privacy requests to <a href="mailto:support@farmsea.com" class="font-extrabold text-blue-700 hover:text-blue-900">support@farmsea.com</a> using the subject “Privacy Request”. We may verify your identity before acting. Some order, payment, tax, fraud-prevention, or legal records cannot be deleted immediately. Removing essential account data may also prevent us from continuing to provide some services.</p>
                    </article>

                    <article id="children" class="privacy-anchor rounded-[24px] border border-slate-100 bg-white p-6 shadow-[0_12px_35px_rgba(15,23,42,0.045)] md:p-8">
                        <h2 class="text-2xl font-extrabold text-slate-950">8. Children's Privacy</h2>
                        <p class="mt-4">FarmSea is intended for adults capable of placing and paying for orders. We do not knowingly collect personal information directly from children under 18. If you believe a child has submitted personal information without appropriate consent, contact us so we can review and take reasonable action.</p>
                    </article>

                    <article id="updates" class="privacy-anchor rounded-[24px] border border-blue-100 bg-[linear-gradient(135deg,#eff6ff,#f0fdf4)] p-6 md:p-8">
                        <h2 class="text-2xl font-extrabold text-slate-950">9. Policy Updates & Contact</h2>
                        <p class="mt-4">FarmSea may update this policy to reflect changes in services, technology, operations, or law. The revised version takes effect when posted on this page, and the effective date above will be updated. Please review this page periodically.</p>
                        <div class="mt-6 flex flex-col gap-4 rounded-2xl border border-white bg-white/80 p-5 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-slate-400">Privacy questions or grievances</p>
                                <p class="mt-1 font-extrabold text-slate-900">FarmSea Customer Support · Delhi NCR, India</p>
                            </div>
                            <a href="mailto:support@farmsea.com" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-[12px] font-extrabold text-white transition hover:bg-blue-700">
                                <i class="fa-solid fa-envelope"></i> support@farmsea.com
                            </a>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views\frontend\pages\privacy-policy.blade.php ENDPATH**/ ?>