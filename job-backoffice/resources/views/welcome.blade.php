<!DOCTYPE html>
<html lang="ar" dir="rtl"> {{-- بما أنك تفضل العربية أحياناً جعلت الهيكل يدعمها --}}
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arcivura | بوابة الدخول</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .bg-arc-sidebar { background-color: #1e1b3a; } /* البنفسجي الغامق في السايدبار */
        .text-arc-yellow { color: #f9b115; } /* الأصفر المستخدم في اللوجو والدائرة */
    </style>
</head>
<body class="bg-[#f8f9fa] h-screen flex items-center justify-center font-sans antialiased">

    <div class="max-w-md w-full p-6">
        {{-- كارت الدخول المصمم بهوية Arcivura --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            
            {{-- الجزء العلوي (Header) بنفس لون السايدبار في الصورة --}}
            <div class="bg-arc-sidebar p-10 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 rounded-2xl mb-4 border border-white/20">
                    <i class="fas fa-briefcase text-arc-yellow text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white tracking-tight">Arcivura</h1>
                <p class="text-gray-400 text-xs uppercase tracking-widest mt-2">Admin Intelligence System</p>
            </div>

            <div class="p-8 text-center">
                <h2 class="text-lg font-semibold text-gray-800 mb-6">مرحباً بك في نظام Arcivura</h2>
                
                <div class="space-y-4">
                    @auth
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center justify-center w-full py-3 bg-arc-sidebar text-white rounded-xl font-bold hover:opacity-90 transition-all shadow-md group">
                            <span>الانتقال إلى لوحة التحكم</span>
                            <i class="fas fa-chart-line mr-3"></i>
                        </a>
                    @else
                        {{-- هنا قمنا بتغيير النص ليكون باسم المنصة كما اقترحت --}}
                        <a href="{{ route('login') }}" 
                           class="flex items-center justify-center w-full py-3 bg-arc-sidebar text-white rounded-xl font-bold hover:opacity-90 transition-all shadow-md group">
                            <span>دخول منصة Arcivura</span>
                            <i class="fas fa-sign-in-alt mr-3"></i>
                        </a>
                    @endauth
                </div>

                {{-- معلومات إضافية --}}
                <div class="mt-8 flex items-center justify-center gap-4 text-gray-400 text-sm">
                    <span class="flex items-center"><i class="fas fa-shield-alt ml-2"></i> آمن</span>
                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                    <span class="flex items-center"><i class="fas fa-lock ml-2"></i> خاص</span>
                </div>
            </div>
        </div>

        <p class="text-center mt-6 text-gray-400 text-xs">
            &copy; {{ date('Y') }} Arcivura Terminal. All rights reserved.
        </p>
    </div>

</body>
</html>