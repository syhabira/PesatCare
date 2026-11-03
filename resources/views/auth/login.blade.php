<x-guest-layout>
    <!-- Card Utama Pembungkus Proporsional di Tengah Layar -->
    <div class="w-full max-w-4xl bg-white rounded-2xl sm:rounded-3xl shadow-xl shadow-slate-200/80 border border-slate-200/90 overflow-hidden flex flex-col md:flex-row transition-all">
        
        <!-- Sisi Kiri: Branding & Medical Identity Panel (#20497B, #4F69CC, #ACE5FF) -->
        <div class="md:w-5/12 p-8 sm:p-10 flex flex-col justify-between text-white relative bg-[#20497B] border-r border-[#4F69CC]/20">
            <!-- Subtle Grid Background -->
            <div class="absolute inset-0 opacity-[0.07] pointer-events-none bg-[radial-gradient(#ACE5FF_1px,transparent_1px)] [background-size:16px_16px]"></div>


            <!-- Content Area: Educational & Healthcare Purpose -->
            <div class="relative z-10 my-8 sm:my-10">
                <span class="inline-block px-2.5 py-1 rounded-md bg-[#4F69CC]/40 text-[#ACE5FF] text-xs font-semibold tracking-wide mb-3 border border-[#ACE5FF]/30">
                    Sistem UKS Sekolah Pesat
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight leading-snug text-white mb-3">
                    Pelayanan Kesehatan Sekolah.
                </h1>
                <p class="text-xs sm:text-sm text-slate-200 leading-relaxed font-normal">
                    Pusat penanganan triase darurat, rekam kunjungan siswa, pemantauan guru piket, serta skrining kesehatan berkala.
                </p>

                <!-- Layanan Utama UKS -->
                <div class="mt-6 space-y-2.5 text-xs text-slate-200">
                    <div class="flex items-center gap-2.5">
                        <div class="w-5 h-5 rounded-full bg-[#ACE5FF]/20 text-[#ACE5FF] flex items-center justify-center flex-shrink-0 text-[11px] font-bold border border-[#ACE5FF]/30">✓</div>
                        <span>Pencatatan keluhan & tindakan medis siswa</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="w-5 h-5 rounded-full bg-[#ACE5FF]/20 text-[#ACE5FF] flex items-center justify-center flex-shrink-0 text-[11px] font-bold border border-[#ACE5FF]/30">✓</div>
                        <span>Pemeriksaan rutin & skrining kebugaran</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="w-5 h-5 rounded-full bg-[#ACE5FF]/20 text-[#ACE5FF] flex items-center justify-center flex-shrink-0 text-[11px] font-bold border border-[#ACE5FF]/30">✓</div>
                        <span>Surat izin resmi istirahat & izin pulang</span>
                    </div>
                </div>
            </div>

            <!-- Footer Left Panel -->
            <div class="relative z-10 flex items-center justify-between text-xs text-[#ACE5FF]/80 pt-4 border-t border-white/10">
                <span>&copy; {{ date('Y') }} SMK Informatika Pesat</span>
            </div>
        </div>

        <!-- Sisi Kanan: Form Login -->
        <div class="md:w-7/12 p-8 sm:p-12 flex flex-col justify-center bg-white">
            <div class="w-full max-w-md mx-auto">
                
                <!-- Header Form -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900">Masuk ke Akun</h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Gunakan akun terdaftar Anda untuk mengakses portal PesatCare.
                    </p>
                </div>

                <!-- Session Status Message -->
                <x-auth-session-status class="mb-5" :status="session('status')" />

                @if ($errors->any())
                    <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-start gap-2.5">
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
                        <label for="login" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Username / Email / NIP / NIS
                        </label>
                        <input 
                            id="login" 
                            type="text" 
                            name="login" 
                            value="{{ old('login', old('email', old('username'))) }}" 
                            required 
                            autofocus 
                            autocomplete="username"
                            placeholder="cth: NIS, NIP, atau username"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-sm placeholder:text-slate-400 focus:border-[#4F69CC] focus:ring-4 focus:ring-[#ACE5FF]/50 outline-none transition"
                        />
                    </div>

                    <!-- Password Input with Toggle View -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                Kata Sandi
                            </label>
                            @if (Route::has('password.request'))
                                <a 
                                    href="{{ route('password.request') }}" 
                                    class="text-xs font-medium text-[#4F69CC] hover:text-[#20497B] hover:underline"
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
                                placeholder="Masukkan kata sandi akun"
                                class="w-full px-3.5 py-2.5 pr-10 rounded-xl border border-slate-300 bg-white text-slate-900 text-sm placeholder:text-slate-400 focus:border-[#4F69CC] focus:ring-4 focus:ring-[#ACE5FF]/50 outline-none transition"
                            />
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-[#4F69CC] focus:outline-none transition cursor-pointer"
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
                                class="w-4 h-4 rounded border-slate-300 text-[#20497B] focus:ring-[#ACE5FF] transition"
                            />
                            <span class="ms-2 text-xs text-slate-600">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button 
                            type="submit" 
                            class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl bg-[#20497B] hover:bg-[#183960] active:bg-[#122b49] text-white font-semibold text-sm shadow-md shadow-[#20497B]/20 transition duration-150 focus:outline-none focus:ring-4 focus:ring-[#ACE5FF]/50 cursor-pointer"
                        >
                            <span>Masuk ke Akun</span>
                            <svg class="w-4 h-4 ms-2 -me-0.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Help / Support Notice -->
                <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                    <div class="flex items-center space-x-1.5">
                        <svg class="w-3.5 h-3.5 text-[#4F69CC]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        <span>Akun siswa dan guru dikelola oleh petugas UKS sekolah.</span>
                    </div>
                    <span class="text-[#20497B] font-semibold">PesatCare</span>
                </div>

            </div>
        </div>

    </div>
</x-guest-layout>
