@extends('layouts.dashboard')
@section('title', 'Delivery Areas')
@section('page_title', 'Delivery Area Settings')

@section('content')
@php
    $deliveryAreaRows = old('areas');

    if (! is_array($deliveryAreaRows)) {
        $deliveryAreaRows = [];

        foreach ($pinSectors as $pin => $sectors) {
            foreach ($sectors as $sector) {
                $deliveryAreaRows[] = ['pincode' => $pin, 'sector' => $sector];
            }
        }
    }

    $deliveryAreaRows = array_values($deliveryAreaRows);
@endphp
<div class="p-4 md:p-6">
    <form method="POST" action="{{ route('dashboard.settings.delivery-areas.update') }}" class="mx-auto max-w-4xl space-y-5">
        @csrf
        @method('PUT')

        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
        @endif

        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-black text-slate-900">Manage Delivery Areas</h1>
                <p class="mt-1 text-sm text-slate-500">Add every Area / Sector you deliver to with its 6-digit PIN Code. At checkout, customers can only pick from this list. The PIN auto-fills when they pick an area, and typing a PIN shows its areas.</p>
                <p class="mt-1 text-xs text-slate-400">If this list is empty, checkout accepts any address.</p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row sm:flex-shrink-0">
                <button type="button" onclick="addAreaRow()" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-bold text-slate-800 hover:border-black">
                    <i class="fa-solid fa-plus"></i> Add Area
                </button>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-black px-6 py-3 text-sm font-bold text-white hover:bg-neutral-800">
                    <i class="fa-solid fa-floppy-disk"></i> Save Areas
                </button>
            </div>
        </div>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-lg font-black text-slate-900">PIN Code &amp; Area List</h2>
                    <p class="mt-1 text-xs text-slate-500">Each row becomes one selectable area in checkout.</p>
                </div>
                <label class="relative block sm:w-80">
                    <span class="sr-only">Search delivery areas</span>
                    <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input id="areaSearch" type="search" placeholder="Search PIN code or area..." class="w-full rounded-xl border border-slate-300 py-2.5 pl-10 pr-3 text-sm font-semibold outline-none focus:border-amber-500">
                </label>
            </div>
            <div class="mt-5 overflow-x-auto">
                <div class="min-w-[480px]">
                    <div class="grid grid-cols-[160px_1fr_44px] gap-3 px-1 pb-2 text-xs font-bold uppercase tracking-wide text-slate-500"><span>PIN Code</span><span>Area / Sector</span><span></span></div>
                    <div id="areaRows" class="space-y-2"></div>
                </div>
            </div>
            <div class="mt-5 flex flex-col gap-3 border-t border-slate-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                <p id="areaPaginationSummary" class="text-sm font-medium text-slate-500"></p>
                <div id="areaPagination" class="flex flex-wrap items-center gap-2"></div>
            </div>
        </section>

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-black px-6 py-3.5 text-sm font-bold text-white hover:bg-neutral-800"><i class="fa-solid fa-floppy-disk"></i> Save Delivery Areas</button>
    </form>
</div>

<script>
const savedAreas = @json($deliveryAreaRows);
const areaRows = document.getElementById('areaRows');
const areaSearch = document.getElementById('areaSearch');
const areaPagination = document.getElementById('areaPagination');
const areaPaginationSummary = document.getElementById('areaPaginationSummary');
let nextAreaIndex = 0;
let currentPage = 1;
const rowsPerPage = 10;

function addAreaRow(area = { pincode: '', sector: '' }, focus = true) {
    const index = nextAreaIndex++;
    const row = document.createElement('div');
    row.className = 'grid grid-cols-[160px_1fr_44px] gap-3';
    row.innerHTML = `<input required inputmode="numeric" pattern="\\d{6}" maxlength="6" name="areas[${index}][pincode]" placeholder="e.g. 281401" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm font-semibold outline-none focus:border-amber-500"><input required maxlength="100" name="areas[${index}][sector]" placeholder="e.g. Sector 63" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm font-semibold outline-none focus:border-amber-500"><button type="button" aria-label="Remove area" class="rounded-xl bg-red-50 text-red-500 hover:bg-red-100"><i class="fa-solid fa-trash-can"></i></button>`;

    const [pinInput, sectorInput] = row.querySelectorAll('input');
    pinInput.value = area.pincode || '';
    sectorInput.value = area.sector || '';
    pinInput.addEventListener('input', () => { pinInput.value = pinInput.value.replace(/\D/g, '').slice(0, 6); });

    row.querySelector('button').addEventListener('click', () => {
        row.remove();
        updateAreaList();
    });
    areaRows.appendChild(row);

    if (focus) {
        areaSearch.value = '';
        currentPage = Math.ceil(areaRows.children.length / rowsPerPage);
        updateAreaList();
        pinInput.focus();
    }
}

function getMatchingRows() {
    const query = areaSearch.value.trim().toLowerCase();
    return Array.from(areaRows.children).filter(row => {
        const values = Array.from(row.querySelectorAll('input')).map(input => input.value.toLowerCase());
        return !query || values.some(value => value.includes(query));
    });
}

function updateAreaList() {
    const matchingRows = getMatchingRows();
    const totalPages = Math.max(1, Math.ceil(matchingRows.length / rowsPerPage));
    currentPage = Math.min(Math.max(currentPage, 1), totalPages);
    const start = (currentPage - 1) * rowsPerPage;
    const visibleRows = new Set(matchingRows.slice(start, start + rowsPerPage));

    Array.from(areaRows.children).forEach(row => {
        row.classList.toggle('hidden', !visibleRows.has(row));
    });

    const first = matchingRows.length ? start + 1 : 0;
    const last = Math.min(start + rowsPerPage, matchingRows.length);
    areaPaginationSummary.textContent = matchingRows.length
        ? `Showing ${first}-${last} of ${matchingRows.length} area${matchingRows.length === 1 ? '' : 's'}`
        : (areaRows.children.length ? 'No matching areas' : 'No delivery areas yet. Click "Add Area" to start.');

    areaPagination.innerHTML = '';
    if (totalPages <= 1) return;

    const addButton = (label, page, { disabled = false, active = false } = {}) => {
        const button = document.createElement('button');
        button.type = 'button';
        button.textContent = label;
        button.disabled = disabled;
        button.className = 'h-9 min-w-9 rounded-lg px-3 text-sm font-bold ' + (active
            ? 'bg-black text-white'
            : disabled
                ? 'cursor-not-allowed border border-slate-200 text-slate-300'
                : 'border border-slate-300 text-slate-700 hover:border-amber-500 hover:text-amber-700');
        button.addEventListener('click', () => { currentPage = page; updateAreaList(); });
        areaPagination.appendChild(button);
    };

    addButton('Previous', currentPage - 1, { disabled: currentPage === 1 });
    for (let page = 1; page <= totalPages; page++) {
        addButton(String(page), page, { active: page === currentPage });
    }
    addButton('Next', currentPage + 1, { disabled: currentPage === totalPages });
}

// Browsers can't show "required" errors on hidden rows, so jump to the first incomplete row.
document.querySelector('form').addEventListener('invalid', (event) => {
    const row = event.target.closest('#areaRows > div');
    if (!row || !row.classList.contains('hidden')) return;
    areaSearch.value = '';
    currentPage = Math.floor(Array.from(areaRows.children).indexOf(row) / rowsPerPage) + 1;
    updateAreaList();
}, true);

areaSearch.addEventListener('input', () => { currentPage = 1; updateAreaList(); });
savedAreas.forEach(area => addAreaRow(area, false));
currentPage = 1;
updateAreaList();
</script>
@endsection
