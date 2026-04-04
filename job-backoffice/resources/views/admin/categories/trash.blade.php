@extends('layouts.admin')

@section('title', 'Archived Categories')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-500">Archived Categories (Trash)</h2>
    <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:underline">← Back to Active Categories</a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 text-gray-400 text-xs uppercase font-semibold">
                <th class="p-5">Name</th>
                <th class="p-5">Archived At</th>
                <th class="p-5 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($archivedCategories as $category)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="p-5 font-bold text-gray-400">{{ $category->name }}</td>
                <td class="p-5 text-gray-400 text-sm">{{ $category->deleted_at->diffForHumans() }}</td>
                <td class="p-5 text-right flex justify-end gap-3">
                    <form action="{{ route('admin.categories.restore', $category->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-green-100 text-green-600 px-4 py-1 rounded-lg text-xs font-bold hover:bg-green-200 transition">
                            Restore
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="3" class="p-10 text-center text-gray-400 italic">Trash is empty.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection