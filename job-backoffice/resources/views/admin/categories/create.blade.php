@extends('layouts.admin')

@section('title', 'Create New Category Entity')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Breadcrumb Navigation --}}
    <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center text-gray-400 hover:text-indigo-600 mb-8 transition-all group">
        <div class="w-8 h-8 rounded-lg bg-white shadow-sm border border-gray-100 flex items-center justify-center mr-3 group-hover:bg-indigo-50">
            <i class="fas fa-chevron-left text-xs"></i>
        </div>
        <span class="text-sm font-bold tracking-tight">Return to Registry</span>
    </a>

    {{-- Creation Card --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-gray-50/30">
            <h2 class="text-xl font-black text-gray-800 tracking-tight">Initialize Category</h2>
            <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest">Define a new structural classification</p>
        </div>

        <div class="p-8">
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
                @csrf
                
                {{-- Category Name Input --}}
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] ml-1">
                        Category Nomenclature
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-300">
                            <i class="fas fa-tag text-xs"></i>
                        </div>
                        <input type="text" name="name" 
                               class="w-full pl-11 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 focus:bg-white outline-none transition-all font-bold text-gray-700 placeholder-gray-300 @error('name') border-red-200 bg-red-50 @enderror" 
                               placeholder="e.g. Backend Engineering" 
                               value="{{ old('name') }}"
                               autocomplete="off">
                    </div>
                    @error('name') 
                        <p class="text-red-500 text-[10px] font-bold mt-2 ml-2 uppercase tracking-wide">
                            <i class="fas fa-exclamation-triangle mr-1"></i> {{ $message }}
                        </p> 
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-4 pt-4">
                    <button type="submit" class="flex-1 bg-indigo-600 text-white px-8 py-4 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-indigo-700 hover:shadow-xl hover:shadow-indigo-100 transition-all active:scale-[0.98]">
                        Confirm & Deploy
                    </button>
                    
                    <a href="{{ route('admin.categories.index') }}" 
                       class="px-8 py-4 rounded-2xl font-bold text-sm text-gray-400 hover:bg-gray-50 hover:text-gray-600 transition-all">
                        Abort
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- System Note --}}
    <div class="mt-8 p-6 bg-amber-50/50 rounded-2xl border border-amber-100 flex items-start gap-4">
        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center shrink-0 text-amber-600">
            <i class="fas fa-info-circle text-sm"></i>
        </div>
        <div>
            <h4 class="text-xs font-black text-amber-800 uppercase tracking-wider">Automated Slug Generation</h4>
            <p class="text-[11px] text-amber-700/70 mt-1 leading-relaxed">
                The system will automatically transform the nomenclature into a URL-friendly unique identifier (slug) for indexing purposes.
            </p>
        </div>
    </div>
</div>
@endsection