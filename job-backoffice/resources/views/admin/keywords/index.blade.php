@extends('layouts.admin')

@section('title', 'Intelligence Registry')

@section('content')
<div class="max-w-[1600px] mx-auto">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight text-indigo-900">Intelligence Registry</h2>
            <p class="text-xs text-gray-400 mt-1 uppercase tracking-[0.2em] font-bold">Managing Global Keyword Infrastructure</p>
        </div>
        
        <div class="flex items-center gap-3 w-full md:w-auto">
            <form action="{{ route('admin.blacklists.cleanup') }}" method="POST" class="flex-1 md:flex-none">
                @csrf
                <button type="submit" class="w-full bg-white text-amber-600 border border-amber-100 px-6 py-3 rounded-2xl hover:bg-amber-50 transition-all font-black text-[10px] uppercase tracking-widest shadow-sm flex items-center justify-center gap-2">
                    <i class="fas fa-broom"></i> Global Cleanup
                </button>
            </form>

            <a href="{{ route('admin.keywords.create') }}" class="flex-1 md:flex-none bg-indigo-600 text-white px-8 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i> Ingest Unit
            </a>
        </div>
    </div>

    {{-- Status Intelligence Tabs --}}
    <div class="flex flex-wrap gap-2 mb-8 p-1.5 bg-gray-100/50 rounded-2xl w-fit">
        @foreach(['all', 'approved', 'pending', 'rejected'] as $status)
            <a href="{{ route('admin.keywords.index', ['status' => $status]) }}" 
               class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ (request('status', 'all') == $status) ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
                {{ $status }}
            </a>
        @endforeach
    </div>

    {{-- Advanced Filtering Interface --}}
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm mb-8">
        <form id="filter-form" action="{{ route('admin.keywords.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
            <input type="hidden" name="status" value="{{ request('status', 'all') }}">

            <div class="md:col-span-6 relative group">
                <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-gray-300 group-focus-within:text-indigo-500 transition-colors">
                    <i class="fas fa-search text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Search terminology..." 
                    class="w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-50 rounded-2xl focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-200 outline-none transition-all font-bold text-gray-700 text-sm">
            </div>

            <div class="md:col-span-4">
                <select name="category_id" onchange="this.form.submit()" 
                        class="w-full bg-gray-50 border border-gray-50 rounded-2xl px-5 py-4 text-xs font-black uppercase tracking-widest focus:ring-4 focus:ring-indigo-50 focus:border-indigo-200 outline-none cursor-pointer text-gray-500">
                    <option value="">All Structural Clusters</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 bg-gray-800 text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-black transition-all">
                    Apply
                </button>
                @if(request()->hasAny(['search', 'category_id']))
                    <a href="{{ route('admin.keywords.index', ['status' => request('status')]) }}" 
                       class="w-12 h-14 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center hover:bg-red-100 transition-all shadow-sm shadow-red-50" title="Reset Filters">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- 1. Individual Delete Forms (Placed OUTSIDE any other form) --}}
    @foreach($keywords as $keyword)
        <form id="delete-form-{{ $keyword->id }}" action="{{ route('admin.keywords.destroy', $keyword->id) }}" method="POST" class="hidden">
            @csrf @method('DELETE')
        </form>
    @endforeach

    {{-- 2. Data Architecture Table within Bulk Form --}}
    <form id="bulk-actions-form" action="{{ route('admin.keywords.bulk-update') }}" method="POST">
        @csrf
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black border-b border-gray-50">
                            <th class="px-8 py-6 w-10">
                                <input type="checkbox" id="select-all" class="w-5 h-5 rounded-lg border-gray-200 text-indigo-600 focus:ring-indigo-500 cursor-pointer transition-all">
                            </th>
                            <th class="px-8 py-6">Keyword Identifier</th>
                            <th class="px-8 py-6">Associated Clusters</th>
                            <th class="px-8 py-6">Operational Status</th>
                            <th class="px-8 py-6 text-right">System Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($keywords as $keyword)
                        <tr id="row-{{ $keyword->id }}" class="hover:bg-indigo-50/20 transition-all group">
                            <td class="px-8 py-6">
                                <input type="checkbox" name="ids[]" value="{{ $keyword->id }}" 
                                       class="keyword-checkbox w-5 h-5 rounded-lg border-gray-200 text-indigo-600 focus:ring-indigo-500 cursor-pointer transition-all"
                                       onclick="toggleToolbar()">
                            </td>
                            
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="font-black text-gray-800 text-base tracking-tight">{{ $keyword->name }}</span>
                                    <span class="text-[10px] font-mono text-gray-400 tracking-tighter">slug: {{ $keyword->slug }}</span>
                                </div>
                            </td>

                            <td class="px-8 py-6">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($keyword->categories as $category)
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-[9px] font-black uppercase bg-white border border-gray-100 text-gray-500 shadow-sm group-hover:border-indigo-100 group-hover:text-indigo-500 transition-all">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            <td class="px-8 py-6 whitespace-nowrap status-badge-cell">
                                <span class="px-4 py-1.5 inline-flex text-[9px] font-black uppercase tracking-widest rounded-full shadow-sm {{ $keyword->status->getColor() }}">
                                    {{ $keyword->status->getLabel() }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right whitespace-nowrap">
                                <div class="flex justify-end items-center gap-4">
                                    
                                    <div class="flex items-center gap-2">
                                        @if($keyword->status->value === 'pending')
                                            <button type="button" onclick="handleStatusUpdate('{{ $keyword->id }}', 'approved')" class="w-9 h-9 bg-green-50 text-green-600 rounded-xl hover:bg-green-600 hover:text-white transition-all flex items-center justify-center shadow-sm" title="Approve">
                                                <i class="fas fa-check text-xs"></i>
                                            </button>
                                            <button type="button" onclick="handleStatusUpdate('{{ $keyword->id }}', 'rejected')" class="w-9 h-9 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all flex items-center justify-center shadow-sm" title="Reject">
                                                <i class="fas fa-ban text-xs"></i>
                                            </button>
                                        @else
                                            <label class="relative inline-flex items-center cursor-pointer group/toggle">
                                                <input type="checkbox" {{ $keyword->status->value === 'approved' ? 'checked' : '' }} onchange="handleToggle('{{ $keyword->id }}')" class="sr-only peer">
                                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-indigo-100 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                            </label>
                                        @endif
                                    </div>

                                    <button type="button" onclick="openEditModal('{{ $keyword->id }}', '{{ $keyword->name }}', '{{ $keyword->slug }}')" 
                                        class="w-9 h-9 bg-gray-50 text-gray-400 rounded-xl hover:bg-white hover:text-indigo-600 hover:shadow-md transition-all flex items-center justify-center border border-transparent hover:border-indigo-100">
                                        <i class="fas fa-pen text-[10px]"></i>
                                    </button>

                                    {{-- Individual Delete Button: linked via form attribute --}}
                                    <button type="button" 
                                            form="delete-form-{{ $keyword->id }}" 
                                            class="delete-btn w-9 h-9 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-600 transition-all">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </td>   
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-32 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                                            <i class="fas fa-satellite-dish text-gray-200 text-3xl"></i>
                                        </div>
                                        <h3 class="text-xl font-black text-gray-800 tracking-tight">No Intelligence Units Found</h3>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Bulk Action Floating Toolbar --}}
        <div id="bulk-toolbar" class="hidden fixed bottom-12 left-1/2 -translate-x-1/2 bg-gray-900/95 backdrop-blur-md shadow-2xl border border-white/10 p-5 rounded-[2rem] items-center gap-8 z-50 animate-bounce-in min-w-[500px]">
            <div class="flex items-center gap-3">
                <div id="selected-count" class="w-10 h-10 bg-indigo-500 text-white rounded-2xl flex items-center justify-center font-black text-sm shadow-lg shadow-indigo-500/20">0</div> 
                <span class="text-xs font-black text-white uppercase tracking-widest">Units Flagged</span>
            </div>
            <div class="h-10 w-px bg-white/10"></div>
            <div class="flex gap-3">
                <button type="button" onclick="openMergeModal()" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-orange-500/20">Merge Logic</button>
                <button name="action" value="approve" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-green-500/20">Bulk Approve</button>
                <button name="action" value="blacklist" class="bg-red-500 hover:bg-red-700 text-white px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-red-500/20">Protocol Blacklist</button>
            </div>
        </div>
    </form>

    <div class="mt-10">{{ $keywords->links() }}</div>
</div>

{{-- Modals Architecture --}}
{{-- 1. Edit Modal --}}
<div id="editModal" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm overflow-y-auto h-full w-full z-[100] flex items-center justify-center p-4">
    <div class="relative w-full max-w-md shadow-2xl rounded-[2.5rem] bg-white overflow-hidden animate-modal-up">
        <div class="p-10">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-xl font-black text-gray-800 tracking-tight">Modify Unit</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-red-500 text-2xl">&times;</button>
            </div>
            <div class="space-y-6">
                <input type="hidden" id="edit_keyword_id">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">New Identity</label>
                    <input type="text" id="edit_keyword_name" class="w-full bg-gray-50 border border-gray-100 rounded-2xl p-5 outline-none transition-all font-bold text-gray-700">
                </div>
            </div>
            <div class="flex gap-3 mt-10">
                <button onclick="closeModal()" class="flex-1 px-8 py-4 bg-gray-50 text-gray-400 rounded-2xl font-black text-[10px] uppercase">Cancel</button>
                <button onclick="submitEdit()" class="flex-1 px-8 py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase shadow-xl hover:bg-indigo-700">Update Registry</button>
            </div>
        </div>
    </div>
</div>

{{-- 2. Merge Modal --}}
<div id="mergeModal" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm overflow-y-auto h-full w-full z-[100] flex items-center justify-center p-4">
    <div class="relative w-full max-w-md shadow-2xl rounded-[2.5rem] bg-white overflow-hidden animate-modal-up">
        <div class="p-10 text-center">
            <h3 class="text-xl font-black text-gray-800 tracking-tight mb-8">Merge Protocol</h3>
            <form id="mergeForm" action="{{ route('admin.keywords.merge') }}" method="POST" class="space-y-6">
                @csrf
                <div class="text-left space-y-2">
                    <label class="block text-[10px] font-black text-gray-500 uppercase">Primary Target Entity</label>
                    <select name="target_id" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl p-5 font-bold outline-none">
                        <option value="">-- Search Registry Target --</option>
                        @foreach($categories as $cat)
                            <optgroup label="{{ $cat->name }}">
                                @foreach($cat->keywords as $k)
                                    <option value="{{ $k->id }}">{{ $k->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    <input type="hidden" name="source_ids" id="sourceIdsInput">
                </div>
                <div class="flex gap-3 mt-10">
                    <button type="button" onclick="closeMergeModal()" class="flex-1 px-8 py-4 bg-gray-50 text-gray-400 rounded-2xl font-black text-[10px] uppercase">Abort</button>
                    <button type="submit" class="flex-1 px-8 py-4 bg-orange-500 text-white rounded-2xl font-black text-[10px] uppercase hover:bg-orange-600">Confirm Fusion</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes modal-up { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-modal-up { animation: modal-up 0.4s ease-out; }
</style>

<script>
    // 1. Delete Handler (Improved)
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.delete-btn');
        if (!btn) return;

        e.preventDefault();
        const formId = btn.getAttribute('form');
        const targetForm = document.getElementById(formId);

        Swal.fire({
            title: 'Are you sure?',
            text: "This entity will be moved to terminal architecture.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Yes, Delete',
            customClass: { popup: 'rounded-[2rem]' }
        }).then((result) => {
            if (result.isConfirmed && targetForm) {
                targetForm.submit();
            }
        });
    });

    // 2. Selection & Toolbar
    function toggleToolbar() {
        const checkedCount = document.querySelectorAll('.keyword-checkbox:checked').length;
        const toolbar = document.getElementById('bulk-toolbar');
        const countSpan = document.getElementById('selected-count');
        if (countSpan) countSpan.innerText = checkedCount;
        toolbar.classList.toggle('hidden', checkedCount === 0);
        toolbar.classList.toggle('flex', checkedCount > 0);
    }

    const selectAll = document.getElementById('select-all');
    if(selectAll) {
        selectAll.onclick = function() {
            document.querySelectorAll('.keyword-checkbox').forEach(cb => cb.checked = selectAll.checked);
            toggleToolbar();
        };
    }

    // 3. Status Updates
    function handleToggle(id) {
        fetch(`/admin/keywords/${id}/toggle-status`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                Toast.fire({ icon: 'success', title: 'Registry Synchronized' });
                setTimeout(() => location.reload(), 500);
            }
        });
    }

    function handleStatusUpdate(id, status) {
        fetch(`/admin/keywords/${id}/update-status`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            body: JSON.stringify({ status: status })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                Toast.fire({ icon: 'success', title: data.message });
                setTimeout(() => location.reload(), 500);
            }
        });
    }

    // 4. Modals
    function openEditModal(id, name, slug) {
        document.getElementById('edit_keyword_id').value = id;
        document.getElementById('edit_keyword_name').value = name;
        document.getElementById('editModal').classList.replace('hidden', 'flex');
    }
    function closeModal() { document.getElementById('editModal').classList.replace('flex', 'hidden'); }
    
    function submitEdit() {
        const id = document.getElementById('edit_keyword_id').value;
        const name = document.getElementById('edit_keyword_name').value;
        fetch(`/admin/keywords/${id}`, {
            method: 'PUT',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            body: JSON.stringify({ name: name })
        }).then(res => res.json()).then(() => location.reload());
    }

    function openMergeModal() {
        const ids = Array.from(document.querySelectorAll('.keyword-checkbox:checked')).map(cb => cb.value);
        if (ids.length < 2) return Toast.fire({ icon: 'warning', title: 'Fusion requires at least 2 units' });
        document.getElementById('sourceIdsInput').value = JSON.stringify(ids);
        document.getElementById('mergeModal').classList.replace('hidden', 'flex');
    }
    function closeMergeModal() { document.getElementById('mergeModal').classList.replace('flex', 'hidden'); }
</script>
@endsection