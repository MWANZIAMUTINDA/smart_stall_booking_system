<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Smart Stall Booking and M-Pesa Payment System for Muthurwa Market.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Muthurwa Stall Booking — Nairobi City County</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --nairobi-green: #068930;
            --nairobi-green-dark: #045a20;
            --nairobi-yellow: #FCDD07;
            --nairobi-yellow-hover: #e5c805;
            --dark-gray: #1a1a1b;
        }

        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            background-color: #fafafa;
        }

        /* ── Hero Gradient ── */
        .hero-gradient {
            background: linear-gradient(135deg, var(--dark-gray) 0%, var(--nairobi-green-dark) 60%, var(--nairobi-green) 100%);
            background-size: 200% 200%;
            animation: gradientMove 15s ease infinite;
        }
        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* ── 3D Glassmorphism Cards ── */
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform-style: preserve-3d;
        }
        .glass-card:hover {
            transform: translateY(-12px) rotateX(4deg) rotateY(4deg);
            box-shadow: 0 25px 50px rgba(6, 137, 48, 0.12);
            border-color: var(--nairobi-yellow);
        }

        /* ── Dark Glass (Hero) ── */
        .dark-glass {
            background: rgba(30, 30, 30, 0.5);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 30px 60px rgba(0,0,0,0.6);
        }

        /* ── Float Animations ── */
        .float-slow { animation: float 7s ease-in-out infinite; }
        .float-fast { animation: float 5s ease-in-out infinite reverse; }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        /* ── Buttons ── */
        .btn-green {
            background-color: var(--nairobi-green);
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(6, 137, 48, 0.3);
        }
        .btn-green:hover {
            background-color: var(--nairobi-green-dark);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(6, 137, 48, 0.5);
        }
        .btn-yellow {
            background-color: var(--nairobi-yellow);
            color: var(--dark-gray);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(252, 221, 7, 0.3);
        }
        .btn-yellow:hover {
            background-color: var(--nairobi-yellow-hover);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(252, 221, 7, 0.5);
        }

        /* ── Scroll Reveal ── */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* ── Background Pattern ── */
        .bg-pattern {
            background-image: radial-gradient(var(--nairobi-green) 1px, transparent 1px);
            background-size: 40px 40px;
            opacity: 0.04;
        }

        /* ── FAQ Accordion ── */
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease-out, padding 0.4s ease-out;
            padding: 0 1.5rem;
        }
        .faq-card.active .faq-answer {
            max-height: 300px;
            padding: 0 1.5rem 1.5rem 1.5rem;
        }
        .faq-card.active .faq-icon {
            transform: rotate(180deg);
            color: var(--nairobi-green);
        }
        .faq-card.active {
            border-color: var(--nairobi-green);
            background: #fff;
            box-shadow: 0 10px 25px rgba(6,137,48,0.05);
        }
    </style>
