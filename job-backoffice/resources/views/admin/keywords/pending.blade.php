@extends('layouts.admin')

@section('title', 'Keywords Review')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h2 class="font-bold text-gray-800 text-xl">Keywords Pending Review</h2>
        <span class="bg-amber-100 text-amber-700 text-xs font-bold px-3 py-1 rounded-full">
            {{ $pendingKeywords->count() }} Pending
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                    <th class="p-4 font-semibold">Keyword Name</th>
                    <th class="p-4 font-semibold">Slug</th>
                    <th class="p-4 font-semibold">Related Categories</th>
                    <th class="p-4 font-semibold text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pendingKeywords as $keyword)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="p-4">
                        <span class="font-bold text-slate-700">{{ $keyword->name }}</span>
                    </td>
                    <td class="p-4 text-gray-500 text-sm">
                        {{ $keyword->slug }}
                    </td>
                    <td class="p-4">
                        @foreach($keyword->categories as $category)
                            <span class="inline-block bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-1 rounded mr-1">
                                {{ $category->name }}
                            </span>
                        @endforeach
                    </td>
                    <td class="p-4">
                        <div class="flex justify-center gap-2">
                            {{-- زر الاعتماد --}}
                            <form action="{{ route('admin.keywords.approve', $keyword->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-1.5 px-4 rounded shadow-sm transition">
                                    Approve
                                </button>
                            </form>

                            {{-- زر الرفض --}}
                            <form action="{{ route('admin.keywords.reject', $keyword->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs font-bold py-1.5 px-4 rounded shadow-sm transition">
                                    Reject
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-12 text-center text-gray-400 italic">
                        No keywords waiting for review.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection