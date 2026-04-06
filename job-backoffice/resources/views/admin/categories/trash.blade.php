@extends('layouts.admin')

@section('title', 'System Recovery Center')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Header --}}
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Trash Bin</h2>
            <p class="text-xs text-red-500 mt-1 uppercase tracking-widest font-bold italic">
                <i class="fas fa-history mr-1"></i> Temporary Storage for Deleted Entities
            </p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="text-indigo-600 text-sm font-bold hover:underline">
            <i class="fas fa-arrow-left mr-1"></i> Back to Registry
        </a>
    </div>

    {{-- Tabs Navigation --}}
    <div class="flex gap-4 mb-6 border-b border-gray-200">
        <button class="px-6 py-3 border-b-2 border-indigo-600 text-indigo-600 font-black text-xs uppercase tracking-widest">
            Categories ({{ $archivedCategories->count() }})
        </button>
        {{-- هنا سنفترض أنك مررت متغير $archivedKeywords من الـ Controller --}}
        <button class="px-6 py-3 border-b-2 border-transparent text-gray-400 font-bold text-xs uppercase tracking-widest hover:text-gray-600 transition">
            Keywords Intelligence
        </button>
    </div>

    {{-- Categories Recovery Table --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-10">
        <div class="p-6 bg-gray-50/30 border-b border-gray-50 flex items-center justify-between">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Archived Categories</span>
            <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-3 py-1 rounded-full uppercase tracking-widest">Soft Deleted Data</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-gray-400 text-[10px] uppercase tracking-widest font-black border-b border-gray-50">
                        <th class="px-8 py-4">Entity Name</th>
                        <th class="px-8 py-4">Deletion Time</th>
                        <th class="px-8 py-4 text-right">System Recovery</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($archivedCategories as $category)
                    <tr class="hover:bg-red-50/30 transition-all group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                    <i class="fas fa-folder-minus text-xs"></i>
                                </div>
                                <span class="font-bold text-gray-400 line-through decoration-red-200">{{ $category->name }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-xs text-gray-400 font-medium">
                            {{ $category->deleted_at->diffForHumans() }}
                        </td>
                        <td class="px-8 py-5 text-right">
                            <form action="{{ route('admin.categories.restore', $category->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-green-50 text-green-600 px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-green-600 hover:text-white transition-all shadow-sm border border-green-100">
                                    <i class="fas fa-undo-alt mr-1"></i> Restore Entity
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-20 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-trash-restore text-gray-100 text-5xl mb-4"></i>
                                <p class="text-gray-300 text-sm font-medium italic">Category trash is currently empty.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Keywords Recovery Table (Section) --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden opacity-90">
        <div class="p-6 bg-gray-50/30 border-b border-gray-50">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Archived Keywords Intelligence</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-gray-400 text-[10px] uppercase tracking-widest font-black border-b border-gray-50">
                        <th class="px-8 py-4">Keyword Name</th>
                        <th class="px-8 py-4">Original Slug</th>
                        <th class="px-8 py-4 text-right">Recovery</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($archivedKeywords ?? [] as $keyword)
                    <tr class="hover:bg-blue-50/30 transition-all">
                        <td class="px-8 py-5 font-bold text-gray-400 italic">
                            {{ $keyword->name }}
                        </td>
                        <td class="px-8 py-5">
                            <code class="text-[10px] bg-gray-50 text-gray-300 px-2 py-1 rounded">/{{ $keyword->slug }}</code>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <form action="{{ route('admin.keywords.restore', $keyword->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-indigo-500 hover:text-indigo-700 font-black text-[10px] uppercase tracking-widest underline decoration-2 underline-offset-4">
                                    Initialize Recovery
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-16 text-center text-gray-300 italic text-sm">
                            No archived keywords detected.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection