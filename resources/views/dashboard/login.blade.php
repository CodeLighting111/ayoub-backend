<!DOCTYPE html>
<html class="light" dir="rtl" lang="ar">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>تسجيل الدخول - ايوب جملة</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @include('dashboard.partials.tailwind-theme')
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .bg-login-pattern {
            background-color: #f3f3f3;
            background-image:
                radial-gradient(circle at 100% 0%, rgba(171, 244, 172, 0.22) 0%, transparent 45%),
                radial-gradient(circle at 0% 100%, rgba(27, 94, 32, 0.06) 0%, transparent 42%),
                radial-gradient(#e2e2e2 1px, transparent 1px);
            background-size: auto, auto, 20px 20px;
        }
        .login-hero-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
    </style>
</head>
<body class="bg-background text-on-surface font-body h-screen flex flex-col overflow-hidden">
    <main class="flex flex-1 min-h-0 flex-col overflow-hidden md:flex-row">
        <div class="relative hidden h-full min-h-0 overflow-hidden md:block md:w-1/2">
            <img
                alt="منتجات سوبرماركت مصرية - جهينة، شيبسي، حياة، بيبسي"
                class="login-hero-image absolute inset-0"
                decoding="sync"
                fetchpriority="high"
                loading="eager"
                sizes="50vw"
                src="{{ asset('images/dashboard/login-hero.png') }}"
                srcset="{{ asset('images/dashboard/login-hero.png') }} 1x, {{ asset('images/dashboard/login-hero@2x.png') }} 2x"
            >
            <div class="absolute inset-0 flex flex-col justify-end bg-gradient-to-t from-on-background/70 to-transparent p-10 lg:p-16 xl:p-20">
                <h2 class="mb-4 text-3xl font-semibold leading-tight text-white lg:text-5xl">لوجستيات البقالة المبسطة.</h2>
                <p class="max-w-lg text-lg leading-relaxed text-white/90 lg:text-xl">قم بإدارة سلاسل التوريد والمخزون وعمليات التسليم بكفاءة من خلال لوحة التحكم الاحترافية الخاصة بنا.</p>
            </div>
        </div>

        <div class="flex h-full w-full flex-col items-center justify-center overflow-y-auto bg-surface-container-lowest px-8 py-10 md:w-1/2 md:px-16 lg:px-24">
            <div class="w-full max-w-xl">
                <div class="mb-8 flex justify-center">
                    <img
                        alt="شعار ايوب جملة"
                        class="h-52 w-auto max-w-[400px] object-contain sm:max-w-[460px] md:h-60 md:max-w-[520px] lg:h-72 lg:max-w-[580px]"
                        src="{{ asset('images/brand/logo.png') }}"
                    >
                </div>

                <div class="text-center md:text-right">
                    <h1 class="mb-3 text-3xl font-semibold text-on-surface lg:text-5xl">مرحباً بعودتك</h1>
                    <p class="mb-8 text-base text-on-surface-variant lg:text-xl">يرجى تسجيل الدخول للوصول إلى لوحة التحكم الخاصة بك.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-lg bg-red-50 text-red-700 px-4 py-3 text-body-md">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('admin.login') }}" class="space-y-7" method="POST">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-on-surface mb-2" for="email">البريد الإلكتروني</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-outline text-[22px]">mail</span>
                            </div>
                            <input
                                class="block w-full pr-12 pl-4 py-4 text-base lg:text-lg border border-outline-variant rounded-xl bg-surface-container-lowest text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary-container transition-all duration-200 placeholder-outline @error('email') border-red-500 @enderror"
                                id="email"
                                name="email"
                                placeholder="ayoub@gmail.com"
                                required
                                type="email"
                                value="{{ old('email') }}"
                            >
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-on-surface" for="password">كلمة المرور</label>
                            <a class="text-sm text-primary-container hover:text-primary transition-colors" href="#">هل نسيت كلمة المرور؟</a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-outline text-[22px]">lock</span>
                            </div>
                            <input
                                class="block w-full pr-12 pl-4 py-4 text-base lg:text-lg border border-outline-variant rounded-xl bg-surface-container-lowest text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary-container transition-all duration-200 placeholder-outline @error('password') border-red-500 @enderror"
                                id="password"
                                name="password"
                                placeholder="••••••••"
                                required
                                type="password"
                            >
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button
                            class="w-full flex justify-center items-center py-4 px-4 border border-transparent rounded-xl shadow-sm text-lg lg:text-xl font-semibold text-on-primary bg-primary-container hover:bg-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-container transition-colors duration-200"
                            type="submit"
                        >
                            تسجيل الدخول
                            <span class="material-symbols-outlined mr-2 text-[22px]">arrow_back</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer class="bg-surface-container-low border-t border-outline-variant z-50 shrink-0">
        <div class="w-full py-4 px-6 md:px-10 flex justify-center">
            <div class="text-body-md text-on-surface">
                © {{ date('Y') }} Code Lighting. جميع الحقوق محفوظة.
            </div>
        </div>
    </footer>
</body>
</html>
