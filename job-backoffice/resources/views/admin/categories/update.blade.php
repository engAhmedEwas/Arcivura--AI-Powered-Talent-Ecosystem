@extends('layouts.admin')

@section('title', 'Re-calibrate Entity: ' . $category->name)

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Header Section --}}
    <div class="mb-10 flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-gray-800 tracking-tight text-indigo-900">Re-calibrate Entity</h2>
            <p class="text-xs text-gray-400 mt-2 uppercase tracking-[0.2em] font-black italic">
                Modifying Structural Unit: <span class="text-indigo-500">{{ $category->name }}</span>
            </p>
        </div>
        
        <a href="{{ route('admin.categories.index') }}" 
           class="w-12 h-12 bg-white border border-gray-100 rounded-2xl flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:shadow-xl hover:shadow-indigo-50 transition-all shadow-sm group">
            <i class="fas fa-chevron-left group-hover:-translate-x-1 transition-transform"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- Main Configuration Form --}}
        <div class="lg:col-span-8">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-10 overflow-hidden relative">
                {{-- Decorative Element --}}
                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50/30 rounded-bl-full -mr-16 -mt-16 transition-all"></div>

                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="relative z-10">
                    @csrf
                    @method('PUT')

                    <div class="space-y-8">
                        {{-- Name Input --}}
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Classification Identity</label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 pl-6 flex items-center text-gray-300 group-focus-within:text-indigo-500 transition-colors">
                                    <i class="fas fa-tag text-xs"></i>
                                </span>
                                <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                                    class="w-full pl-14 pr-6 py-5 bg-gray-50 border border-gray-50 rounded-2xl focus:ring-4 focus:ring-indigo-50 focus:border-indigo-200 outline-none transition-all font-black text-gray-700 text-lg">
                            </div>
                            @error('name') <p class="text-red-500 text-[10px] font-bold mt-2 uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>

                        {{-- Metadata / System Status (Read Only) --}}
                        <div class="bg-gray-50/50 p-6 rounded-3xl border border-gray-100 flex flex-col md:flex-row gap-6 md:items-center justify-between">
                            <div class="space-y-1">
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Active System Slug</span>
                                <p class="text-xs font-mono text-indigo-600 font-bold">/{{ $category->slug }}</p>
                            </div>
                            <div class="h-8 w-px bg-gray-200 hidden md:block"></div>
                            <div class="space-y-1">
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Linked Intelligence</span>
                                <p class="text-xs font-bold text-gray-700 uppercase tracking-tighter">{{ $category->keywords_count ?? 0 }} Units Connected</p>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-6 flex gap-3">
                            <button type="submit" class="flex-1 bg-indigo-600 text-white py-5 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-1 transition-all">
                                Deploy Calibration
                            </button>
                            <button type="reset" class="px-8 py-5 bg-gray-50 text-gray-400 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-100 transition-all">
                                Reset
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Sidebar Intelligence --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-gray-900 text-white rounded-[2rem] p-8 shadow-2xl relative overflow-hidden">
                <i class="fas fa-info-circle absolute -bottom-4 -right-4 text-8xl text-white/5 opacity-20"></i>
                <h4 class="text-xs font-black uppercase tracking-[0.2em] mb-4 text-indigo-400 italic">Operational Note</h4>
                <p class="text-xs text-gray-400 leading-loose font-bold uppercase tracking-widest italic opacity-70">
                    Modifying the primary identity of this entity will trigger a global re-sync across all linked keywords and pivot routes.
                </p>
            </div>

            <div class="bg-amber-50 rounded-[2rem] p-8 border border-amber-100 shadow-sm">
                <h4 class="text-xs font-black uppercase tracking-[0.2em] mb-4 text-amber-600">Danger Zone</h4>
                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="delete-form">
                    @csrf 
                    @method('DELETE')
                    <button type="button" class="w-full bg-white border border-amber-200 text-amber-600 py-4 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-amber-600 hover:text-white transition-all shadow-sm delete-btn">
                        Purge Entity
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection