<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PesatCare') }} - UKS Digital</title>

    <!-- Geist Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Geist', sans-serif;
        }
        /* Custom Scrollbar Clean & Minimalist */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-[#C4E2F5] via-[#2C5EAD]/40 to-[#FAFBFD] bg-fixed text-neutral-800 antialiased selection:bg-[#1591DC] selection:text-white p-3 sm:p-6" x-data="{ mobileMenuOpen: false }">
@auth
@php
    $user = auth()->user();
    $role = $user->role ?? 'murid';

    // Menu list per role untuk Guru & Murid
    $menus = [
        'guru' => [
            [
                'route' => 'guru.dashboard',
                'active' => 'guru.dashboard',
                'label' => 'Dashboard',
            ],
            [
                'route' => 'guru.monitoring',
                'active' => 'guru.monitoring*',
                'label' => 'Pantau Siswa',
            ],
            [
                'route' => 'guru.health-checks',
                'active' => 'guru.health-checks*',
                'label' => 'Pemeriksaan',
            ],
            [
                'route' => 'guru.visits',
                'active' => 'guru.visits*',
                'label' => 'Kunjungan',
            ],
            [
                'route' => 'guru.profile',
                'active' => 'guru.profile',
                'label' => 'Profil Saya',
            ],
        ],
        'murid' => [
            [
                'route' => 'murid.dashboard',
                'active' => 'murid.dashboard',
                'label' => 'Dashboard',
            ],
            [
                'route' => 'murid.visits',
                'active' => 'murid.visits*',
                'label' => 'Kunjungan',
            ],
            [
                'route' => 'murid.screenings',
                'active' => 'murid.screenings*',
                'label' => 'Screening',
            ],
            [
                'route' => 'murid.certificates',
                'active' => 'murid.certificates*',
                'label' => 'Surat Izin',
            ],
            [
                'route' => 'murid.profile',
                'active' => 'murid.profile',
                'label' => 'Profil',
            ],
        ],
    ];

    $userMenus = $menus[$role] ?? $menus['murid'];
@endphp
<!-- Container Utama: Seluruh Kartu + Navbar Menggunakan bg-white/60 Menyatu -->
<div class="min-h-[calc(100vh-3rem)] w-full bg-white/60 backdrop-blur-xl rounded-[32px] border border-white/80 shadow-2xl shadow-[#2C5EAD]/20 flex flex-col overflow-hidden relative">

    <!-- ========================================================= -->
    <!-- TOP NAVBAR (HEADER) - TANPA BORDER BAWAH & TANPA BG BERBEDA-->
    <!-- ========================================================= -->
    <header class="px-6 py-5 sm:px-10 flex items-center justify-between z-20 bg-transparent">
        
        <!-- Left: Brand / Logo -->
        <div class="flex items-center gap-3">
            <a href="{{ route($role . '.dashboard') }}" class="flex items-center gap-2.5 group">
                <img src="{{ asset('assets/img/pesatcare.png') }}" alt="PesatCare" class="w-40 object-contain transition-transform group-hover:scale-105">
            </a>
        </div>

        <!-- Center: Navigation Links (Pill Bar Modern) -->
        <nav class="hidden md:flex items-center gap-1 bg-[#2C5EAD]/10 p-1.5 rounded-full border border-white/40">
            @foreach($userMenus as $m)
                @php
                    $isActive = request()->routeIs($m['active']) || request()->routeIs($m['route']);
                @endphp
                <a href="{{ route($m['route']) }}"
                   class="px-5 py-2 rounded-full text-xs font-semibold transition-all duration-200 {{ $isActive ? 'bg-[#2C5EAD] text-white shadow-md shadow-[#2C5EAD]/30' : 'text-slate-700 hover:text-[#2C5EAD] hover:bg-white/50' }}">
                    {{ $m['label'] }}
                </a>
            @endforeach
        </nav>

        <!-- Right Side: Actions, Notifications & Profile -->
        <div class="flex items-center gap-3">
            
            <!-- Role Badge Pill -->
            <div class="hidden sm:flex items-center bg-[#C4E2F5]/60 border border-white/80 px-3.5 py-1.5 rounded-full text-xs font-semibold text-[#2C5EAD] capitalize">
                {{ $role }} Akses
            </div>

            <!-- Notifications Button -->
            <button type="button"
                    class="w-10 h-10 rounded-2xl bg-white/60 border border-white/80 hover:bg-white text-slate-700 hover:text-[#2C5EAD] shadow-sm transition flex items-center justify-center relative focus:outline-none"
                    title="Notifikasi">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                <span class="absolute top-2.5 right-2.5 w-2 h-2 rounded-full bg-[#1591DC] ring-2 ring-white"></span>
            </button>

            <!-- User Profile Avatar & Dropdown Menu -->
            <div class="relative flex items-center" x-data="{ profileOpen: false }">
                <button @click="profileOpen = !profileOpen" class="flex items-center gap-2.5 focus:outline-none group">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-[#2C5EAD] to-[#1591DC] text-white font-bold text-sm flex items-center justify-center shadow-md shadow-[#2C5EAD]/30 ring-2 ring-white group-hover:scale-105 transition">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </button>

                <!-- Dropdown Card -->
                <div x-show="profileOpen" 
                     @click.away="profileOpen = false" 
                     x-cloak
                     class="absolute right-0 top-12 w-52 bg-white/90 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/80 py-2.5 z-50 text-xs">
                    <div class="px-4 py-2 border-b border-slate-100">
                        <p class="font-bold text-slate-800 truncate">{{ $user->name }}</p>
                        <p class="text-[#1591DC] capitalize font-medium">{{ $role }}</p>
                    </div>
                    <a href="{{ route($role . '.profile') }}" class="block px-4 py-2.5 text-slate-700 hover:bg-[#C4E2F5]/40 hover:text-[#2C5EAD] font-medium transition">Profil Saya</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2.5 text-rose-600 hover:bg-rose-50 font-medium transition">
                            Keluar / Log Out
                        </button>
                    </form>
                </div>
            </div>

            <!-- Mobile Hamburger Toggle Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-xl bg-white/80 border border-white text-slate-700 hover:bg-[#C4E2F5]/40 hover:text-[#2C5EAD] focus:outline-none transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

        </div>
    </header>

    <!-- Mobile Navigation Drawer -->
    <div x-show="mobileMenuOpen" x-cloak class="md:hidden bg-white/90 backdrop-blur-md border-b border-white/60 px-6 py-4 space-y-1.5 z-30">
        @foreach($userMenus as $m)
            @php
                $isActive = request()->routeIs($m['active']) || request()->routeIs($m['route']);
            @endphp
            <a href="{{ route($m['route']) }}"
               class="block px-4 py-2.5 rounded-xl text-sm font-semibold transition {{ $isActive ? 'bg-[#2C5EAD] text-white shadow-sm' : 'text-slate-700 hover:bg-[#C4E2F5]/40 hover:text-[#2C5EAD]' }}">
                {{ $m['label'] }}
            </a>
        @endforeach
    </div>

    <!-- ========================================================= -->
    <!-- MAIN WORKSPACE CONTENT AREA                               -->
    <!-- ========================================================= -->
    <main class="flex-1 p-6 sm:p-10 overflow-y-auto bg-transparent">

        <!-- Flash Notification: Success -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50/90 backdrop-blur-md border border-emerald-200/80 text-emerald-800 text-xs font-semibold flex items-center gap-3 shadow-sm">
                <div class="w-6 h-6 rounded-lg bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">✓</div>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Flash Notification: Errors -->
        @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-50/90 backdrop-blur-md border border-rose-200/80 text-rose-800 text-xs space-y-1 shadow-sm">
                <p class="font-bold">Mohon periksa kembali input Anda:</p>
                <ul class="list-disc list-inside pl-1 space-y-0.5">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Child Blade View Content Container -->
        <div class="max-w-7xl mx-auto space-y-6">
            @yield('content')
        </div>

    </main>

</div>
@endauth

@guest
    <div class="min-h-screen bg-gradient-to-br from-[#2C5EAD] via-[#C4E2F5] to-[#FAFBFD] p-6 flex flex-col items-center justify-center">
        @yield('content')
    </div>
@endguest

</body>
</html>