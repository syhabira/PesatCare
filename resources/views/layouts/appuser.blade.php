<!DOCTYPE html>
<html lang="id" class="h-full overflow-hidden">
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
            background: #4F69CC;
        }
    </style>
</head>
<body class="h-screen w-screen overflow-hidden bg-slate-100 text-slate-800 antialiased selection:bg-[#4F69CC] selection:text-white p-2.5 sm:p-4 flex flex-col" x-data="{ mobileMenuOpen: false }">
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
                'label' => 'Pemeriksaan Guru',
            ],
            [
                'route' => 'guru.visits',
                'active' => 'guru.visits*',
                'label' => 'Kunjungan Saya',
            ],
            [
                'route' => 'guru.profile',
                'active' => 'guru.profile',
                'label' => 'Profil',
            ],
        ],
        'murid' => [
            [
                'route' => 'murid.dashboard',
                'active' => 'murid.dashboard',
                'label' => 'Dashboard',
            ],
            [
                'route' => 'murid.screenings',
                'active' => 'murid.screenings*',
                'label' => 'Skrining Kesehatan',
            ],
            [
                'route' => 'murid.visits',
                'active' => 'murid.visits*',
                'label' => 'Riwayat Kunjungan',
            ],
            [
                'route' => 'murid.certificates',
                'active' => 'murid.certificates*',
                'label' => 'Surat Izin UKS',
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
<!-- Container Utama: Fixed Frame Viewport, Navbar Statis di Atas, Hanya Main yang Scroll -->
<div class="h-full w-full bg-white rounded-2xl sm:rounded-3xl border border-slate-200/90 shadow-sm flex flex-col overflow-hidden relative min-h-0">

    <!-- ========================================================= -->
    <!-- TOP NAVBAR (HEADER) - TETAP DI ATAS / NON-SCROLLABLE      -->
    <!-- ========================================================= -->
    <header class="flex-shrink-0 px-5 py-5 sm:px-8 flex items-center justify-between z-20 border-b border-slate-100 bg-white">
        
        <!-- Left: Brand / Logo -->
        <div class="flex items-center gap-3">
            <a href="{{ route($role . '.dashboard') }}" class="flex items-center gap-2.5 group">
                <img src="{{ asset('assets/img/pesatcare.png') }}" alt="PesatCare" class="h-8 object-contain">
            </a>
        </div>

        <!-- Center: Navigation Links (Clean Segmented Tab with #20497B, #4F69CC, and #ACE5FF accents) -->
        <nav class="hidden md:flex items-center gap-1 bg-slate-100/90 p-1 rounded-xl border border-slate-200/70">
            @foreach($userMenus as $m)
                @php
                    $isActive = request()->routeIs($m['active']) || request()->routeIs($m['route']);
                @endphp
                <a href="{{ route($m['route']) }}"
                   class="px-4 py-1.5 rounded-lg text-xs transition-all duration-150 {{ $isActive ? 'bg-[#20497B] text-white font-semibold shadow-xs' : 'text-slate-600 hover:text-[#4F69CC] hover:bg-[#ACE5FF]/20 font-medium' }}">
                    {{ $m['label'] }}
                </a>
            @endforeach
        </nav>

        <!-- Right Side: Actions & Profile -->
        <div class="flex items-center gap-3">
            
            <!-- Role Badge Pill with #ACE5FF accent -->
            <div class="hidden sm:inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-[#ACE5FF]/30 text-[#20497B] border border-[#ACE5FF] capitalize">
                {{ $role }}
            </div>

            <!-- User Profile Avatar & Dropdown Menu -->
            <div class="relative flex items-center" x-data="{ profileOpen: false }">
                <button @click="profileOpen = !profileOpen" class="flex items-center gap-2 focus:outline-none group cursor-pointer">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#20497B] to-[#4F69CC] text-white font-bold text-xs flex items-center justify-center shadow-xs ring-2 ring-[#ACE5FF]/50">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <span class="hidden lg:block text-xs font-semibold text-slate-700 group-hover:text-[#4F69CC] transition-colors">{{ strtok($user->name, ' ') }}</span>
                    <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#4F69CC] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Card -->
                <div x-show="profileOpen" 
                     @click.away="profileOpen = false" 
                     x-cloak
                     class="absolute right-0 top-10 w-48 bg-white rounded-xl shadow-lg border border-slate-200 py-1.5 z-50 text-xs">
                    <div class="px-3.5 py-2 border-b border-slate-100 bg-slate-50/50">
                        <p class="font-semibold text-slate-900 truncate">{{ $user->name }}</p>
                        <p class="text-[#4F69CC] capitalize text-[11px] font-medium">{{ $role }}</p>
                    </div>
                    <a href="{{ route($role . '.profile') }}" class="block px-3.5 py-2 text-slate-700 hover:bg-[#ACE5FF]/20 hover:text-[#20497B] font-medium transition">
                        Profil Saya
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3.5 py-2 text-rose-600 hover:bg-rose-50 font-medium transition cursor-pointer">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Mobile Hamburger Toggle Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 focus:outline-none transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

        </div>
    </header>

    <!-- Mobile Navigation Drawer (Collapsible) -->
    <div x-show="mobileMenuOpen" x-cloak class="flex-shrink-0 md:hidden bg-slate-50 border-b border-slate-200 px-4 py-3 space-y-1 z-30">
        @foreach($userMenus as $m)
            @php
                $isActive = request()->routeIs($m['active']) || request()->routeIs($m['route']);
            @endphp
            <a href="{{ route($m['route']) }}"
               class="block px-3 py-2 rounded-lg text-xs font-semibold transition {{ $isActive ? 'bg-[#20497B] text-white shadow-xs' : 'text-slate-700 hover:bg-[#ACE5FF]/20 hover:text-[#20497B]' }}">
                {{ $m['label'] }}
            </a>
        @endforeach
    </div>

    <!-- ========================================================= -->
    <!-- MAIN WORKSPACE CONTENT AREA - HANYA INI YANG SCROLLABLE   -->
    <!-- ========================================================= -->
    <main class="flex-1 min-h-0 overflow-y-auto p-4 sm:p-7 bg-slate-50/50">

        <!-- Flash Notification: Success -->
        @if(session('success'))
            <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center gap-2.5">
                <div class="w-5 h-5 rounded-md bg-emerald-500 text-white flex items-center justify-center text-[10px] flex-shrink-0 font-bold">✓</div>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Flash Notification: Errors -->
        @if($errors->any())
            <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
                <p class="font-bold">Mohon periksa kembali input Anda:</p>
                <ul class="list-disc list-inside pl-1 space-y-0.5">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Child Blade View Content Container -->
        <div class="max-w-7xl mx-auto space-y-6 pb-6">
            @yield('content')
        </div>

    </main>

</div>
@endauth

@guest
    <div class="h-screen w-screen overflow-y-auto bg-slate-100 p-4 flex flex-col items-center justify-center">
        @yield('content')
    </div>
@endguest

</body>
</html>
