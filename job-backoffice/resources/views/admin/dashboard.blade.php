@extends('layouts.admin')

@section('title', 'Home | Analytics Overview')

@section('content')

{{-- Statistics Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    {{-- Pending Card --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between transition-all hover:shadow-md hover:-translate-y-1">
        <div>
            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Pending Review</p>
            <h3 class="text-3xl font-black text-amber-500 mt-1">{{ $pendingCount }}</h3>
        </div>
        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500">
            <i class="fas fa-clock text-xl"></i>
        </div>
    </div>

    {{-- Approved Card --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between transition-all hover:shadow-md hover:-translate-y-1">
        <div>
            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Approved Keywords</p>
            <h3 class="text-3xl font-black text-green-500 mt-1">{{ $approvedCount }}</h3>
        </div>
        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-500">
            <i class="fas fa-check-double text-xl"></i>
        </div>
    </div>

    {{-- Categories Card --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between transition-all hover:shadow-md hover:-translate-y-1">
        <div>
            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Total Categories</p>
            <h3 class="text-3xl font-black text-indigo-500 mt-1">{{ $totalCategories }}</h3>
        </div>
        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-500">
            <i class="fas fa-folder-tree text-xl"></i>
        </div>
    </div>

    {{-- Blacklist Card --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between transition-all hover:shadow-md hover:-translate-y-1">
        <div>
            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Blacklisted</p>
            <h3 class="text-3xl font-black text-red-500 mt-1">{{ $blacklistedCount }}</h3>
        </div>
        <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-500">
            <i class="fas fa-ban text-xl"></i>
        </div>
    </div>
</div>

{{-- Recent Activity Table Section --}}
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
        <div>
            <h2 class="font-black text-gray-800 text-xl tracking-tight">Recently Approved</h2>
            <p class="text-xs text-gray-400 mt-1">Latest keywords verified by the system</p>
        </div>
        <a href="{{ route('admin.keywords.index', ['status' => 'approved']) }}" 
           class="px-5 py-2 bg-white border border-gray-200 rounded-xl text-indigo-600 text-xs font-bold hover:bg-indigo-50 transition-all shadow-sm">
            View All Terminal <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black border-b border-gray-50">
                    <th class="px-8 py-5">Keyword Intelligence</th>
                    <th class="px-8 py-5">System Slug</th>
                    <th class="px-8 py-5">Assigned Clusters</th>
                    <th class="px-8 py-5 text-right">Verification Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($approvedKeywords as $keyword)
                <tr class="hover:bg-gray-50/50 transition-colors group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-2.5 h-2.5 rounded-full bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.4)]"></div>
                            <span class="font-bold text-gray-700 text-base group-hover:text-indigo-600 transition-colors">{{ $keyword->name }}</span>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        <code class="text-[11px] font-mono bg-gray-100 text-gray-500 px-3 py-1.5 rounded-lg border border-gray-200/50">
                            {{ $keyword->slug }}
                        </code>
                    </td>
                    <td class="px-8 py-5">
                        <div class="flex flex-wrap gap-2">
                            @forelse($keyword->categories as $category)
                                <span class="inline-flex items-center bg-indigo-50 text-indigo-600 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-wider">
                                    {{ $category->name }}
                                </span>
                            @empty
                                <span class="text-[10px] text-gray-300 italic">Uncategorized</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="px-8 py-5 text-right">
                        <div class="text-sm font-bold text-gray-700">{{ $keyword->updated_at->format('d M, Y') }}</div>
                        <div class="text-[10px] text-gray-400 uppercase font-medium mt-1">{{ $keyword->updated_at->format('H:i') }} Terminal Time</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-24 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-inbox text-gray-200 text-2xl"></i>
                            </div>
                            <p class="text-gray-400 text-sm font-medium">No verified intelligence found in the system.</p>
                            <a href="{{ route('admin.keywords.index') }}" class="mt-4 text-indigo-600 text-xs font-bold uppercase tracking-widest hover:underline">Start Approval Process</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection