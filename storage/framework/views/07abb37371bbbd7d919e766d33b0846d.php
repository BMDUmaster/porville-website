
<?php $__env->startSection('title', 'Create Account'); ?>

<?php $__env->startSection('styles'); ?>
<style>
.field-label { display:block; font-size:13px; font-weight:600; color:#1f2937; margin-bottom:6px; }
.field-label .req { color:#ef4444; margin-left:2px; }
.field-input {
    width:100%; padding:12px 14px 12px 42px;
    border:1.5px solid #e5e7eb; border-radius:10px;
    font-size:13px; outline:none; background:#f9fafb;
    color:#1f2937; transition:border-color .2s, box-shadow .2s;
    font-family:'Poppins',sans-serif;
}
.field-input:focus { border-color:#2B5BA8; box-shadow:0 0 0 3px rgba(43,91,168,.1); background:#fff; }
.field-input.error { border-color:#ef4444; background:#fef2f2; }
.field-input-no-icon {
    width:100%; padding:12px 14px;
    border:1.5px solid #e5e7eb; border-radius:10px;
    font-size:13px; outline:none; background:#f9fafb;
    color:#1f2937; transition:border-color .2s, box-shadow .2s;
    font-family:'Poppins',sans-serif;
}
.field-input-no-icon:focus { border-color:#2B5BA8; box-shadow:0 0 0 3px rgba(43,91,168,.1); background:#fff; }
.field-icon { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:14px; pointer-events:none; }
.eye-btn { position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#9ca3af; font-size:14px; padding:4px; }
.eye-btn:hover { color:#2B5BA8; }
.error-msg { margin-top:5px; font-size:11px; color:#ef4444; display:none; align-items:center; gap:4px; }
.error-msg.show { display:flex; }
/* Toggle switch */
.tog { width:46px; height:26px; border-radius:13px; background:#d1d5db; position:relative; flex-shrink:0; transition:background .2s; cursor:pointer; border:none; }
.tog.on { background:#16a34a; }
.tog-dot { position:absolute; top:3px; left:3px; width:20px; height:20px; border-radius:50%; background:#fff; box-shadow:0 1px 4px rgba(0,0,0,.2); transition:transform .2s; }
.tog.on .tog-dot { transform:translateX(20px); }
/* Password strength */
.str-bar { height:3px; border-radius:3px; flex:1; background:#e5e7eb; transition:background .25s; }
/* Country select */
.country-select {
    width:100%; padding:12px 14px 12px 38px;
    border:1.5px solid #e5e7eb; border-radius:10px;
    font-size:13px; outline:none; background:#f9fafb;
    color:#1f2937; transition:border-color .2s;
    font-family:'Poppins',sans-serif; appearance:none; cursor:pointer;
}
.country-select:focus { border-color:#2B5BA8; box-shadow:0 0 0 3px rgba(43,91,168,.1); background:#fff; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<main class="flex-1 py-10" style="background:#f0f2f5;">
<div class="max-w-[680px] mx-auto px-4">

    <?php if($errors->any()): ?>
    <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-600">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><p class="flex items-center gap-2"><i class="fa-solid fa-circle-exclamation text-xs"></i><?php echo e($error); ?></p><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    <?php if(session('success')): ?>
    <div class="mb-5 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700 flex items-center gap-2">
        <i class="fa-solid fa-circle-check"></i> <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-8 py-8">

            
            <div class="mb-7">
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight uppercase">CREATE ACCOUNT</h1>
                <p class="text-sm text-gray-400 mt-1">Join FarmSea for exclusive benefits</p>
            </div>

            <form method="POST" action="<?php echo e(route('frontend.register.post')); ?>" id="signupForm" novalidate>
                <?php echo csrf_field(); ?>

                
                <div class="mb-5">
                    <label class="field-label">Full Name <span class="req">*</span></label>
                    <div class="relative">
                        <i class="fa-regular fa-user field-icon"></i>
                        <input type="text" name="name" id="name" value="<?php echo e(old('name')); ?>"
                               placeholder="John Doe" class="field-input" required>
                    </div>
                    <div class="error-msg" id="nameErr"><i class="fa-solid fa-circle-exclamation text-xs"></i><span>Full name is required</span></div>
                </div>

                
                <div class="mb-5">
                    <label class="field-label">Email Address <span class="req">*</span></label>
                    <div class="relative">
                        <i class="fa-regular fa-envelope field-icon"></i>
                        <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>"
                               placeholder="john@example.com" class="field-input" required>
                    </div>
                    <div class="error-msg" id="emailErr"><i class="fa-solid fa-circle-exclamation text-xs"></i><span>Enter a valid email address</span></div>
                </div>

                
                <div class="mb-5">
                    <label class="field-label">Mobile Number <span class="req">*</span></label>
                    <div class="relative flex items-center border-[1.5px] border-gray-200 rounded-[10px] bg-[#f9fafb] focus-within:border-[#2B5BA8] focus-within:shadow-[0_0_0_3px_rgba(43,91,168,.1)] focus-within:bg-white transition-all overflow-hidden">
                        <div class="flex items-center gap-1.5 px-3 border-r border-gray-200 h-full py-3 flex-shrink-0 cursor-pointer select-none" onclick="toggleDialCode()">
                            <span id="flag-display" class="text-base">🇮🇳</span>
                            <span id="dial-display" class="text-xs font-bold text-gray-600">+91</span>
                            <i class="fa-solid fa-angle-down text-[10px] text-gray-400"></i>
                        </div>
                        <input type="tel" name="phone" id="phone" value="<?php echo e(old('phone')); ?>"
                               placeholder="Enter mobile number"
                               class="flex-1 px-3 py-3 text-sm outline-none bg-transparent font-[Poppins] text-gray-800">
                    </div>
                    
                    <div id="dialDropdown" class="hidden absolute z-50 bg-white border border-gray-200 rounded-xl shadow-xl mt-1 w-64 max-h-52 overflow-y-auto">
                        <?php $__currentLoopData = [['🇮🇳','India','+91'],['🇺🇸','United States','+1'],['🇬🇧','United Kingdom','+44'],['🇦🇺','Australia','+61'],['🇨🇦','Canada','+1'],['🇦🇪','UAE','+971'],['🇸🇬','Singapore','+65'],['🇩🇪','Germany','+49'],['🇫🇷','France','+33'],['🇯🇵','Japan','+81']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$flag,$country,$code]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button type="button" onclick="selectDial('<?php echo e($flag); ?>','<?php echo e($code); ?>')"
                                class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-blue-50 text-sm text-left">
                            <span class="text-base"><?php echo e($flag); ?></span>
                            <span class="flex-1 text-gray-700"><?php echo e($country); ?></span>
                            <span class="text-gray-400 text-xs font-bold"><?php echo e($code); ?></span>
                        </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="error-msg" id="phoneErr"><i class="fa-solid fa-circle-exclamation text-xs"></i><span>Mobile number is required</span></div>
                </div>

                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="field-label">Password <span class="req">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-lock field-icon"></i>
                            <input type="password" name="password" id="pw"
                                   placeholder="Min. 8 chars" class="field-input pr-10"
                                   oninput="checkStrength(this.value)" required>
                            <button type="button" class="eye-btn" onclick="togglePw('pw','ei1')">
                                <i id="ei1" class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        
                        <div class="flex gap-1 mt-2">
                            <div class="str-bar" id="sb1"></div>
                            <div class="str-bar" id="sb2"></div>
                            <div class="str-bar" id="sb3"></div>
                            <div class="str-bar" id="sb4"></div>
                        </div>
                        <div class="error-msg" id="pwErr"><i class="fa-solid fa-circle-exclamation text-xs"></i><span>Min. 8 characters required</span></div>
                    </div>
                    <div>
                        <label class="field-label">Confirm Password <span class="req">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-lock field-icon"></i>
                            <input type="password" name="password_confirmation" id="cpw"
                                   placeholder="Re-enter" class="field-input pr-10" required>
                            <button type="button" class="eye-btn" onclick="togglePw('cpw','ei2')">
                                <i id="ei2" class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        <div class="error-msg" id="cpwErr"><i class="fa-solid fa-circle-exclamation text-xs"></i><span>Passwords do not match</span></div>
                    </div>
                </div>

                
                <div class="flex items-center gap-3 my-6">
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-location-dot text-[#2B5BA8] text-xs"></i>
                        <span class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">Shipping Address</span>
                    </div>
                    <button type="button" onclick="autoDetect()"
                            class="flex items-center gap-1.5 bg-[#2B5BA8] hover:bg-[#1e4080] text-white text-[11px] font-bold px-3 py-1.5 rounded-lg transition">
                        <i id="locIcon" class="fa-solid fa-location-crosshairs text-xs"></i>
                        <span id="locText">Auto Detect</span>
                    </button>
                    <div class="flex-1 h-px bg-gray-200"></div>
                </div>

                
                <div class="mb-4">
                    <label class="field-label">Street Address <span class="req">*</span></label>
                    <div class="relative">
                        <i class="fa-solid fa-house field-icon"></i>
                        <input type="text" name="address" id="address" value="<?php echo e(old('address')); ?>"
                               placeholder="123 Main Street" class="field-input">
                    </div>
                    <div class="error-msg" id="addrErr"><i class="fa-solid fa-circle-exclamation text-xs"></i><span>Street address is required</span></div>
                </div>

                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="field-label">City <span class="req">*</span></label>
                        <input type="text" name="city" id="city" value="<?php echo e(old('city')); ?>"
                               placeholder="New York" class="field-input-no-icon">
                        <div class="error-msg" id="cityErr"><i class="fa-solid fa-circle-exclamation text-xs"></i><span>City is required</span></div>
                    </div>
                    <div>
                        <label class="field-label">State <span class="req">*</span></label>
                        <input type="text" name="state" id="state" value="<?php echo e(old('state')); ?>"
                               placeholder="NY" class="field-input-no-icon">
                        <div class="error-msg" id="stateErr"><i class="fa-solid fa-circle-exclamation text-xs"></i><span>State is required</span></div>
                    </div>
                </div>

                
                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="field-label">ZIP Code <span class="req">*</span></label>
                        <input type="text" name="pincode" id="pincode" value="<?php echo e(old('pincode')); ?>"
                               placeholder="10001" class="field-input-no-icon">
                        <div class="error-msg" id="zipErr"><i class="fa-solid fa-circle-exclamation text-xs"></i><span>ZIP code is required</span></div>
                    </div>
                    <div>
                        <label class="field-label">Country <span class="req">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-base pointer-events-none">🌍</span>
                            <select name="country" id="country" class="country-select">
                                <option value="">Select country</option>
                                <?php $__currentLoopData = ['India','United States','United Kingdom','Australia','Canada','UAE','Singapore','Germany','France','Japan','Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($c); ?>" <?php echo e(old('country') == $c ? 'selected' : ''); ?>><?php echo e($c); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
                        <div class="error-msg" id="countryErr"><i class="fa-solid fa-circle-exclamation text-xs"></i><span>Please select a country</span></div>
                    </div>
                </div>

                
                <label class="flex items-center gap-4 bg-gray-50 border border-gray-200 hover:border-green-400 rounded-xl px-4 py-3.5 cursor-pointer select-none transition mb-5">
                    <i class="fa-solid fa-truck text-green-600 text-base flex-shrink-0"></i>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-700 leading-tight">Billing same as shipping</p>
                        <p class="text-xs text-gray-400 mt-0.5">Use above address for billing too</p>
                    </div>
                    <input type="checkbox" id="sameAddr" class="sr-only" checked>
                    <button type="button" id="togBtn" class="tog on" onclick="toggleBilling()">
                        <div class="tog-dot"></div>
                    </button>
                </label>

                
                <div class="mb-6">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" id="terms" name="terms"
                               class="mt-0.5 w-4 h-4 rounded border-gray-300 accent-[#2B5BA8] cursor-pointer flex-shrink-0">
                        <span class="text-xs text-gray-500 leading-relaxed">
                            I agree to FarmSea's
                            <a href="<?php echo e(route('frontend.terms')); ?>" class="text-[#2B5BA8] font-semibold hover:underline">Terms of Service</a>
                            and
                            <a href="<?php echo e(route('frontend.privacy')); ?>" class="text-[#2B5BA8] font-semibold hover:underline">Privacy Policy</a>
                        </span>
                    </label>
                    <div class="error-msg" id="termsErr"><i class="fa-solid fa-circle-exclamation text-xs"></i><span>Please agree to continue</span></div>
                </div>

                
                <button type="submit" onclick="return validateForm()"
                        class="flex items-center justify-center gap-2 bg-[#2B5BA8] hover:bg-[#1e4080] active:scale-[.99] text-white font-bold text-sm px-8 py-3.5 rounded-xl transition-all shadow-sm hover:shadow-lg hover:shadow-blue-200/50 mb-4">
                    <i class="fa-solid fa-user-plus text-sm"></i>
                    Create Account →
                </button>

                <p class="text-sm text-gray-500">
                    Already have an account?
                    <a href="<?php echo e(route('frontend.login')); ?>" class="text-green-600 font-bold hover:underline">Sign in here</a>
                </p>

            </form>
        </div>
    </div>
</div>
</main>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
// ── Password toggle ──────────────────────────────────────────
function togglePw(inputId, iconId) {
    const inp  = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.className = 'fa-regular fa-eye-slash';
    } else {
        inp.type = 'password';
        icon.className = 'fa-regular fa-eye';
    }
}

// ── Password strength ────────────────────────────────────────
function checkStrength(val) {
    const bars   = [1,2,3,4].map(i => document.getElementById('sb'+i));
    const colors = ['#ef4444','#f97316','#eab308','#16a34a'];
    let score = 0;
    if (val.length >= 8)              score++;
    if (/[A-Z]/.test(val))            score++;
    if (/[0-9]/.test(val))            score++;
    if (/[^A-Za-z0-9]/.test(val))     score++;
    bars.forEach((b, i) => { b.style.background = i < score ? colors[score-1] : '#e5e7eb'; });
}

// ── Dial code dropdown ───────────────────────────────────────
function toggleDialCode() {
    document.getElementById('dialDropdown').classList.toggle('hidden');
}
function selectDial(flag, code) {
    document.getElementById('flag-display').textContent = flag;
    document.getElementById('dial-display').textContent = code;
    document.getElementById('dialDropdown').classList.add('hidden');
}
document.addEventListener('click', function(e) {
    const dd = document.getElementById('dialDropdown');
    if (!e.target.closest('[onclick="toggleDialCode()"]') && !dd.contains(e.target)) {
        dd.classList.add('hidden');
    }
});

// ── Billing toggle ───────────────────────────────────────────
function toggleBilling() {
    const btn = document.getElementById('togBtn');
    btn.classList.toggle('on');
}

// ── Auto detect location ─────────────────────────────────────
function autoDetect() {
    const icon = document.getElementById('locIcon');
    const text = document.getElementById('locText');
    if (!navigator.geolocation) { alert('Geolocation not supported'); return; }
    icon.className = 'fa-solid fa-spinner fa-spin text-xs';
    text.textContent = 'Detecting...';
    navigator.geolocation.getCurrentPosition(
        pos => {
            fetch(`https://nominatim.openstreetmap.org/reverse?lat=${pos.coords.latitude}&lon=${pos.coords.longitude}&format=json`)
                .then(r => r.json())
                .then(data => {
                    const a = data.address || {};
                    if (a.road || a.suburb)   document.getElementById('address').value = (a.house_number ? a.house_number+' ' : '') + (a.road || a.suburb || '');
                    if (a.city || a.town)     document.getElementById('city').value    = a.city || a.town || a.village || '';
                    if (a.state)              document.getElementById('state').value   = a.state || '';
                    if (a.postcode)           document.getElementById('pincode').value = a.postcode || '';
                    // Try to match country
                    const sel = document.getElementById('country');
                    for (let opt of sel.options) {
                        if (opt.value.toLowerCase().includes((a.country || '').toLowerCase().split(' ')[0])) {
                            sel.value = opt.value; break;
                        }
                    }
                    icon.className = 'fa-solid fa-check text-xs';
                    text.textContent = 'Detected!';
                    setTimeout(() => { icon.className = 'fa-solid fa-location-crosshairs text-xs'; text.textContent = 'Auto Detect'; }, 2000);
                })
                .catch(() => { icon.className = 'fa-solid fa-location-crosshairs text-xs'; text.textContent = 'Auto Detect'; });
        },
        () => { icon.className = 'fa-solid fa-location-crosshairs text-xs'; text.textContent = 'Auto Detect'; alert('Location access denied.'); }
    );
}

// ── Form validation ──────────────────────────────────────────
function showErr(id, show) {
    const el = document.getElementById(id);
    if (el) { show ? el.classList.add('show') : el.classList.remove('show'); }
}
function setInputError(id, hasError) {
    const el = document.getElementById(id);
    if (!el) return;
    hasError ? el.classList.add('error') : el.classList.remove('error');
}

function validateForm() {
    let valid = true;
    const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // Name
    const name = document.getElementById('name').value.trim();
    showErr('nameErr', !name); setInputError('name', !name);
    if (!name) valid = false;

    // Email
    const email = document.getElementById('email').value.trim();
    showErr('emailErr', !emailRe.test(email)); setInputError('email', !emailRe.test(email));
    if (!emailRe.test(email)) valid = false;

    // Phone
    const phone = document.getElementById('phone').value.trim();
    showErr('phoneErr', !phone); setInputError('phone', !phone);
    if (!phone) valid = false;

    // Password
    const pw  = document.getElementById('pw').value;
    const cpw = document.getElementById('cpw').value;
    showErr('pwErr', pw.length < 8); setInputError('pw', pw.length < 8);
    if (pw.length < 8) valid = false;
    showErr('cpwErr', pw !== cpw); setInputError('cpw', pw !== cpw);
    if (pw !== cpw) valid = false;

    // Terms
    const terms = document.getElementById('terms').checked;
    showErr('termsErr', !terms);
    if (!terms) valid = false;

    return valid;
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views/frontend/signup.blade.php ENDPATH**/ ?>