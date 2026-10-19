<x-guest-layout>
    <!-- Card Utama Pembungkus Proporsional di Tengah Layar -->
    <div class="w-full max-w-4xl bg-white rounded-3xl shadow-2xl shadow-[#3368A0]/15 border border-[#C8DFDB]/50 overflow-hidden flex flex-col md:flex-row transition-all duration-300">
        
        <!-- Sisi Kiri: Branding & Medical Identity Panel -->
        <div class="md:w-5/12 p-8 sm:p-10 flex flex-col justify-between text-white relative overflow-hidden bg-gradient-to-br from-[#3368A0] via-[#2F6196] to-[#254F7B]">
            <!-- Subtle Medical Geometric Accent Background -->
            <div class="absolute inset-0 opacity-10 pointer-events-none bg-[radial-gradient(#FFFFFF_1.5px,transparent_1.5px)] [background-size:18px_18px]"></div>
            
            <!-- Soft Ambient Light Glow -->
            <div class="absolute -top-24 -left-24 w-60 h-60 rounded-full bg-[#66A3BF]/40 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-60 h-60 rounded-full bg-[#C8DFDB]/20 blur-3xl pointer-events-none"></div>

            <!-- Top Header & Logo -->
            <div class="relative z-10">
                <div class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-white/20 shadow-inner">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-white text-[#3368A0] shadow-sm">
                        <!-- Icon Palang Medis Presisi -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-lg font-bold tracking-tight text-white block leading-tight">PesatCare</span>
                        <span class="text-[10px] tracking-wider uppercase font-semibold text-[#C8DFDB] block">UKS SMK PESAT</span>
                    </div>
                </div>
            </div>

            <!-- Content Area / Pitch -->
            <div class="relative z-10 my-8">
                <div class="inline-block px-3 py-1 rounded-full bg-white/10 text-[#C8DFDB] text-[11px] font-semibold tracking-wide uppercase mb-3.5 border border-white/15">
                    Sistem Layanan Medis Sekolah
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight leading-snug text-white mb-3">
                    Sehat Bersama,<br />
                    Tumbuh Berprestasi.
                </h1>
                <p class="text-xs sm:text-sm text-slate-100/90 leading-relaxed font-normal">
                    Pusat penanganan triase darurat, pencatatan rekam kunjungan, manajemen stok obat, serta skrining kesehatan siswa dan guru.
                </p>

                <!-- 3 Role Access Badges -->
                <div class="mt-6 pt-5 border-t border-white/15 grid grid-cols-3 gap-2.5">
                    <div class="text-center p-2.5 rounded-xl bg-white/10 backdrop-blur-xs border border-white/10 hover:bg-white/15 transition">
                        <span class="block text-[11px] font-bold text-white">Admin UKS</span>
                        <span class="block text-[9px] text-[#C8DFDB]">Penjaga UKS</span>
                    </div>
                    <div class="text-center p-2.5 rounded-xl bg-white/10 backdrop-blur-xs border border-white/10 hover:bg-white/15 transition">
                        <span class="block text-[11px] font-bold text-white">Guru</span>
                        <span class="block text-[9px] text-[#C8DFDB]">Pemeriksaan</span>
                    </div>
                    <div class="text-center p-2.5 rounded-xl bg-white/10 backdrop-blur-xs border border-white/10 hover:bg-white/15 transition">
                        <span class="block text-[11px] font-bold text-white">Murid</span>
                        <span class="block text-[9px] text-[#C8DFDB]">Data Mandiri</span>
                    </div>
                </div>
            </div>

            <!-- Footer Left Panel -->
            <div class="relative z-10 flex items-center justify-between text-[11px] text-[#C8DFDB]/80 pt-3 border-t border-white/10">
                <span>&copy; {{ date('Y') }} SMK Pesat IT XPRO</span>
                <span class="inline-flex items-center space-x-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="font-medium">Sistem Aktif</span>
                </span>
            </div>
        </div>

        <!-- Sisi Kanan: Form Login -->
        <div class="md:w-7/12 p-8 sm:p-12 flex flex-col justify-center bg-white">
            <div class="w-full max-w-md mx-auto">
                
                <!-- Header Form -->
                <div class="mb-7">
                    <div class="flex items-center space-x-2 text-[#66A3BF] text-xs font-semibold uppercase tracking-wider mb-1.5">
                        <span class="w-2 h-2 rounded-full bg-[#3368A0]"></span>
                        <span>Portal Masuk Pengguna</span>
                    </div>
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900">Masuk ke PesatCare</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Masukkan identitas dan kata sandi Anda untuk mengakses layanan.
                    </p>
                </div>

                <!-- Session Status Message -->
                <x-auth-session-status class="mb-5" :status="session('status')" />

                @if ($errors->any())
                    <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-start gap-2.5 shadow-sm">
                        <svg class="w-4 h-4 text-rose-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <div class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Form Login -->
                <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4" x-data="{ showPassword: false }">
                    @csrf

                    <!-- Identifier Input (Username, Email, NIP, or NIS) -->
                    <div>
                        <label for="login" class="block text-xs font-semibold text-gray-700 tracking-wide uppercase mb-1.5">
                            Username / E-mail / NIP / NIS
                        </label>
                        <input 
                            id="login" 
                            type="text" 
                            name="login" 
                            value="{{ old('login', old('email', old('username'))) }}" 
                            required 
                            autofocus 
                            autocomplete="username"
                            placeholder="cth: shifa, email@sekolah.sch.id, atau NIS/NIP"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/50 text-gray-900 text-sm placeholder:text-gray-400 focus:bg-white focus:border-[#3368A0] focus:ring-4 focus:ring-[#3368A0]/10 outline-none transition duration-200"
                        />
                    </div>

                    <!-- Password Input with Toggle View -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-semibold text-gray-700 tracking-wide uppercase">
                                Kata Sandi
                            </label>
                            @if (Route::has('password.request'))
                                <a 
                                    href="{{ route('password.request') }}" 
                                    class="text-xs font-medium text-[#3368A0] hover:text-[#275382] hover:underline transition-colors"
                                >
                                    Lupa sandi?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <input 
                                id="password" 
                                :type="showPassword ? 'text' : 'password'" 
                                name="password" 
                                required 
                                autocomplete="current-password"
                                placeholder="Masukkan kata sandi"
                                class="w-full px-4 py-3 pr-11 rounded-xl border border-gray-200 bg-gray-50/50 text-gray-900 text-sm placeholder:text-gray-400 focus:bg-white focus:border-[#3368A0] focus:ring-4 focus:ring-[#3368A0]/10 outline-none transition duration-200"
                            />
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none transition-colors cursor-pointer"
                                tabindex="-1"
                                :aria-label="showPassword ? 'Sembunyikan password' : 'Lihat password'"
                            >
                                <!-- Eye Icon (Show) -->
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <!-- Eye Slash Icon (Hide) -->
                                <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-1">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                            <input 
                                id="remember_me" 
                                type="checkbox" 
                                name="remember" 
                                class="w-4 h-4 rounded border-gray-300 text-[#3368A0] focus:ring-[#3368A0]/30 transition"
                            />
                            <span class="ms-2 text-xs text-gray-600">Ingat sesi saya di perangkat ini</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-3">
                        <button 
                            type="submit" 
                            class="w-full inline-flex items-center justify-center px-5 py-3.5 rounded-xl bg-[#3368A0] hover:bg-[#275382] active:bg-[#1E4166] text-white font-semibold text-sm shadow-lg shadow-[#3368A0]/25 transition duration-200 focus:outline-none focus:ring-4 focus:ring-[#3368A0]/30 cursor-pointer"
                        >
                            <span>Masuk ke Akun</span>
                            <svg class="w-4 h-4 ms-2 -me-0.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Help / Support Notice -->
                <div class="mt-8 pt-5 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-500">
                    <div class="flex items-center space-x-1.5">
                        <svg class="w-3.5 h-3.5 text-[#66A3BF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        <span>Pendaftaran akun dikelola oleh Admin UKS.</span>
                    </div>
                    <span class="text-gray-400 font-medium">v1.0</span>
                </div>

            </div>
        </div>

    </div>
</x-guest-layout>