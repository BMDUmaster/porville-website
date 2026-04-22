@extends('layouts.dashboard')
@section('title', 'Categories')
@section('page_title', 'Category Management')

@section('content')
<div class="p-4 sm:p-6">

    {{-- Toolbar --}}
    <div class="bg-white rounded-xl border p-4 mb-4 flex flex-col md:flex-row gap-3 items-center">
        <form method="GET" class="flex-1 relative w-full">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search categories..."
                   class="w-full pl-9 pr-4 py-2.5 border rounded-lg text-sm outline-none focus:border-blue-400">
        </form>
        <button onclick="openModal('addModal')"
                class="bg-purple-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 w-full md:w-auto justify-center">
            <i class="fa-solid fa-plus"></i> Add New Category
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border overflow-x-auto">
        <table class="w-full text-left min-w-[700px]">
            <thead style="background: linear-gradient(to right, #7e22ce, #4f46e5); color: white;">
                <tr>
                    <th class="px-4 py-3 text-xs uppercase">Sr.No.</th>
                    <th class="px-4 py-3 text-xs uppercase">Image</th>
                    <th class="px-4 py-3 text-xs uppercase">Category</th>
                    <th class="px-4 py-3 text-xs uppercase">Sub-cats</th>
                    <th class="px-4 py-3 text-xs uppercase">Date</th>
                    <th class="px-4 py-3 text-xs uppercase text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($categories as $i => $cat)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $categories->firstItem() + $i }}</td>
                    <td class="px-4 py-3">
                        @if($cat->image)
                            <img src="{{ asset('storage/'.$cat->image) }}" class="w-12 h-12 rounded-lg object-cover">
                        @else
                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                <i class="fa-regular fa-image"></i>
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <p class="font-semibold text-sm text-gray-800">{{ $cat->name }}</p>
                        <p class="text-xs text-gray-400 truncate max-w-[200px]">{{ $cat->description ?? 'No description' }}</p>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $cat->children->count() }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $cat->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center gap-2">
                            <button onclick="openEditModal({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ addslashes($cat->description ?? '') }}')"
                                    class="p-2 hover:bg-indigo-100 text-indigo-600 rounded-lg text-xs">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            <form method="POST" action="{{ route('dashboard.categories.destroy', $cat) }}"
                                  onsubmit="return confirm('Delete this category?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 hover:bg-red-100 text-red-500 rounded-lg text-xs">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No categories found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $categories->withQueryString()->links() }}</div>
</div>

{{-- Add Modal --}}
<div id="addModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl">
        <div class="px-7 py-5 border-b flex justify-between items-center">
            <h2 class="text-xl font-semibold">Add Category</h2>
            <button onclick="closeModal('addModal')" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
        </div>
        <form method="POST" action="{{ route('dashboard.categories.store') }}" enctype="multipart/form-data" class="px-7 py-6 space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1.5">Category Image</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Category Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Fresh Fish"
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-purple-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5 text-gray-600">Description</label>
                <textarea name="description" rows="5" placeholder="Enter category details..."
                          class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none resize-none"></textarea>
            </div>
            <div class="flex gap-3 pt-1">
                <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-xl font-medium text-sm">Save Category</button>
                <button type="button" onclick="closeModal('addModal')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 py-3 rounded-xl font-medium text-sm">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl">
        <div class="px-7 py-5 border-b flex justify-between items-center">
            <h2 class="text-xl font-semibold">Edit Category</h2>
            <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data" class="px-7 py-6 space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium mb-1.5">Update Image</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Category Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="editName" required
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-purple-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5 text-gray-600">Description</label>
                <textarea name="description" id="editDesc" rows="5"
                          class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none resize-none"></textarea>
            </div>
            <div class="flex gap-3 pt-1">
                <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-xl font-medium text-sm">Update Category</button>
                <button type="button" onclick="closeModal('editModal')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 py-3 rounded-xl font-medium text-sm">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    document.getElementById(id).classList.add('flex');
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.getElementById(id).classList.remove('flex');
}
function openEditModal(id, name, desc) {
    document.getElementById('editName').value = name;
    document.getElementById('editDesc').value = desc;
    document.getElementById('editForm').action = '/categories/' + id;
    openModal('editModal');
}
</script>
@endsection
