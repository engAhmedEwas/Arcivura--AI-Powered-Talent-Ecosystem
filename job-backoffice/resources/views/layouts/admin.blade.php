<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arcivura Dashboard | @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <aside class="w-64 bg-slate-900 text-white h-screen sticky top-0 flex flex-col overflow-y-auto shrink-0">
            <div class="p-6 text-center border-b border-slate-800">
                <h1 class="text-2xl font-bold text-amber-500">Arcivura</h1>
                <p class="text-xs text-gray-400">Arcivura | Admin Dashboard</p>
            </div>
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center py-3 px-6 hover:bg-slate-800 transition {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 border-l-4 border-amber-500' : '' }}">
                    <span class="mx-3">Home</span>
                </a>
                <a href="{{ route('admin.keywords.index') }}" 
                class="flex items-center py-3 px-6 hover:bg-slate-800 transition {{ request()->routeIs('admin.keywords.*') ? 'bg-slate-800 border-l-4 border-amber-500' : '' }}">
                    <span class="mx-3">Keywords Review</span>
                    @php 
                        $pendingKeywordsCount = \App\Models\Keyword::where('status', \App\Enums\KeywordStatus::PENDING)->count();
                    @endphp
                    @if($pendingKeywordsCount > 0)
                        <span class="bg-amber-500 text-[10px] px-2 py-0.5 rounded-full font-bold ml-auto animate-pulse">
                            {{ $pendingKeywordsCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('admin.categories.index') }}" 
                class="flex items-center py-3 px-6 hover:bg-slate-800 transition {{ request()->routeIs('admin.categories.index') ? 'bg-slate-800 border-l-4 border-amber-500' : '' }}">
                    <span class="mx-3">Categories</span>
                    @php $catCount = \App\Models\Category::count(); @endphp
                    <span class="bg-amber-500 text-[10px] px-2 py-0.5 rounded-full font-bold ml-auto">{{ $catCount }}</span>
                </a>
                {{-- ------------------------------------------- --}}
                
            </nav>
            {{-- @if(auth()->user()->role === 'super-admin') --}}
                <div class="mt-auto pt-4 border-t border-slate-700/50">
                    <p class="px-6 mb-2 text-[10px] uppercase tracking-wider text-slate-500 font-bold">System Management</p>
                    
                    <a href="{{ route('admin.categories.trash') }}" 
                    class="flex items-center py-3 px-6 hover:bg-red-900/20 group transition {{ request()->routeIs('admin.categories.trash') ? 'bg-red-900/30 border-l-4 border-red-500 text-white' : 'text-slate-400' }}">
                        <svg class="w-5 h-5 group-hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        <span class="mx-3 text-sm">Trash Bin</span>
                        
                        @php $trashCount = \App\Models\Category::onlyTrashed()->count(); @endphp
                        @if($trashCount > 0)
                            <span class="bg-red-500/20 text-red-500 text-[10px] px-2 py-0.5 rounded-full font-bold ml-auto">
                                {{ $trashCount }}
                            </span>
                        @endif
                    </a>
                </div>
            {{-- @endif --}}
        </aside>

        <main class="flex-1 flex flex-col">
            <header class="h-20 bg-white border-b border-gray-200 sticky top-0 z-10 flex items-center justify-between px-8 shrink-0 shadow-sm">
                <div class="text-gray-700 font-bold">Arcivura | Dashboard</div>
                <div class="flex items-center gap-4">

                <span class="text-sm text-gray-600">
                    Welcome {{ auth()->user()->name ?? 'Guest' }}
                </span>
                
                <div class="w-10 h-10 rounded-full bg-amber-500 flex items-center justify-center text-white font-bold uppercase">
                    {{ substr(auth()->user()->name ?? 'G', 0, 1) }}
                </div>
            </div>
            </header>

            <div class="p-8">
                @yield('content')
            </div>
        </main>
    </div>
    <script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
    });

    @if(session('success'))
        Toast.fire({
            icon: 'success',
            title: "{{ session('success') }}"
        });
    @endif

    @if(session('error'))
        Toast.fire({
            icon: 'error',
            title: "{{ session('error') }}"
        });
    @endif

    @if(session('warning'))
        Toast.fire({
            icon: 'warning',
            title: "{{ session('warning') }}"
        });
    @endif
</script>
<script>
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('delete-btn') || e.target.closest('.delete-btn')) {
            e.preventDefault();
            
            const button = e.target.classList.contains('delete-btn') ? e.target : e.target.closest('.delete-btn');
            const form = button.closest('.delete-form');

            Swal.fire({
                title: 'Confirm Action',
                text: "Are you sure you want to proceed with this deletion?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b', 
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Confirm',
                cancelButtonText: 'Cancel',
                borderRadius: '15px'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    });
</script>
</body>
</html>