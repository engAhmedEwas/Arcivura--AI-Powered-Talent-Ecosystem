@extends('layouts.admin')

@section('title', 'Category Architecture')

@section('content')
<div class="max-w-[1600px] mx-auto">
    {{-- Header Section --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Manage Categories</h2>
            <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest font-bold">Structural Data Control</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="bg-amber-500 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:bg-amber-600 transition-all flex items-center gap-2">
            <i class="fas fa-plus-circle"></i>
            <span>Create New Entity</span>
        </a>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-400 text-[10px] uppercase font-black border-b border-gray-50">
                        <th class="px-8 py-5">Classification Name</th>
                        <th class="px-8 py-5">System Route (Slug)</th>
                        <th class="px-8 py-5 text-center">Intelligence Density</th>
                        <th class="px-8 py-5 text-right">Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($categories as $category)
                    <tr class="hover:bg-gray-50/50 transition-all" id="row-{{ $category->id }}">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-500">
                                    <i class="fas fa-folder-open text-xs"></i>
                                </div>
                                <span class="font-bold text-gray-700 entity-name">{{ $category->name }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <code class="text-[11px] font-mono bg-gray-100 text-gray-500 px-3 py-1.5 rounded-lg entity-slug">/{{ $category->slug }}</code>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="inline-flex items-center bg-blue-50 text-blue-600 text-[11px] font-black px-4 py-1.5 rounded-full uppercase">
                                {{ $category->keywords_count ?? 0 }} Keywords Linked
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <button type="button" 
                                        onclick="openCategoryEdit('{{ $category->id }}', '{{ $category->name }}')" 
                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:bg-indigo-50 hover:text-indigo-600 transition-all shadow-sm">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>

                                <button form="delete-cat-{{ $category->id }}" type="button" class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-600 transition-all delete-btn">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                </button>

                                <form id="delete-cat-{{ $category->id }}" action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-20 text-center text-gray-400">Architecture is empty.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-6">
        {{ $categories->links() }}
    </div>
</div>

{{-- Edit Modal Architecture --}}
<div id="editCategoryModal" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm overflow-y-auto h-full w-full z-[100] flex items-center justify-center p-4">
    <div class="relative w-full max-w-md shadow-[0_30px_60px_-15px_rgba(0,0,0,0.3)] rounded-[2.5rem] bg-white overflow-hidden animate-modal-up">
        <div class="p-10">
            {{-- Header --}}
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-xl font-black text-gray-800 tracking-tight">Modify Unit</h3>
                <button type="button" onclick="closeGlobalModal('editCategoryModal')" class="w-8 h-8 rounded-full bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all flex items-center justify-center text-lg">&times;</button>
            </div>
            
            <form id="editCategoryForm" onsubmit="submitCategoryUpdate(event)">
                @csrf
                <input type="hidden" id="cat_id" name="id">
                
                <div class="space-y-6">
                    {{-- Input Field --}}
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">New Identity</label>
                        <input type="text" id="cat_name" name="name" required 
                               class="w-full bg-gray-50 border border-gray-100 rounded-2xl p-5 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-200 outline-none transition-all font-bold text-gray-700">
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-3 mt-10">
                        <button type="button" onclick="closeGlobalModal('editCategoryModal')" 
                                class="flex-1 px-8 py-4 bg-gray-50 text-gray-400 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-100 transition-all">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="flex-1 px-8 py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all">
                            Update Registry
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes modal-up {
        from { opacity: 0; transform: translateY(20px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .animate-modal-up { 
        animation: modal-up 0.4s cubic-bezier(0.16, 1, 0.3, 1); 
    }
</style>

<script>
    /**
     * Logic for Categories
     */

    function openCategoryEdit(id, name) {
        openGlobalModal('editCategoryModal', {
            'cat_id': id,
            'cat_name': name
        });
    }

    // 2. إرسال طلب التحديث عبر AJAX
    function submitCategoryUpdate(event) {
        const id = document.getElementById('cat_id').value;
        const url = "/admin/categories/" + id;

        // استدعاء الدالة العالمية handleAjaxUpdate الموجودة في Layout
        handleAjaxUpdate(event, url, (result) => {
            const row = document.getElementById("row-" + id);
            if (row) {
                row.querySelector('.entity-name').innerText = document.getElementById('cat_name').value;
                if (result.new_slug) {
                    row.querySelector('.entity-slug').innerText = '/' + result.new_slug;
                }
            }
            closeGlobalModal('editCategoryModal');
        });
    }
</script>

<style>
    @keyframes modal-up {
        from { opacity: 0; transform: translateY(20px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .animate-modal-up { animation: modal-up 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
</style>
@endsection