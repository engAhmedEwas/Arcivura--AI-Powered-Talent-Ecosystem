@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Manage Categories</h2>
    <button class="bg-amber-500 text-white font-bold py-2 px-6 rounded-lg shadow-sm transition">
        
        <a href="{{ route('admin.categories.create') }}" class="bg-amber-500 ..."> + Add New Category </a>
    </button>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-widest font-semibold">
                <th class="p-5">Category Name</th>
                <th class="p-5">Slug</th>
                <th class="p-5 text-center">Linked Keywords</th>
                <th class="p-5 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($categories as $category)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="p-5 font-bold text-slate-700">{{ $category->name }}</td>
                <td class="p-5 text-gray-500 font-mono text-sm">{{ $category->slug }}</td>
                <td class="p-5 text-center">
                    <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                        {{ $category->keywords_count }} Keywords
                    </span>
                </td>
                <td class="p-5 text-right flex justify-end gap-3">
                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="text-blue-500 hover:underline">Edit</a>

                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="delete-form inline">
                        @csrf 
                        @method('DELETE')
                        <button type="button" class="text-red-500 hover:underline delete-btn">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="p-10 text-center text-gray-400 italic">No categories found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-5 border-t border-gray-100">
        {{ $categories->links() }}
    </div>
</div>
@endsection