</head>
<body class="text-gray-800 selection:bg-green-600 selection:text-white">

    <!-- ══ NAVBAR ═════════════════════════════════════ -->
    <nav class="fixed w-full z-50 transition-all duration-300 bg-white/80 backdrop-blur-md border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <div class="font-black text-gray-900 leading-none tracking-tight">Nairobi City County</div>
                        <div class="text-[10px] text-green-600 font-bold uppercase tracking-widest mt-0.5">Muthurwa Market</div>
                    </div>
                </div>
                <div class="hidden md:flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-green-600 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-green-600 transition px-4 py-2">Sign in</a>
                        <a href="{{ route('register') }}" class="btn-green px-5 py-2.5 rounded-lg font-bold text-sm">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- ══ HERO SECTION ═════════════════════════════════════ -->
    <section class="hero-gradient min-h-screen relative flex items-center pt-20 overflow-hidden">
        <!-- Floating abstract shapes -->
        <div class="absolute top-1/4 left-5 w-64 h-64 bg-green-500/30 rounded-full blur-[80px]"></div>
        <div class="absolute bottom-1/4 right-5 w-80 h-80 bg-yellow-400/20 rounded-full blur-[100px]"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10 w-full grid lg:grid-cols-2 gap-12 items-center">
            
            <!-- Hero Text -->
            <div class="text-white pt-10 md:pt-0">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 mb-6 reveal">
                    <span class="w-2.5 h-2.5 rounded-full bg-yellow-400 animate-pulse"></span>
                    <span class="text-xs font-bold tracking-widest uppercase text-yellow-300">Official Portal</span>
                </div>
                
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-black tracking-tight leading-[1.1] mb-6 reveal" style="transition-delay: 100ms;">
                    Smart Stall Booking <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-500">& M-Pesa System</span>
                </h1>
                
                <p class="text-lg md:text-xl text-gray-300 mb-10 max-w-xl leading-relaxed reveal" style="transition-delay: 200ms;">
                    Welcome to the official Nairobi City County digital platform for Muthurwa Market. Find, book, and pay for your market stalls instantly, securely, and transparently.
                </p>
                
                <div class="flex flex-wrap gap-4 reveal" style="transition-delay: 300ms;">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-yellow px-8 py-4 rounded-xl font-bold text-lg inline-flex items-center gap-2">
                            Go to Dashboard
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn-yellow px-8 py-4 rounded-xl font-bold text-lg inline-flex items-center gap-2">
                            Get Started Now
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                        <a href="{{ route('login') }}" class="px-8 py-4 rounded-xl font-bold text-lg text-white bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 transition-all inline-flex items-center gap-2">
                            Login Account
                        </a>
                    @endauth
                </div>
            </div>

            <!-- 3D Floating Mockups (Hidden on small mobile) -->
            <div class="relative hidden lg:block h-[500px] reveal" style="transition-delay: 400ms;">
                
                <!-- Floating Card 1: Success -->
                <div class="absolute right-0 top-12 w-80 dark-glass rounded-3xl p-6 float-slow border-t-4 border-t-yellow-400">
                    <div class="flex justify-between items-center mb-6">
                        <div class="w-12 h-12 rounded-full bg-green-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status: Confirmed</span>
                    </div>
                    <div class="h-4 w-3/4 bg-white/10 rounded mb-3"></div>
                    <div class="h-4 w-1/2 bg-white/10 rounded mb-6"></div>
                    <div class="p-4 bg-gradient-to-r from-green-500/20 to-transparent rounded-xl border-l-2 border-green-500">
                        <div class="text-green-400 font-bold mb-1">Stall Allocated</div>
                        <div class="text-sm text-gray-300">Muthurwa Block A - 104</div>
                    </div>
                </div>

                <!-- Floating Card 2: M-Pesa -->
                <div class="absolute left-8 bottom-12 w-72 dark-glass rounded-3xl p-6 float-fast border-t-4 border-t-green-500 z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center p-2 shadow-lg">
                            <div class="w-full h-full bg-green-600 rounded-xl flex items-center justify-center text-white font-black text-xs">M-PESA</div>
                        </div>
                        <div>
                            <div class="font-bold text-white text-lg">Secure Payment</div>
                            <div class="text-xs text-gray-400">Instant Processing</div>
                        </div>
                    </div>
                    <div class="text-4xl font-black text-white mb-2">KES 1,500</div>
                    <div class="text-sm text-green-400 font-bold flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Paid Successfully
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Wave Divider -->
        <div class="absolute bottom-0 w-full">
            <svg viewBox="0 0 1440 120" class="w-full h-auto text-white fill-current"><path d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path></svg>
        </div>
    </section>

    <!-- ══ ABOUT SECTION ═════════════════════════════════════ -->
    <section class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto reveal">
                <div class="inline-block p-3 rounded-2xl bg-yellow-100 text-yellow-600 mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h2 class="text-sm font-bold text-green-600 tracking-widest uppercase mb-3">About The System</h2>
                <h3 class="text-3xl md:text-5xl font-black text-gray-900 mb-6">Transforming Muthurwa Market</h3>
                <p class="text-lg md:text-xl text-gray-600 leading-relaxed">
                    The Smart Stall Booking system replaces manual paperwork with a fast, secure, and transparent digital platform. We are committed to making it easier for traders to book stalls, process payments via M-Pesa securely, and manage their market operations seamlessly while reducing allocation delays and corruption.
                </p>
            </div>
        </div>
    </section>

    <!-- ══ FEATURES SECTION ═════════════════════════════════════ -->
    <section class="py-24 bg-gray-50 relative border-t border-gray-100">
        <div class="absolute inset-0 bg-pattern"></div>
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16 reveal">
                <h2 class="text-sm font-bold text-green-600 tracking-widest uppercase mb-3">System Features</h2>
                <h3 class="text-3xl md:text-5xl font-black text-gray-900">Everything You Need</h3>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="glass-card p-8 rounded-3xl reveal">
                    <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center mb-6 text-green-600 shadow-inner">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Online Stall Booking</h4>
                    <p class="text-gray-600 leading-relaxed">Browse available stalls in real-time, select your preferred location, and book instantly without visiting county offices.</p>
                </div>

                <!-- Feature 2 -->
                <div class="glass-card p-8 rounded-3xl reveal" style="transition-delay: 100ms;">
                    <div class="w-14 h-14 bg-yellow-100 rounded-2xl flex items-center justify-center mb-6 text-yellow-600 shadow-inner">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">M-Pesa Integration</h4>
                    <p class="text-gray-600 leading-relaxed">Secure and automated payments directly through Safaricom M-Pesa. Your receipt is generated immediately upon payment.</p>
                </div>

                <!-- Feature 3 -->
                <div class="glass-card p-8 rounded-3xl reveal" style="transition-delay: 200ms;">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mb-6 text-blue-600 shadow-inner">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Violation Management</h4>
                    <p class="text-gray-600 leading-relaxed">Official county enforcement with transparent violation tracking and digitized resolution processes for a fair market.</p>
                </div>

                <!-- Feature 4 -->
                <div class="glass-card p-8 rounded-3xl reveal">
                    <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center mb-6 text-purple-600 shadow-inner">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Email Notifications</h4>
                    <p class="text-gray-600 leading-relaxed">Receive instant updates, receipts, and important announcements directly to your registered email address.</p>
                </div>

                <!-- Feature 5 -->
                <div class="glass-card p-8 rounded-3xl reveal" style="transition-delay: 100ms;">
                    <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center mb-6 text-orange-600 shadow-inner">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Stall Availability Tracking</h4>
                    <p class="text-gray-600 leading-relaxed">A live visual map of the market showing exactly which stalls are vacant, occupied, or under maintenance.</p>
                </div>

                <!-- Feature 6 -->
                <div class="glass-card p-8 rounded-3xl reveal" style="transition-delay: 200ms;">
                    <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center mb-6 text-green-700 shadow-inner">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Secure User Accounts</h4>
                    <p class="text-gray-600 leading-relaxed">Your data is protected. Manage your personal information, booking history, and active leases from a secure dashboard.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ══ BENEFITS SECTION ═════════════════════════════════════ -->
    <section class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                
                <div class="order-2 lg:order-1 relative reveal">
                    <!-- Abstract 3D UI Block instead of an image to keep it clean and fast -->
                    <div class="relative w-full h-[500px] rounded-3xl bg-gray-50 border border-gray-100 shadow-2xl overflow-hidden flex items-center justify-center">
                        <div class="absolute inset-0 bg-pattern"></div>
                        <div class="absolute w-72 h-72 bg-yellow-300/30 rounded-full blur-[60px] top-10 left-10"></div>
                        <div class="absolute w-72 h-72 bg-green-500/20 rounded-full blur-[60px] bottom-10 right-10"></div>
                        
                        <div class="relative z-10 p-8 glass-card rounded-3xl w-3/4 max-w-sm text-center">
                            <div class="w-20 h-20 mx-auto bg-green-500 rounded-full flex items-center justify-center mb-6 shadow-xl shadow-green-500/30">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <h4 class="text-2xl font-black text-gray-900 mb-2">Fast Allocation</h4>
                            <p class="text-gray-500 text-sm">Experience the fastest stall approval process in Nairobi City County.</p>
                            <div class="mt-6 space-y-3">
                                <div class="h-3 w-full bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-green-500 w-[85%]"></div>
                                </div>
                                <div class="h-3 w-full bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-yellow-400 w-[60%]"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-1 lg:order-2 reveal">
                    <h2 class="text-sm font-bold text-yellow-500 tracking-widest uppercase mb-3">Traders First</h2>
                    <h3 class="text-3xl md:text-5xl font-black text-gray-900 mb-8">Why Use This Portal?</h3>
                    
                    <ul class="space-y-8">
                        <li class="flex items-start gap-5">
                            <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0 mt-1 shadow-inner">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900 mb-2">Saves Time</h4>
                                <p class="text-gray-600">No more spending hours in long queues at county offices. Complete your entire booking process from your phone in minutes.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-5">
                            <div class="w-10 h-10 rounded-xl bg-yellow-100 flex items-center justify-center flex-shrink-0 mt-1 shadow-inner">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900 mb-2">Easy & Secure Payment</h4>
                                <p class="text-gray-600">Integrated M-Pesa payments mean you never have to carry cash. Receipts are generated automatically and stored safely.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-5">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0 mt-1 shadow-inner">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900 mb-2">Transparent Allocation</h4>
                                <p class="text-gray-600">See exactly which stalls are available on the live map. The system ensures fair, first-come-first-serve allocation for all traders.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-5">
                            <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0 mt-1 shadow-inner">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900 mb-2">Accessible Anywhere</h4>
                                <p class="text-gray-600">Manage your business from home, from the market, or while traveling. The portal works perfectly on smartphones, tablets, and computers.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ══ SUPPORT & CONTACT SECTION (HIGH VISIBILITY) ═════════════════════════════════════ -->
    <section class="py-16 relative overflow-hidden bg-green-600 border-y-8 border-yellow-400" style="background: linear-gradient(135deg, var(--nairobi-green) 0%, var(--nairobi-green-dark) 100%);">
        <div class="absolute inset-0 bg-pattern"></div>
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-[2rem] p-8 md:p-14 text-center shadow-2xl reveal">
                <span class="inline-block py-1.5 px-4 rounded-full bg-yellow-400 text-yellow-900 text-sm font-bold tracking-wider mb-6 uppercase shadow-lg">24/7 Support Desk</span>
                <h2 class="text-3xl md:text-5xl font-black text-white mb-6 leading-tight">Need help booking a stall?</h2>
                <p class="text-white/90 text-lg md:text-xl mb-12 max-w-2xl mx-auto">Contact support via Call, SMS, or WhatsApp. Our dedicated Nairobi City County team is ready to assist you.</p>

                <div class="flex flex-col md:flex-row justify-center items-center gap-6">
                    <!-- Contact 1 -->
                    <a href="tel:0710618973" class="group flex items-center gap-5 bg-white p-5 pr-8 rounded-2xl shadow-[0_15px_30px_rgba(0,0,0,0.2)] hover:-translate-y-2 transition-all duration-300 w-full md:w-auto border border-transparent hover:border-yellow-400">
                        <div class="w-14 h-14 bg-green-100 text-green-600 rounded-xl flex items-center justify-center group-hover:bg-green-600 group-hover:text-white transition-colors">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <div class="text-left">
                            <div class="text-xs text-gray-500 font-bold uppercase tracking-widest mb-1">Call / SMS / WhatsApp</div>
                            <div class="text-2xl font-black text-gray-900 tracking-tight">0710 618 973</div>
                        </div>
                    </a>

                    <!-- Contact 2 -->
                    <a href="tel:0748210495" class="group flex items-center gap-5 bg-white p-5 pr-8 rounded-2xl shadow-[0_15px_30px_rgba(0,0,0,0.2)] hover:-translate-y-2 transition-all duration-300 w-full md:w-auto border border-transparent hover:border-yellow-400">
                        <div class="w-14 h-14 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center group-hover:bg-yellow-400 group-hover:text-yellow-900 transition-colors">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <div class="text-left">
                            <div class="text-xs text-gray-500 font-bold uppercase tracking-widest mb-1">Call / SMS / WhatsApp</div>
                            <div class="text-2xl font-black text-gray-900 tracking-tight">0748 210 495</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ══ FAQ SECTION ═════════════════════════════════════ -->
    <section class="py-24 bg-gray-50 relative">
        <div class="max-w-3xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16 reveal">
                <h2 class="text-sm font-bold text-green-600 tracking-widest uppercase mb-3">Got Questions?</h2>
                <h3 class="text-3xl md:text-5xl font-black text-gray-900 mb-6">Frequently Asked Questions</h3>
            </div>
            
            <div class="space-y-4 reveal">
                <!-- FAQ Item 1 -->
                <div class="faq-card bg-white rounded-2xl border border-gray-200 cursor-pointer transition-colors" onclick="toggleFaq(this)">
                    <div class="p-6 flex justify-between items-center">
                        <h4 class="text-lg font-bold text-gray-900 pr-4">How do I book a stall?</h4>
                        <svg class="w-6 h-6 text-gray-400 faq-icon transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                    <div class="faq-answer text-gray-600 leading-relaxed">
                        To book a stall, you first need to Create an Account. Once logged in, navigate to "Find a Stall", browse the available (green) stalls on the map, select your preferred one, choose your booking duration, and proceed to checkout.
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="faq-card bg-white rounded-2xl border border-gray-200 cursor-pointer transition-colors" onclick="toggleFaq(this)">
                    <div class="p-6 flex justify-between items-center">
                        <h4 class="text-lg font-bold text-gray-900 pr-4">How do I pay?</h4>
                        <svg class="w-6 h-6 text-gray-400 faq-icon transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                    <div class="faq-answer text-gray-600 leading-relaxed">
                        Payment is processed securely via Safaricom M-Pesa. During checkout, an M-Pesa STK push will be sent to your registered phone number. Simply enter your PIN to complete the transaction. The system will automatically verify the payment.
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="faq-card bg-white rounded-2xl border border-gray-200 cursor-pointer transition-colors" onclick="toggleFaq(this)">
                    <div class="p-6 flex justify-between items-center">
                        <h4 class="text-lg font-bold text-gray-900 pr-4">What happens after payment?</h4>
                        <svg class="w-6 h-6 text-gray-400 faq-icon transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                    <div class="faq-answer text-gray-600 leading-relaxed">
                        Once payment is verified, your stall booking is instantly confirmed. You will receive an email receipt and a digital confirmation on your dashboard. The stall status will turn blue (Selected/Occupied by you) on the main map.
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="faq-card bg-white rounded-2xl border border-gray-200 cursor-pointer transition-colors" onclick="toggleFaq(this)">
                    <div class="p-6 flex justify-between items-center">
                        <h4 class="text-lg font-bold text-gray-900 pr-4">How do I report issues?</h4>
                        <svg class="w-6 h-6 text-gray-400 faq-icon transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                    <div class="faq-answer text-gray-600 leading-relaxed">
                        If you encounter any issues with the platform, payment, or your physical stall, you can use the support contacts provided above (Call/SMS/WhatsApp) or submit a ticket through the Feedback section in your trader dashboard.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══ FOOTER ═════════════════════════════════════ -->
    <footer class="bg-dark-gray text-white pt-20 pb-10 border-t-[6px] border-green-600">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-12 mb-16">
                
                <!-- Branding -->
                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <div class="font-black text-white text-xl leading-none">Nairobi City County</div>
                            <div class="text-[10px] text-yellow-400 font-bold uppercase tracking-widest mt-1">Smart Market Portal</div>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed mb-6">
                        Official digital platform for managing stalls at Muthurwa Market. A secure, transparent, and fast way to empower local traders.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-white font-bold mb-6 tracking-wide uppercase text-sm">Quick Links</h4>
                    <ul class="space-y-4 text-gray-400 text-sm">
                        <li><a href="{{ route('login') }}" class="hover:text-yellow-400 transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Trader Login</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-yellow-400 transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Create Account</a></li>
                        <li><a href="#" class="hover:text-yellow-400 transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> View Market Map</a></li>
                        <li><a href="{{ url('/terms') }}" class="hover:text-yellow-400 transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Terms & Conditions</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-white font-bold mb-6 tracking-wide uppercase text-sm">Contact Us</h4>
                    <ul class="space-y-4 text-gray-400 text-sm">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>City Hall, Wabera Street<br>Nairobi, Kenya</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span>0710 618 973 / 0748 210 495</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span>support@nairobi.go.ke</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 text-center flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-500 font-medium">
                <div>&copy; {{ date('Y') }} Nairobi City County. All Rights Reserved.</div>
                <div class="flex items-center gap-4">
                    <a href="#" class="hover:text-white transition">Privacy Policy</a>
                    <a href="{{ url('/terms') }}" class="hover:text-white transition">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts for animations and interactions -->
    <script>
        // Scroll Reveal Animation Observer
        document.addEventListener('DOMContentLoaded', () => {
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.15
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                        observer.unobserve(entry.target); // Only animate once
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.reveal').forEach(el => {
                observer.observe(el);
            });
        });

        // FAQ Toggle Function
        function toggleFaq(element) {
            const isActive = element.classList.contains('active');
            
            // Close all
            document.querySelectorAll('.faq-card').forEach(card => {
                card.classList.remove('active');
            });

            // Open clicked if it wasn't active
            if (!isActive) {
                element.classList.add('active');
            }
        }
    </script>

    <x-chat-assistant />
</body>
</html>
