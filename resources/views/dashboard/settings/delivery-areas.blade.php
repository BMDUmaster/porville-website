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
@endphp
<div class="p-4 md:p-6">
    <form method="POST" action="{{ route('dashboard.settings.delivery-areas.update') }}" class="mx-auto max-w-4xl space-y-5">
        @csrf
        @method('PUT')

        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-black text-slate-900">Manage Delivery Areas</h1>
                <p class="mt-1 text-sm text-slate-500">Add the sectors served under each 6-digit PIN Code. Checkout will auto-fill the PIN when a customer selects a sector.</p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row">
                <button type="button" onclick="addAreaRow()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white hover:bg-slate-700">
                    <i class="fa-solid fa-plus"></i> Add Area
                </button>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3 text-sm font-bold text-white hover:bg-blue-700">
                    <i class="fa-solid fa-floppy-disk"></i> Save Areas
                </button>
            </div>
        </div>

        @if($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
        @endif

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-lg font-black text-slate-900">PIN Code &amp; Sector List</h2>
                    <p class="mt-1 text-xs text-slate-500">Each row creates one selectable sector in checkout.</p>
                </div>
                <label class="relative block sm:w-80">
                    <span class="sr-only">Search delivery areas</span>
                    <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input id="areaSearch" type="search" placeholder="Search PIN code or sector..." class="w-full rounded-xl border border-slate-300 py-2.5 pl-10 pr-3 text-sm font-semibold outline-none focus:border-blue-500">
                </label>
            </div>
            <div class="mt-5 overflow-x-auto">
                <div class="min-w-[520px]">
                    <div class="grid grid-cols-[180px_1fr_44px] gap-3 px-1 pb-2 text-xs font-bold uppercase tracking-wide text-slate-500"><span>PIN Code</span><span>Sector / Area</span><span></span></div>
                    <div id="areaRows" class="space-y-2"></div>
                </div>
            </div>
            <div class="mt-5 flex flex-col gap-3 border-t border-slate-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                <p id="areaPaginationSummary" class="text-sm font-medium text-slate-500"></p>
                <div id="areaPagination" class="flex items-center gap-2"></div>
            </div>
        </section>

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3.5 text-sm font-bold text-white hover:bg-blue-700"><i class="fa-solid fa-floppy-disk"></i> Save Delivery Areas</button>
    </form>
</div>

<script>
const savedAreas = {!! json_encode($deliveryAreaRows, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!};
const areaRows = document.getElementById('areaRows');
const areaSearch = document.getElementById('areaSearch');
const areaPagination = document.getElementById('areaPagination');
const areaPaginationSummary = document.getElementById('areaPaginationSummary');
let nextAreaIndex = 0;
let currentPage = 1;
const rowsPerPage = 10;

function addAreaRow(area = { pincode: '', sector: '' }) {
    const index = nextAreaIndex++;
    const row = document.createElement('div');
    row.className = 'grid grid-cols-[180px_1fr_44px] gap-3';
    row.innerHTML = `<input required inputmode="numeric" pattern="\\d{6}" maxlength="6" name="areas[${index}][pincode]" value="${escapeHtml(area.pincode || '')}" placeholder="e.g. 201301" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm font-semibold outline-none focus:border-blue-500"><input required maxlength="100" name="areas[${index}][sector]" value="${escapeHtml(area.sector || '')}" placeholder="e.g. Sector 63" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm font-semibold outline-none focus:border-blue-500"><button type="button" aria-label="Remove area" class="rounded-xl bg-red-50 text-red-500 hover:bg-red-100"><i class="fa-solid fa-trash-can"></i></button>`;
    row.querySelector('button').addEventListener('click', () => {
        row.remove();
        updateAreaList();
    });
    row.querySelectorAll('input').forEach(input => input.addEventListener('input', updateAreaList));
    areaRows.appendChild(row);
    areaSearch.value = '';
    currentPage = Math.ceil(areaRows.children.length / rowsPerPage);
    updateAreaList();
}

function escapeHtml(value) {
    const node = document.createElement('div');
    node.textContent = value;
    return node.innerHTML;
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
    currentPage = Math.min(currentPage, totalPages);
    const start = (currentPage - 1) * rowsPerPage;
    const visibleRows = new Set(matchingRows.slice(start, start + rowsPerPage));

    Array.from(areaRows.children).forEach(row => {
        row.classList.toggle('hidden', !visibleRows.has(row));
    });

    const first = matchingRows.length ? start + 1 : 0;
    const last = Math.min(start + rowsPerPage, matchingRows.length);
    areaPaginationSummary.textContent = matchingRows.length
        ? `Showing ${first}–${last} of ${matchingRows.length} area${matchingRows.length === 1 ? '' : 's'}`
        : 'No delivery areas found';

    areaPagination.innerHTML = '';
    if (totalPages <= 1) return;

    const createButton = (label, page, disabled = false) => {
        const button = document.createElement('button');
        button.type = 'button';
        button.textContent = label;
        button.disabled = disabled;
        button.className = 'rounded-lg border px-3 py-2 text-sm font-bold ' + (disabled
            ? 'cursor-not-allowed border-slate-200 text-slate-300'
            : 'border-slate-300 text-slate-700 hover:border-blue-500 hover:text-blue-600');
        button.addEventListener('click', () => { currentPage = page; updateAreaList(); });
        areaPagination.appendChild(button);
    };

    createButton('Previous', currentPage - 1, currentPage === 1);
    for (let page = 1; page <= totalPages; page++) {
        const button = document.createElement('button');
        button.type = 'button';
        button.textContent = page;
        button.className = 'h-9 min-w-9 rounded-lg px-3 text-sm font-bold ' + (page === currentPage ? 'bg-blue-600 text-white' : 'border border-slate-300 text-slate-700 hover:border-blue-500 hover:text-blue-600');
        button.addEventListener('click', () => { currentPage = page; updateAreaList(); });
        areaPagination.appendChild(button);
    }
    createButton('Next', currentPage + 1, currentPage === totalPages);
}

areaSearch.addEventListener('input', () => { currentPage = 1; updateAreaList(); });
(savedAreas.length ? savedAreas : [{ pincode: '', sector: '' }]).forEach(addAreaRow);
currentPage = 1;
updateAreaList();
</script>
@endsection
