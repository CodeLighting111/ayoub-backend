<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title', 'لوحة التحكم') - ايوب جملة</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @include('dashboard.partials.tailwind-theme')
    <style>
        body {
            font-family: 'Cairo', 'Segoe UI', Tahoma, sans-serif;
            font-size: 14px;
            line-height: 1.7;
            font-weight: 400;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .dashboard-sidebar {
            background: #ffffff;
        }
        .dashboard-content {
            background: #ffffff;
        }
        .dashboard-card {
            box-shadow: 0 4px 20px rgba(27, 94, 32, 0.06);
        }
        .dashboard-page-title {
            font-size: 1.5rem;
            line-height: 2rem;
            font-weight: 700;
        }
        .dashboard-page-subtitle {
            font-size: 0.875rem;
            line-height: 1.375rem;
            font-weight: 400;
            color: inherit;
        }
        .dashboard-nav-link {
            font-size: 0.875rem;
            line-height: 1.25rem;
            font-weight: 500;
        }
        .dashboard-table-head {
            font-size: 0.8125rem;
            line-height: 1.125rem;
            font-weight: 600;
            letter-spacing: 0.01em;
        }
        .dashboard-table-body {
            font-size: 0.875rem;
            line-height: 1.375rem;
            font-weight: 400;
        }
        select.dashboard-select {
            -webkit-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2341493e' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") !important;
            background-position: left 0.75rem center !important;
            background-repeat: no-repeat !important;
            background-size: 1.25rem 1.25rem !important;
            padding-left: 2.5rem !important;
            padding-right: 1rem;
        }
    </style>
    @stack('head')
</head>
<body class="min-h-screen bg-white text-on-surface">
    @include('dashboard.partials.sidebar')

    <main class="dashboard-content mr-64 flex min-h-screen flex-col">
        @include('dashboard.partials.header', ['breadcrumb' => trim($__env->yieldContent('breadcrumb')) ?: 'لوحة التحكم'])

        <div class="mx-auto w-full max-w-[1440px] flex-1 p-6">
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-secondary-container bg-secondary-container/30 px-4 py-3 text-sm font-medium text-on-secondary-container">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    @include('dashboard.partials.confirm-dialog')
    @stack('scripts')
</body>
</html>
