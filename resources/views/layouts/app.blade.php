<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
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
</head>
<body class="h-full bg-[#2C5EAD] font-sans text-neutral-800 antialiased selection:bg-[#1591DC] selection:text-white" x-data="{ sidebarOpen: false }">

@auth
@php
    $user = auth()->user();
    $role = $user->role ?? 'admin';
    $currentLang = strtoupper(app()->getLocale());

    // Menu list per role dengan i18n
    $menus = [
        'admin' => [
            [
                'route' => 'admin.dashboard',
                'active' => 'admin.dashboard',
                'label' => __('Dashboard'),
                'icon' => 'dashboard',
            ],
            [
                'route' => 'admin.visits.index',
                'active' => 'admin.visits.*',
                'label' => __('Kunjungan'),
                'icon' => 'stethoscope',
            ],
            [
                'route' => 'admin.diseases.index',
                'active' => 'admin.diseases.*',
                'label' => __('Penyakit'),
                'icon' => 'activity',
            ],
            [
                'route' => 'admin.medicines.index',
                'active' => 'admin.medicines.*',
                'label' => __('Obat'),
                'icon' => 'pill',
            ],
            [
                'route' => 'admin.screenings.index',
                'active' => 'admin.screenings.*',
                'label' => __('Screening'),
                'icon' => 'clipboard',
            ],
            [
                'route' => 'admin.teacher-checks.index',
                'active' => 'admin.teacher-checks.*',
                'label' => __('Periksa Guru'),
                'icon' => 'user-check',
            ],
            [
                'route' => 'admin.certificates.index',
                'active' => 'admin.certificates.*',
                'label' => __('Surat'),
                'icon' => 'file-text',
            ],
            [
                'route' => 'admin.reports',
                'active' => 'admin.reports*',
                'label' => __('Rekap'),
                'icon' => 'chart',
            ],
            [
                'route' => 'admin.triage-rules.index',
                'active' => 'admin.triage-rules.*',
                'label' => __('Aturan Triase'),
                'icon' => 'shield',
            ],
            [
                'route' => 'admin.classes.index',
                'active' => 'admin.classes.*',
                'label' => __('Kelas'),
                'icon' => 'academic',
            ],
            [
                'route' => 'admin.units.index',
                'active' => 'admin.units.*',
                'label' => __('Unit'),
                'icon' => 'building',
            ],
            [
                'route' => 'admin.students.index',
                'active' => 'admin.students.*',
                'label' => __('Siswa'),
                'icon' => 'users',
            ],
            [
                'route' => 'admin.teachers.index',
                'active' => 'admin.teachers.*',
                'label' => __('Guru'),
                'icon' => 'id-card',
            ],
            [
                'route' => 'admin.users.index',
                'active' => 'admin.users.*',
                'label' => __('Akun'),
                'icon' => 'settings',
            ],
        ],
        'guru' => [
            [
                'route' => 'guru.dashboard',
                'active' => 'guru.dashboard',
                'label' => __('Dashboard'),
                'icon' => 'dashboard',
            ],
            [
                'route' => 'guru.profile',
                'active' => 'guru.profile',
                'label' => __('Profil'),
                'icon' => 'user',
            ],
            [
                'route' => 'guru.health-checks',
                'active' => 'guru.health-checks*',
                'label' => __('Pemeriksaan'),
                'icon' => 'stethoscope',
            ],
            [
                'route' => 'guru.visits',
                'active' => 'guru.visits*',
                'label' => __('Kunjungan'),
                'icon' => 'activity',
            ],
        ],
        'murid' => [
            [
                'route' => 'murid.dashboard',
                'active' => 'murid.dashboard',
                'label' => __('Dashboard'),
                'icon' => 'dashboard',
            ],
            [
                'route' => 'murid.profile',
                'active' => 'murid.profile',
                'label' => __('Profil'),
                'icon' => 'user',
            ],
            [
                'route' => 'murid.visits',
                'active' => 'murid.visits*',
                'label' => __('Kunjungan'),
                'icon' => 'activity',
            ],
            [
                'route' => 'murid.screenings',
                'active' => 'murid.screenings*',
                'label' => __('Screening'),
                'icon' => 'clipboard',
            ],
            [
                'route' => 'murid.certificates',
                'active' => 'murid.certificates*',
                'label' => __('Surat'),
                'icon' => 'file-text',
            ],
        ],
    ];

    $userMenus = $menus[$role] ?? [];
@endphp

<div class="flex h-screen w-full overflow-hidden bg-[#2C5EAD]">

    <!-- ========================================================= -->
    <!-- DESKTOP SIDEBAR (#2C5EAD)                                -->
    <!-- ========================================================= -->
    <aside class="hidden lg:flex lg:flex-col lg:w-60 flex-shrink-0 bg-[#2C5EAD] text-white select-none">

        <!-- Logo Section -->
        <div class="flex items-center justify-center px-4">
            <img
                src="{{ asset('assets/img/pesatcare.svg') }}"
                alt="PesatCare"
                class="w-[120px] h-[120px] object-contain drop-shadow-sm transition-transform duration-200 hover:scale-105"
            >
        </div>

        <!-- Navigation Links List -->
        <div class="flex-1 overflow-y-auto overflow-x-hidden pl-4 pr-0 pt-6 pb-6 space-y-1 no-scrollbar">
            @foreach($userMenus as $m)
                @php
                    $isActive = request()->routeIs($m['active']) || request()->routeIs($m['route']);
                @endphp
                <a href="{{ route($m['route']) }}"
                   class="block pl-6 pr-4 py-2.5 text-[14.5px] transition-all duration-150 {{ $isActive ? 'sidebar-tab-active' : 'text-white/80 hover:text-white font-medium hover:translate-x-1' }}">
                    <span class="truncate block">{{ $m['label'] }}</span>
                </a>
            @endforeach
        </div>

        <!-- Sidebar Footer -->
        <div class="px-4 py-4 border-t border-white/10 flex items-center justify-between gap-2.5">
            <a @if(Route::has('profile.edit')) href="{{ route('profile.edit') }}" @endif
               class="flex items-center gap-2.5 min-w-0 group hover:opacity-90 transition p-1 rounded-xl"
               title="{{ __('Profil Saya') }}">
                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-[#1591DC] to-[#C4E2F5] text-white font-bold text-sm flex items-center justify-center ring-2 ring-white/20 shadow-sm flex-shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="flex flex-col min-w-0 leading-tight">
                    <span class="text-sm font-semibold text-white truncate max-w-[95px] group-hover:text-[#C4E2F5] transition-colors">{{ $user->name }}</span>
                    <span class="text-[11px] font-medium text-[#C4E2F5] capitalize truncate">{{ $role }}</span>
                </div>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                @csrf
                <button type="submit"
                        title="{{ __('Keluar') }}"
                        class="p-2 rounded-xl text-white/80 hover:text-white hover:bg-white/10 transition-all duration-150 flex items-center justify-center">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                </button>
            </form>
        </div>
    </aside>

    <!-- ========================================================= -->
    <!-- MOBILE SIDEBAR DRAWER (OFF-CANVAS)                       -->
    <!-- ========================================================= -->
    <div x-show="sidebarOpen"
         x-cloak
         class="fixed inset-0 z-50 lg:hidden flex"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <!-- Backdrop -->
        <div class="fixed inset-0 bg-neutral-900/60 backdrop-blur-sm" @click="sidebarOpen = false"></div>

        <!-- Drawer Content -->
        <div class="relative flex-1 flex flex-col max-w-xs w-full bg-[#2C5EAD] text-white shadow-2xl z-10"
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full">
            
            <!-- Mobile Header with Close -->
            <div class="flex items-center justify-between px-6 pt-6 pb-4">
                <div class="flex items-center justify-center flex-1">
                    <img src="{{ asset('assets/img/pesatcare.svg') }}" alt="PesatCare" class="w-16 h-16 object-contain drop-shadow-sm">
                </div>

                <button @click="sidebarOpen = false" class="p-2 rounded-xl text-white/80 hover:text-white hover:bg-white/10">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu Links -->
            <div class="flex-1 overflow-y-auto px-4 py-2 space-y-1 no-scrollbar">
                @foreach($userMenus as $m)
                    @php
                        $isActive = request()->routeIs($m['active']) || request()->routeIs($m['route']);
                    @endphp
                    <a href="{{ route($m['route']) }}"
                       @click="sidebarOpen = false"
                       class="block px-5 py-2.5 rounded-2xl text-[14.5px] font-medium transition-all {{ $isActive ? 'bg-white text-[#2C5EAD] font-bold shadow-md' : 'text-white/80 hover:text-white hover:bg-white/10' }}">
                        <span>{{ $m['label'] }}</span>
                    </a>
                @endforeach
            </div>

            <!-- Mobile Footer -->
            <div class="p-4 border-t border-white/10 flex items-center justify-between gap-2.5">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-[#1591DC] to-[#C4E2F5] text-white font-bold text-sm flex items-center justify-center ring-2 ring-white/20 shadow-sm flex-shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="flex flex-col min-w-0 leading-tight">
                        <span class="text-sm font-semibold text-white truncate max-w-[120px]">{{ $user->name }}</span>
                        <span class="text-[11px] font-medium text-[#C4E2F5] capitalize truncate">{{ $role }}</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                    @csrf
                    <button type="submit" title="{{ __('Keluar') }}" class="p-2 rounded-xl text-white/80 hover:text-white hover:bg-white/10 transition">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- MAIN CONTENT CARD / WORKSPACE                            -->
    <!-- ========================================================= -->
    <div class="flex-1 flex flex-col h-full overflow-hidden bg-[#FAFBFD] relative">

        <!-- ===================================================== -->
        <!-- TOP NAVBAR (HEADER)                                   -->
        <!-- ===================================================== -->
        <header class="flex-shrink-0 h-20 px-6 sm:px-10 border-b border-slate-100/80 flex items-center justify-between bg-white/80 backdrop-blur-md z-20">
            
            <!-- Left: Mobile Toggle & Search Bar -->
            <div class="flex items-center gap-3 sm:gap-6 flex-1 max-w-xl">
                <!-- Mobile Hamburger Button -->
                <button @click="sidebarOpen = true"
                        class="lg:hidden p-2.5 rounded-2xl bg-slate-100 hover:bg-[#C4E2F5]/40 text-neutral-700 hover:text-[#2C5EAD] focus:outline-none transition">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>

                <!-- Pill Search Bar -->
                <div class="relative w-full max-w-xs sm:max-w-md">
                    <input type="text"
                           placeholder="{{ __('Cari...') }}"
                           class="w-full pl-5 pr-10 py-2.5 bg-slate-100/90 hover:bg-slate-100 focus:bg-white border border-transparent focus:border-[#1591DC] rounded-full text-sm text-neutral-800 placeholder-neutral-400 focus:outline-none focus:ring-4 focus:ring-[#C4E2F5]/50 transition-all duration-150" />
                    <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-neutral-400">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Right: Language Switcher Dropdown & Quick Icons -->
            <div class="flex items-center gap-2.5 sm:gap-3.5 flex-shrink-0">
                
                <!-- Language Selector Dropdown -->
                <div class="hidden md:relative md:block" x-data="{ langOpen: false }">
                    <button @click="langOpen = !langOpen" 
                            @click.away="langOpen = false"
                            type="button" 
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 text-neutral-700 hover:bg-[#C4E2F5]/40 hover:text-[#2C5EAD] transition cursor-pointer text-xs font-semibold focus:outline-none">
                        <span>{{ $currentLang }}</span>
                        <svg class="w-3.5 h-3.5 text-neutral-400 transition-transform duration-200" :class="{ 'rotate-180': langOpen }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>

                    <!-- Dropdown Options -->
                    <div x-show="langOpen" 
                         x-cloak
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-32 bg-white rounded-2xl shadow-xl border border-slate-100 py-1.5 z-50 text-xs font-medium">
                        <a href="{{ route('lang.switch', 'en') }}" class="flex items-center justify-between px-3.5 py-2 hover:bg-slate-50 text-neutral-700 hover:text-[#2C5EAD] transition {{ app()->getLocale() === 'en' ? 'font-bold text-[#2C5EAD]' : '' }}">
                            <span>English</span>
                            @if(app()->getLocale() === 'en')
                                <span class="w-1.5 h-1.5 rounded-full bg-[#1591DC]"></span>
                            @endif
                        </a>
                        <a href="{{ route('lang.switch', 'id') }}" class="flex items-center justify-between px-3.5 py-2 hover:bg-slate-50 text-neutral-700 hover:text-[#2C5EAD] transition {{ app()->getLocale() === 'id' ? 'font-bold text-[#2C5EAD]' : '' }}">
                            <span>Indonesia</span>
                            @if(app()->getLocale() === 'id')
                                <span class="w-1.5 h-1.5 rounded-full bg-[#1591DC]"></span>
                            @endif
                        </a>
                    </div>
                </div>

                <!-- Messages Quick Button -->
                <button type="button"
                        class="p-2.5 rounded-2xl bg-slate-100 hover:bg-[#C4E2F5]/40 text-neutral-700 hover:text-[#2C5EAD] transition focus:outline-none relative"
                        title="{{ __('Pesan') }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                </button>

                <!-- Notifications Button with Badge -->
                <button type="button"
                        class="p-2.5 rounded-2xl bg-slate-100 hover:bg-[#C4E2F5]/40 text-neutral-700 hover:text-[#2C5EAD] transition focus:outline-none relative"
                        title="{{ __('Notifikasi') }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    <!-- Status dot -->
                    <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-[#1591DC] ring-2 ring-white"></span>
                </button>

            </div>
        </header>

        <!-- ===================================================== -->
        <!-- MAIN SCROLLABLE CONTENT AREA                          -->
        <!-- ===================================================== -->
        <main class="flex-1 overflow-y-auto px-4 sm:px-8 py-6 sm:py-8 space-y-6 scrollbar-thin">
            
            <!-- Flash Notification: Success -->
            @if(session('success'))
                <div class="flex items-center gap-3 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm shadow-sm">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    <div class="font-medium">{{ session('success') }}</div>
                </div>
            @endif

            <!-- Flash Notification: Errors -->
            @if($errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-sm">
                    <div class="flex items-center gap-2.5 font-semibold text-rose-900 mb-1.5">
                        <svg class="w-5 h-5 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <span>{{ __('Periksa kesalahan berikut:') }}</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-xs text-rose-700 pl-2">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Child Page Content -->
            <div class="w-full max-w-7xl mx-auto space-y-6">
                @yield('content')
            </div>

        </main>
    </div>

</div>
@endauth

@guest
    <div class="min-h-screen bg-[#FAFBFD] p-6 flex flex-col items-center justify-center">
        @yield('content')
    </div>
@endguest

</body>
</html>