@extends('layouts.admin')

@section('title', 'Add Category & Keywords')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('admin.categories.index') }}" class="flex items-center text-gray-500 hover:text-amber-600 mb-6 transition">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Categories
    </a>

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Create Category & Keywords</h2>

        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Category Name</label>
                <input type="text" name="name" class="w-full p-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none @error('name') border-red-500 @enderror" placeholder="e.g. Software Development" value="{{ old('name') }}">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Related Keywords (Separate with commas)</label>
                <textarea name="keywords_raw" rows="4" class="w-full p-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none @error('keywords') border-red-500 @enderror" placeholder="PHP, Laravel, Backend, MySQL...">{{ old('keywords_raw') }}</textarea>
                <p class="text-xs text-gray-400 mt-2">Example: PHP, Laravel, Backend (Each word will be sent for review)</p>
                @error('keywords') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-4 border-t pt-6">
                <button type="submit" class="bg-amber-500 text-white px-8 py-2.5 rounded-lg font-bold hover:bg-amber-600 transition">Save & Send for Review</button>
                <a href="{{ route('admin.categories.index') }}" class="text-gray-500 hover:text-gray-700 font-medium">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection