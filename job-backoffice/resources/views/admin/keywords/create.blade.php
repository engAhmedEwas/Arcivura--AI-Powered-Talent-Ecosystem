@extends('layouts.admin')

@section('title', 'Intelligence Ingestion')

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- Breadcrumb Navigation --}}
    <a href="{{ route('admin.keywords.index') }}" class="inline-flex items-center text-gray-400 hover:text-indigo-600 mb-8 transition-all group">
        <div class="w-8 h-8 rounded-lg bg-white shadow-sm border border-gray-100 flex items-center justify-center mr-3 group-hover:bg-indigo-50">
            <i class="fas fa-chevron-left text-[10px]"></i>
        </div>
        <span class="text-sm font-bold tracking-tight">Return to Registry</span>
    </a>

    {{-- Creation Card --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-gray-50/30 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-black text-gray-800 tracking-tight">Ingest Intelligence</h2>
                <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest font-bold">Assign terms to structural clusters</p>
            </div>
            <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-500">
                <i class="fas fa-brain text-xl"></i>
            </div>
        </div>

        <div class="p-8">
            <form action="{{ route('admin.keywords.store') }}" method="POST" class="space-y-8">
                @csrf

                {{-- Cluster Selection (Category) --}}
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] ml-1">
                        Target Cluster (Category)
                    </label>
                    <div class="select2-wrapper">
                        <select name="category_id" id="category_select" class="w-full" required>
                            <option value="">Select a category...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('category_id') <p class="text-red-500 text-[10px] font-bold mt-1 tracking-wide uppercase">{{ $message }}</p> @enderror
                </div>

                {{-- Keyword Tagging --}}
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] ml-1">
                        Term Analysis & Injection
                    </label>
                    <div class="select2-wrapper">
                        <select name="keywords[]" id="keyword_autocomplete" class="w-full" multiple="multiple" required>
                        </select>
                    </div>
                    
                    {{-- Automated Review Indicator --}}
                    <div class="flex items-center gap-3 p-4 bg-amber-50/50 rounded-2xl border border-amber-100">
                        <i class="fas fa-robot text-amber-500"></i>
                        <p class="text-amber-700 text-[10px] font-bold uppercase tracking-wider leading-relaxed">
                            System Protocol: If the term is not detected in the current registry, it will be flagged for automated review before deployment.
                        </p>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
                            @foreach ($errors->all() as $error)
                                <p class="text-red-700 text-[10px] font-black uppercase flex items-center mb-1">
                                    <i class="fas fa-times-circle mr-2 text-xs"></i> {{ $error }}
                                </p>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-50">
                    <a href="{{ route('admin.keywords.index') }}" class="px-8 py-3 rounded-2xl font-bold text-sm text-gray-400 hover:bg-gray-50 transition-all">
                        Abort
                    </a>
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-1 active:scale-[0.98] transition-all flex items-center gap-2">
                        <span>Save & Deploy</span>
                        <i class="fas fa-paper-plane text-[10px]"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Select2 Theme Override for Arcivura v2.0 */
        .select2-container--default .select2-selection--single,
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #f3f4f6 !important; 
            border-radius: 1.25rem !important;
            min-height: 56px !important;
            padding: 10px 14px !important;
            background-color: #f9fafb !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #6366f1 !important;
            background-color: #ffffff !important;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.05) !important;
        }

        .select2-dropdown {
            border: 1px solid #f3f4f6 !important;
            border-radius: 1.25rem !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05) !important;
            overflow: hidden;
            padding: 8px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #6366f1 !important;
            border: none !important;
            color: white !important;
            border-radius: 0.75rem !important;
            padding: 4px 12px !important;
            font-size: 11px !important;
            font-weight: 800 !important;
            margin-top: 4px !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: rgba(255,255,255,0.7) !important;
            margin-right: 8px !important;
            border: none !important;
        }
        
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: white !important;
            background: none !important;
        }
    </style>
@endpush

@push('scripts')
    {{-- نستخدم jQuery من الـ Layout إذا كان متاحاً، وإلا نضمنه هنا --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Category Selector
            $('#category_select').select2({
                placeholder: "Target Cluster...",
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: 10
            });

            // Keyword Autocomplete
            $('#keyword_autocomplete').select2({
                placeholder: "Analyze or Inject terms...",
                tags: true,
                width: '100%',
                tokenSeparators: [','],
                ajax: {
                    url: "{{ route('admin.keywords.search') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { q: params.term }; 
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return { text: item.text, id: item.text }
                            })
                        };
                    },
                    cache: true
                }
            });
        });
    </script>
@endpush
@endsection