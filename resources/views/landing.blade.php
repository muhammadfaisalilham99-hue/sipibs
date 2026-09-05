<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPIBS - Sistem Peminjaman & Inventaris Barang Sekolah</title>
    <!-- Font Setup -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Hanken Grotesk"', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#e8f0fe',
                            100: '#c7d6fa',
                            200: '#a3bbf6',
                            300: '#7c9ef1',
                            400: '#5c86ec',
                            500: '#346ae6',
                            600: '#1b52dd',
                            700: '#0d47a1',
                            800: '#093685',
                            900: '#05276b',
                        }
                    }
                },
            },
        }
    </script>
    <style>
        @property --angle { syntax: '<angle>'; initial-value: 0deg; inherits: false; }
        .feature-card { position: relative; cursor: pointer; }
        .feature-card::before {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 26px;
            padding: 2px;
            background: conic-gradient(from var(--angle, 0deg), transparent 0%, #93c5fd 18%, #38bdf8 32%, #7c9ef1 50%, #93c5fd 68%, transparent 90%);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.35s ease;
            pointer-events: none;
        }
        .feature-card.active::before { opacity: 1; animation: border-spin 3.5s linear infinite; }
        .feature-card.active { box-shadow: 0 0 28px rgba(56,189,248,0.35); }
        @keyframes border-spin { to { --angle: 360deg; } }
    </style>
    <style>
        body {
            background-color: #F4FAFF;
            background-image: radial-gradient(circle at 85% 15%, rgba(199, 225, 255, 0.45) 0%, transparent 45%),
                              radial-gradient(circle at 10% 90%, rgba(213, 235, 255, 0.5) 0%, transparent 45%);
            background-attachment: fixed;
        }

        .dot-pattern-tr {
            position: absolute;
            top: 6rem;
            right: 2.5rem;
            width: 120px;
            height: 120px;
            background-image: radial-gradient(#a3c4f6 2.5px, transparent 2.5px);
            background-size: 18px 18px;
            opacity: 0.5;
            z-index: 0;
        }

        .dot-pattern-bl {
            position: absolute;
            bottom: 6rem;
            left: 2.5rem;
            width: 120px;
            height: 120px;
            background-image: radial-gradient(#a3c4f6 2.5px, transparent 2.5px);
            background-size: 18px 18px;
            opacity: 0.5;
            z-index: 0;
        }
    </style>
</head>
<body class="text-slate-800 antialiased min-h-screen flex flex-col justify-between relative overflow-x-hidden">
    <!-- Dot Patterns -->
    <div class="dot-pattern-tr"></div>
    <div class="dot-pattern-bl"></div>

    <!-- Header -->
    <header class="w-full bg-white/70 backdrop-blur-md sticky top-0 z-50 border-b border-blue-100/40">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <!-- Logo & Brand -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/LOGO SIPIBS.jpeg') }}" alt="Logo SIPIBS" class="w-11 h-11 rounded-full object-cover shadow-md shadow-blue-500/20 shrink-0">
                <div class="flex items-center gap-3">
                    <span class="text-2xl font-black tracking-tight text-[#1b52dd]">SIPIBS</span>
                    <span class="h-4 w-[1.5px] bg-slate-300 hidden md:inline-block"></span>
                    <span class="text-sm font-medium text-slate-500 hidden md:inline-block">Sistem Peminjaman &amp; Inventaris Barang Sekolah</span>
                </div>
            </a>

            <!-- Action Button -->
            <a href="{{ url('/login') }}" class="bg-[#1b52dd] hover:bg-[#1544be] text-white px-6 py-2.5 rounded-full font-bold flex items-center gap-2 transition-all duration-200 shadow-md shadow-blue-600/25 text-sm">
                <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center">
                    <i class="ph-bold ph-user text-xs text-white"></i>
                </div>
                <span>Masuk</span>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col justify-center relative z-10 py-10 lg:py-12">
        <div class="max-w-7xl mx-auto px-6 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <!-- Left: Hero Heading & Description -->
                <div class="max-w-xl">
                    <img src="{{ asset('images/LOGO SIPIBS.jpeg') }}" alt="Logo SIPIBS" class="w-20 h-20 rounded-2xl object-cover shadow-lg shadow-blue-500/20 mb-6">
                    <h1 class="text-4xl sm:text-5xl lg:text-[54px] font-black leading-[1.12] text-slate-900 mb-4 tracking-tight">
                        Kelola Peminjaman <br>
                        &amp; <br class="hidden sm:inline">
                        <span class="text-[#1b52dd]">Inventaris Barang<br>Sekolah</span>
                    </h1>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-700 mb-5">
                        Mudah, Cepat, dan Terorganisir
                    </h2>
                    <p class="text-base sm:text-lg text-slate-600 mb-8 leading-relaxed max-w-md font-medium">
                        SIPIBS hadir untuk mendukung sekolah dalam mengelola peminjaman dan inventaris barang secara digital, efisien, dan terpercaya.
                    </p>
                    <a href="{{ url('/login') }}" class="inline-flex items-center gap-3 bg-[#1b52dd] hover:bg-[#1544be] text-white px-8 py-3.5 rounded-xl font-bold text-base transition-all duration-200 shadow-lg shadow-blue-600/30">
                        <span>Masuk</span>
                        <i class="ph-bold ph-arrow-right text-lg"></i>
                    </a>
                </div>

                <!-- Right: Feature Cards Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 relative">
                    <!-- Feature 1 -->
                    <div class="feature-card bg-white rounded-3xl p-7 shadow-xl shadow-slate-200/40 border border-slate-100 hover:-translate-y-1 transition-all duration-300">
                        <div class="w-14 h-14 bg-blue-50 text-[#1b52dd] rounded-2xl flex items-center justify-center mb-5">
                            <i class="ph-duotone ph-clipboard-text text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Peminjaman Mudah</h3>
                        <p class="text-sm text-slate-500 leading-relaxed font-medium">
                            Ajukan dan kelola peminjaman dengan cepat.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="feature-card bg-white rounded-3xl p-7 shadow-xl shadow-slate-200/40 border border-slate-100 hover:-translate-y-1 transition-all duration-300">
                        <div class="w-14 h-14 bg-blue-50 text-[#1b52dd] rounded-2xl flex items-center justify-center mb-5">
                            <i class="ph-duotone ph-cube text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Inventaris Terkelola</h3>
                        <p class="text-sm text-slate-500 leading-relaxed font-medium">
                            Data barang tersimpan rapi dan mudah dicari.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="feature-card bg-white rounded-3xl p-7 shadow-xl shadow-slate-200/40 border border-slate-100 hover:-translate-y-1 transition-all duration-300">
                        <div class="w-14 h-14 bg-blue-50 text-[#1b52dd] rounded-2xl flex items-center justify-center mb-5">
                            <i class="ph-duotone ph-chart-bar text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Laporan Lengkap</h3>
                        <p class="text-sm text-slate-500 leading-relaxed font-medium">
                            Laporan peminjaman dan inventaris lengkap dan akurat.
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="feature-card bg-white rounded-3xl p-7 shadow-xl shadow-slate-200/40 border border-slate-100 hover:-translate-y-1 transition-all duration-300">
                        <div class="w-14 h-14 bg-blue-50 text-[#1b52dd] rounded-2xl flex items-center justify-center mb-5">
                            <i class="ph-fill ph-shield-check text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Aman &amp; Terpercaya</h3>
                        <p class="text-sm text-slate-500 leading-relaxed font-medium">
                            Sistem aman dengan hak akses yang terkontrol.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Stats Container -->
        <div class="max-w-5xl mx-auto px-6 w-full mt-16 relative z-20">
            <div class="bg-white rounded-full py-5 px-10 shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6 divide-y md:divide-y-0 md:divide-x divide-slate-100">
                <!-- Stat Item 1 -->
                <div class="flex items-center gap-4 w-full md:w-1/3 justify-center pt-2 md:pt-0">
                    <div class="w-12 h-12 bg-blue-50 text-[#1b52dd] rounded-full flex items-center justify-center shrink-0">
                        <i class="ph-duotone ph-cube text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="text-2xl font-black text-slate-900">100+</h4>
                        <p class="text-slate-500 font-medium text-xs">Barang Tersedia</p>
                    </div>
                </div>

                <!-- Stat Item 2 -->
                <div class="flex items-center gap-4 w-full md:w-1/3 justify-center pt-4 md:pt-0">
                    <div class="w-12 h-12 bg-blue-50 text-[#1b52dd] rounded-full flex items-center justify-center shrink-0">
                        <i class="ph-duotone ph-arrows-clockwise text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="text-2xl font-black text-slate-900">200+</h4>
                        <p class="text-slate-500 font-medium text-xs">Transaksi</p>
                    </div>
                </div>

                <!-- Stat Item 3 -->
                <div class="flex items-center gap-4 w-full md:w-1/3 justify-center pt-4 md:pt-0">
                    <div class="w-12 h-12 bg-blue-50 text-[#1b52dd] rounded-full flex items-center justify-center shrink-0">
                        <i class="ph-fill ph-shield-check text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-black text-slate-900">Terpercaya</h4>
                        <p class="text-slate-500 font-medium text-xs">Digunakan oleh banyak sekolah</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full py-4 text-center text-xs text-slate-400 font-medium">
        © 2026 SIPIBS - Sistem Peminjaman &amp; Inventaris Barang Sekolah
    </footer>
    <script>
        document.querySelectorAll('.feature-card').forEach(function (card) {
            card.addEventListener('click', function () {
                var wasActive = this.classList.contains('active');
                document.querySelectorAll('.feature-card.active').forEach(function (c) { c.classList.remove('active'); });
                if (!wasActive) this.classList.add('active');
            });
        });
    </script>
</body>
</html>
