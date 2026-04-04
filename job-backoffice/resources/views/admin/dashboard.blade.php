@extends('layouts.admin')

@section('title', 'Home')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between transition hover:shadow-lg hover:-translate-y-1">
        <div>
            <p class="text-gray-500 text-sm font-medium">Pending Review</p>
            <h3 class="text-4xl font-extrabold text-amber-600 mt-2">{{ $pendingCount }}</h3>
        </div>
        <div class="bg-amber-50 p-4 rounded-xl text-amber-500">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>

    
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between transition hover:shadow-lg hover:-translate-y-1">
        <div>
            <p class="text-gray-500 text-sm font-medium">Approved Keywords</p>
            <h3 class="text-4xl font-extrabold text-green-600 mt-2">{{ $approvedCount }}</h3>
        </div>
        <div class="bg-green-50 p-4 rounded-xl text-green-500">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>

    
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between transition hover:shadow-lg hover:-translate-y-1">
        <div>
            <p class="text-gray-500 text-sm font-medium">Total Categories</p>
            <h3 class="text-4xl font-extrabold text-blue-600 mt-2">{{ $totalCategories }}</h3>
        </div>
        <div class="bg-blue-50 p-4 rounded-xl text-blue-500">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        </div>
    </div>
</div>


<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <h2 class="font-bold text-gray-800 text-xl tracking-tight">Recently Approved Keywords</h2>
        <a href="#" class="text-amber-600 text-sm font-semibold hover:text-amber-700 transition">View All Approved</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-widest font-semibold">
                    <th class="p-5">Keyword Name</th>
                    <th class="p-5">Slug</th>
                    <th class="p-5">Categories</th>
                    <th class="p-5 text-right">Approval Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($approvedKeywords as $keyword)
                <tr class="hover:bg-amber-50/30 transition-colors">
                    <td class="p-5">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            <span class="font-bold text-slate-800 text-base">{{ $keyword->name }}</span>
                        </div>
                    </td>
                    <td class="p-5 text-gray-600 text-sm font-mono bg-gray-50 rounded-md inline-block my-3 ml-5">
                        {{ $keyword->slug }}
                    </td>
                    <td class="p-5">
                        <div class="flex flex-wrap gap-1.5">
                            @forelse($keyword->categories as $category)
                                <span class="inline-block bg-blue-50 text-blue-700 text-[11px] font-bold px-3 py-1 rounded-full shadow-inner">
                                    {{ $category->name }}
                                </span>
                            @empty
                                <span class="text-xs text-gray-400 italic">No category</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="p-5 text-right text-sm text-gray-500 font-medium">
                        {{ $keyword->updated_at->format('M d, Y') }}
                        <span class="text-xs text-gray-400 block mt-1">{{ $keyword->updated_at->format('g:i A') }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-20 text-center text-gray-400 italic">
                        <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        No keywords approved yet. <br> Go to <a href="{{ route('admin.keywords.index') }}" class="text-amber-600 font-bold hover:underline">Keywords Review</a> to approve some.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection