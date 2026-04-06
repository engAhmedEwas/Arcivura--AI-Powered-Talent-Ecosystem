@extends('layouts.admin')

@section('title', 'Blacklist Registry')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Header Section --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Security Blacklist</h2>
            <p class="text-xs text-red-500 mt-1 uppercase tracking-widest font-bold">
                <i class="fas fa-shield-alt mr-1"></i> Forbidden Intelligence Terminology
            </p>
        </div>
    </div>

    {{-- Add to Blacklist Form Card --}}
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 mb-10 transition-all hover:shadow-md">
        <form action="{{ route('admin.blacklists.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                <div class="md:col-span-5 space-y-2">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Terminal Term to Block</label>
                    <input type="text" name="word" required placeholder="e.g. offensive_term" value="{{ old('word') }}"
                           class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-red-50 focus:border-red-500 outline-none transition-all font-bold text-gray-700 @error('word') border-red-200 bg-red-50 @enderror">
                </div>

                <div class="md:col-span-5 space-y-2">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Protocol Violation Reason</label>
                    <input type="text" name="reason" placeholder="Optional justification..." value="{{ old('reason') }}"
                           class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-red-50 focus:border-red-500 outline-none transition-all font-bold text-gray-700">
                </div>

                <div class="md:col-span-2">
                    <button type="submit" class="w-full bg-red-600 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-red-100 hover:bg-red-700 hover:-translate-y-0.5 active:scale-95 transition-all">
                        Restrict
                    </button>
                </div>
            </div>
            @error('word') <p class="text-red-500 text-[10px] font-bold mt-2 ml-2 uppercase tracking-wide italic">{{ $message }}</p> @enderror
        </form>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 bg-gray-50/30 border-b border-gray-50 flex items-center justify-between">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Active Restrictions</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-gray-400 text-[10px] uppercase tracking-widest font-black border-b border-gray-50">
                        <th class="px-8 py-5">Blocked Word</th>
                        <th class="px-8 py-5">Violation Reason</th>
                        <th class="px-8 py-5 text-center">System Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($blacklists as $item)
                    <tr class="hover:bg-red-50/20 transition-all group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full bg-red-500 animate-pulse shadow-[0_0_8px_rgba(239,68,68,0.5)]"></div>
                                <span class="font-black text-red-600 tracking-tight text-base">{{ $item->word }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-sm text-gray-500 font-medium italic">
                            {{ $item->reason ?? 'General protocol violation' }}
                        </td>
                        <td class="px-8 py-5 text-center">
                            {{-- لاحظ إضافة الكلاس unblock-btn --}}
                            <form id="unblock-form-{{ $item->id }}" action="{{ route('admin.blacklists.destroy', $item->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        onclick="confirmUnblock('{{ $item->id }}', '{{ $item->word }}')"
                                        class="text-gray-300 hover:text-red-600 transition-all p-3 rounded-xl hover:bg-red-50">
                                    <i class="fas fa-unlock-alt mr-1"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Unblock</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-24 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-user-shield text-gray-100 text-6xl mb-4"></i>
                                <p class="text-gray-400 text-sm font-medium italic">Terminal security cleared. No words restricted.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($blacklists->hasPages())
        <div class="p-6 border-t border-gray-50 bg-gray-50/20">
            {{ $blacklists->links() }}
        </div>
        @endif
    </div>
</div>
@endsection