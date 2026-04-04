@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('admin.categories.index') }}" class="flex items-center text-gray-500 hover:text-amber-600 mb-6 transition">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Categories
    </a>

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Edit Category</h2>
            {{-- <span class="text-xs text-gray-400 font-mono">ID: #{{ $category->id }}</span> --}}
        </div>

        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- مهم جداً في عمليات التعديل --}}
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Category Name</label>
                <input type="text" name="name" 
                       class="w-full p-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none transition @error('name') border-red-500 @enderror" 
                       value="{{ old('name', $category->name) }}">
                <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Category Keywords</label>
                <textarea name="keywords_raw" rows="4" class="w-full p-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">{{ old('keywords_raw', $category->keywords->pluck('name')->implode(', ')) }}
                </textarea>
            </div>
                @error('name')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-4 border-t border-gray-50 pt-6">
                <button type="submit" class="bg-blue-600 text-white px-8 py-2.5 rounded-lg font-bold hover:bg-blue-700 shadow-sm transition">
                    Update Category
                </button>
                <a href="{{ route('admin.categories.index') }}" class="text-gray-500 hover:text-gray-700 font-medium transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection