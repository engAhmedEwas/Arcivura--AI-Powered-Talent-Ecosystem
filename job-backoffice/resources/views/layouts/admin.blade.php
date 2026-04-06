<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arcivura | @yield('title', 'Intelligence Portal')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @stack('styles')
    
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .bg-arc-sidebar { background-color: #1e1b3a; } /* اللون من الصورة */
        .active-link { background-color: rgba(255, 255, 255, 0.1); border-left: 4px solid #f9b115; color: white !important; }
    </style>
</head>
<body class="bg-[#f8f9fa] antialiased">
    <div class="min-h-screen flex">
        
        <aside class="w-64 bg-arc-sidebar text-gray-400 h-screen sticky top-0 flex flex-col shrink-0 shadow-xl">
            <div class="p-8 text-center border-b border-white/5">
                <h1 class="text-2xl font-black text-white tracking-tighter">Arcivura</h1>
                <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">Admin Terminal</p>
            </div>

            <nav class="mt-6 flex-1 px-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all hover:text-white {{ request()->routeIs('admin.dashboard') ? 'active-link' : '' }}">
                    <i class="fas fa-th-large w-5 text-sm"></i>
                    <span class="mx-3 text-sm font-bold">Dashboard</span>
                </a>

                <a href="{{ route('admin.keywords.index') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all hover:text-white {{ request()->routeIs('admin.keywords.*') ? 'active-link' : '' }}">
                    <i class="fas fa-key w-5 text-sm"></i>
                    <span class="mx-3 text-sm font-bold">Keywords</span>
                    @if(isset($pendingCount) && $pendingCount > 0)
                        <span class="ml-auto bg-amber-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.categories.index') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all hover:text-white {{ request()->routeIs('admin.categories.index') ? 'active-link' : '' }}">
                    <i class="fas fa-folder w-5 text-sm"></i>
                    <span class="mx-3 text-sm font-bold">Categories</span>
                    @if(isset($totalCategories) && $totalCategories > 0)
                        <span class="ml-auto bg-white/10 text-white text-[10px] font-black px-2 py-0.5 rounded-full">{{ $totalCategories }}</span>
                    @endif
                </a>

                <div class="pt-4 pb-2">
                    <p class="px-4 text-[10px] uppercase tracking-widest text-gray-600 font-bold">Security & System</p>
                </div>

                <a href="{{ route('admin.blacklists.index') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all hover:text-white {{ request()->routeIs('admin.blacklists.*') ? 'active-link' : '' }}">
                    <i class="fas fa-user-shield w-5 text-sm"></i>
                    <span class="mx-3 text-sm font-bold">Blacklist</span>
                    @if(isset($blacklistedCount) && $blacklistedCount > 0)
                        <span class="ml-auto bg-red-500/20 text-red-500 text-[10px] font-black px-2 py-0.5 rounded-full">{{ $blacklistedCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.categories.trash') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all hover:text-white {{ request()->routeIs('admin.categories.trash') ? 'active-link' : '' }}">
                    <i class="fas fa-trash-alt w-5 text-sm"></i>
                    <span class="mx-3 text-sm font-bold">Trash Bin</span>
                </a>
            </nav>

            <div class="p-4 border-t border-white/5">
                <div class="flex items-center p-2 bg-white/5 rounded-2xl">
                    <div class="w-8 h-8 rounded-lg bg-amber-500 flex items-center justify-center text-white font-bold text-xs uppercase">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="ml-3 overflow-hidden">
                        <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-[10px] text-gray-500">System Manager</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="flex-1 flex flex-col">
            <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-8 sticky top-0 z-30">
                <div class="text-gray-400 text-sm font-medium">
                    Pages / <span class="text-gray-800 font-bold">@yield('title', 'Dashboard')</span>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="relative group">
                        <button class="relative p-2 text-gray-400 hover:text-indigo-600 transition-all">
                            <i class="fas fa-bell text-xl"></i>
                            {{-- النقطة الحمراء تظهر فقط إذا وجد تنبيهات --}}
                            @if($unreadCount > 0)
                                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                            @endif
                        </button>

                        {{-- القائمة المنسدلة تظهر عند تمرير الماوس أو الضغط --}}
                        <div class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 hidden group-hover:block z-50">
                            <div class="p-4 border-b border-gray-50 flex justify-between items-center">
                                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Notifications</span>
                                <a href="#" class="text-[9px] font-bold text-indigo-600 uppercase">Clear All</a>
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                @forelse($recentNotifications as $note)
                                    <div class="p-4 border-b border-gray-50 hover:bg-gray-50 transition-all">
                                        <p class="text-xs font-bold text-gray-700">{{ $note->message }}</p>
                                        <span class="text-[9px] text-gray-400">{{ $note->created_at->diffForHumans() }}</span>
                                    </div>
                                @empty
                                    <div class="p-8 text-center text-gray-300 text-[10px] uppercase font-bold">
                                        System Secure. No Alerts.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="h-8 w-[1px] bg-gray-100 mx-2"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700 transition-all">
                            LOGOUT <i class="fas fa-sign-out-alt ml-1"></i>
                        </button>
                    </form>
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
            timerProgressBar: true
        });

        document.addEventListener('click', function (e) {
            const deleteBtn = e.target.closest('.delete-btn');
            
            if (deleteBtn) {
                e.preventDefault(); 
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This entity will be moved to terminal architecture.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Yes, Delete',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        popup: 'rounded-[2rem]',
                        confirmButton: 'rounded-xl font-black text-[10px] uppercase px-8 py-4',
                        cancelButton: 'rounded-xl font-black text-[10px] uppercase px-8 py-4 text-gray-400'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formId = deleteBtn.getAttribute('form');
                        const targetForm = document.getElementById(formId);

                        if (targetForm) {
                            console.log("Success: Deleting " + formId);
                            targetForm.submit();
                        } else {
                            console.error("Critical Failure: Form " + formId + " not found.");
                        }
                    }
                });
            }
        });

        function openGlobalModal(modalId, data = {}) {
            const modal = document.getElementById(modalId);
            if (!modal) return;

            Object.keys(data).forEach(key => {
                const input = modal.querySelector(`#${key}`);
                if (input) {
                    if (input.tagName === 'INPUT' || input.tagName === 'SELECT') {
                        input.value = data[key];
                    } else {
                        input.innerText = data[key];
                    }
                }
            });

            modal.classList.replace('hidden', 'flex');
            document.body.style.overflow = 'hidden';
        }

        function closeGlobalModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.replace('flex', 'hidden');
                document.body.style.overflow = 'auto';
            }
        }

    // الاستماع لرسائل الـ Session من Laravel
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#4f46e5'
                });
            @endif
            
            @if(session('info'))
                Toast.fire({
                    icon: 'info',
                    title: '{{ session('info') }}'
                });
            @endif
        });

        
        async function handleAjaxUpdate(event, url, callback = null) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok && (result.success || !result.errors)) {
                    Toast.fire({ icon: 'success', title: 'Registry Synchronized' });
                    if (callback) callback(result);
                    else setTimeout(() => location.reload(), 800);
                } else {
                    const errorMsg = result.message || (result.errors ? Object.values(result.errors)[0][0] : 'Update Failed');
                    throw new Error(errorMsg);
                }
            } catch (error) {
                Swal.fire({ icon: 'error', title: 'System Error', text: error.message });
            }
        }

        function confirmUnblock(id, word) {
            Swal.fire({
                title: 'Security Protocol: Unblock?',
                text: `Are you sure you want to restore "${word}" to the active terminology?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5', 
                cancelButtonColor: '#f3f4f6',
                confirmButtonText: 'YES, RELEASE TERM',
                cancelButtonText: 'ABORT',
                customClass: {
                    popup: 'rounded-[2rem]',
                    confirmButton: 'rounded-xl font-black text-[10px] uppercase px-8 py-4',
                    cancelButton: 'rounded-xl font-black text-[10px] uppercase px-8 py-4 text-gray-400'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('unblock-form-' + id).submit();
                }
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>