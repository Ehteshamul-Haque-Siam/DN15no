<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ধানমন্ডি ১৫ নং গভঃ ষ্টাফ কোয়ার্টার্স প্রাক্তন নিবাসী ফোরাম')</title>

    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('images/logo-square.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-square.png') }}">

    {{-- OpenGraph --}}
    <meta property="og:image" content="{{ asset('images/logo-square.png') }}">
    <meta property="og:title" content="ধানমন্ডি ১৫ নং গভঃ ষ্টাফ কোয়ার্টার্স প্রাক্তন নিবাসী ফোরাম">
    <meta property="og:description" content="সদস্য রেজিস্ট্রেশন ফরম ও স্ট্যাটাস ট্র্যাকিং">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    {{-- CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* ===== Base ===== */
        html, body {
            overflow-x: hidden;
            font-family: 'Noto Sans Bengali', system-ui, sans-serif;
        }
        .shadow-soft { box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.08) !important; }
        .required-asterisk { color: #dc3545; }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 .2rem rgba(13,110,253,.15);
        }
        .badge-status { font-size: .8rem; padding: .45em .7em; }

        /* ===== Public header (banner) ===== */
        .site-header {
            background: #f5efe1;
            padding: 1rem 0 1.25rem;
            border-bottom: 1px solid #e8e0ce;
        }
        .header-banner-wrap {
            text-align: center;
            margin-bottom: .75rem;
        }
        .header-banner {
            display: block;
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            height: auto;
            border-radius: 12px;
        }
        .header-contacts {
            background: #fff;
            border: 1px solid #e8e0ce;
            border-radius: 10px;
            padding: .6rem .9rem;
            color: #4b5563;
            font-size: .88rem;
        }

        /* ===== Admin navbar ===== */
        .admin-navbar {
            background: #1f2937;
            color: #fff;
            padding: .65rem 0;
        }
        .admin-navbar .navbar-brand {
            color: #fff;
            font-weight: 700;
            letter-spacing: .5px;
            display: flex;
            align-items: center;
            gap: .5rem;
            text-decoration: none;
        }
        .admin-navbar .brand-logo {
            height: 32px;
            width: 32px;
            object-fit: contain;
            border-radius: 6px;
            background: #fff;
            padding: 2px;
        }
        .admin-navbar a.nav-link {
            color: #d1d5db;
            text-decoration: none;
            padding: .35rem .75rem;
            border-radius: 6px;
            font-size: .92rem;
        }
        .admin-navbar a.nav-link:hover {
            background: #374151;
            color: #fff;
        }
        .admin-navbar a.nav-link.active {
            background: #0d6efd;
            color: #fff;
        }
        .admin-navbar .badge-pending {
            background: #dc3545;
            font-size: .7rem;
            padding: .25em .5em;
            margin-left: .25rem;
        }

        /* ===== Photo preview ===== */
        #preview {
            max-width: 200px;
            max-height: 200px;
            margin-bottom: 10px;
            cursor: pointer;
            border-radius: 8px;
            object-fit: cover;
        }
        #avatar { display: none; }

        /* ===== Registration hero ===== */
        .reg-hero {
            background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
            color: #fff;
            padding: 2.5rem 1rem 3rem;
            border-radius: 18px;
            margin-bottom: -2rem;
            position: relative;
            overflow: hidden;
        }
        .reg-hero::after {
            content: "";
            position: absolute;
            bottom: -60px;
            right: -60px;
            width: 220px;
            height: 220px;
            background: rgba(255,255,255,.08);
            border-radius: 50%;
        }
        .reg-hero h1 {
            font-weight: 700;
            font-size: 1.75rem;
            margin-bottom: .35rem;
        }
        .reg-hero p { opacity: .9; margin: 0; font-size: .95rem; }

        /* ===== Form cards ===== */
        .form-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 6px 24px rgba(15, 23, 42, .06);
            border: 1px solid #eef1f5;
            margin-bottom: 1.25rem;
            overflow: hidden;
        }
        .form-card-header {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: 1rem 1.25rem;
            background: #f8fafc;
            border-bottom: 1px solid #eef1f5;
        }
        .step-badge {
            flex: 0 0 32px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #0d6efd;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .9rem;
        }
        .form-card-header h5 { margin: 0; font-size: 1rem; font-weight: 600; }
        .form-card-header small {
            display: block;
            color: #6b7280;
            font-weight: 400;
            font-size: .82rem;
        }
        .form-card-body { padding: 1.25rem; }

        .field-label {
            font-weight: 600;
            font-size: .92rem;
            margin-bottom: .35rem;
            color: #1f2937;
        }
        .field-label .bn {
            color: #6b7280;
            font-weight: 500;
            font-size: .82rem;
        }
        .field-hint {
            font-size: .78rem;
            color: #94a3b8;
            margin-top: .25rem;
        }
        .form-control, .form-select {
            border-radius: 10px;
            padding: .65rem .85rem;
            border-color: #e5e7eb;
            font-size: .95rem;
        }

        /* Photo box */
        .photo-box {
            border: 2px dashed #cbd5e1;
            border-radius: 14px;
            padding: 1.25rem;
            text-align: center;
            cursor: pointer;
            transition: .2s;
            background: #fafbfc;
        }
        .photo-box:hover {
            border-color: #0d6efd;
            background: #f0f6ff;
        }
        .photo-box img#preview {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,.1);
            margin-bottom: .65rem;
        }
        .photo-box .cta {
            color: #0d6efd;
            font-weight: 600;
            font-size: .9rem;
        }
        .photo-box .meta {
            color: #94a3b8;
            font-size: .78rem;
            margin-top: .25rem;
        }

        /* Submit bar */
        .submit-bar {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 -4px 20px rgba(0,0,0,.04);
            padding: 1rem 1.25rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            border: 1px solid #eef1f5;
        }
        .btn-submit {
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            border: 0;
            border-radius: 10px;
            padding: .75rem 2rem;
            color: #fff;
            font-weight: 600;
            font-size: 1rem;
            transition: .2s;
        }
        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(13,110,253,.25);
            color: #fff;
        }

        /* Payment guide */
        .pay-guide {
            background: #fff8e6;
            border: 1px solid #fde68a;
            border-radius: 12px;
            padding: 1rem;
        }
        .pay-guide .bkash-amount {
            font-size: 1.35rem;
            font-weight: 700;
            color: #0d6efd;
        }

        /* bKash guide images — capped size */
        .pay-guide-img {
            display: block;
            margin: 0 auto;
            max-width: 140px;
            max-height: 220px;
            width: 100%;
            height: auto;
            object-fit: contain;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: #fff;
            padding: 4px;
        }

        /* Responsive */
        @media (max-width: 767.98px) {
            .site-header { padding: .75rem 0; }
            .header-banner { border-radius: 8px; }
            .header-contacts { font-size: .8rem; padding: .5rem .65rem; }
            .reg-hero { padding: 1.5rem 1rem 2.5rem; border-radius: 12px; }
            .reg-hero h1 { font-size: 1.35rem; }
            .form-card-body { padding: 1rem; }
            .btn-submit { width: 100%; }
            #preview { max-width: 150px !important; }
            .pay-guide-img { max-width: 110px; max-height: 180px; }
        }
        @media (max-width: 575.98px) {
            .card-body { padding: 1rem; }
            .form-label { font-size: .9rem; }
            .btn-lg-mobile { font-size: 18px !important; padding: 10px; }
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- =========================================================
     ADMIN NAVBAR (only on admin routes)
========================================================= --}}
@auth
    @if(request()->routeIs('admin.*'))
        @php
            $pendingCount = \App\Models\Registration::where('payment_status', 'pending')
                                ->whereNotNull('mfs_trn')
                                ->count();
        @endphp
        <nav class="admin-navbar">
            <div class="container d-flex flex-wrap align-items-center justify-content-between gap-2">

                <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('images/logo-square.png') }}"
                         alt="D15"
                         class="brand-logo"
                         onerror="this.style.display='none'">
                    <span>D15 Admin</span>
                </a>

                <div class="d-flex flex-wrap align-items-center gap-1">
                    <a href="{{ route('admin.registrations') }}"
                       class="nav-link {{ request()->routeIs('admin.registrations') && !request('payment') ? 'active' : '' }}">
                        Registrations
                    </a>

                    <a href="{{ route('admin.registrations', ['payment' => 'pending']) }}"
                       class="nav-link {{ request('payment') === 'pending' ? 'active' : '' }}">
                        Pending
                        @if($pendingCount)
                            <span class="badge badge-pending">{{ $pendingCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.sms.index') }}"
                       class="nav-link {{ request()->routeIs('admin.sms.*') ? 'active' : '' }}">
                        SMS
                    </a>

                    @if(auth()->user()->role !== 'moderator')
                        <a href="{{ route('admin.bkash.index') }}"
                           class="nav-link {{ request()->routeIs('admin.bkash.*') ? 'active' : '' }}">
                            bKash
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            Users
                        </a>
                        <a href="{{ route('admin.settings.index') }}"
                           class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                            Settings
                        </a>
                    @endif

                    <span class="text-light small d-none d-md-inline ms-2">
                        {{ auth()->user()->name }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}" class="d-inline ms-2">
                        @csrf
                        <button class="btn btn-sm btn-outline-light">Logout</button>
                    </form>
                </div>
            </div>
        </nav>
    @endif
@endauth

{{-- =========================================================
     PUBLIC HEADER (banner) — not on admin routes
========================================================= --}}
@if(!request()->routeIs('admin.*'))
    <header class="site-header">
        <div class="container">
            <div class="header-banner-wrap">
                <a href="{{ route('home') }}" class="d-block">
                    <img src="{{ asset('images/banner-logo.png') }}"
                         alt="ধানমন্ডি ১৫ নং গভঃ ষ্টাফ কোয়ার্টার্স প্রাক্তন নিবাসী ফোরাম"
                         class="header-banner"
                         onerror="this.onerror=null; this.parentNode.innerHTML='<h3 class=\'text-center text-muted py-3\'>ধানমন্ডি ১৫ নং গভঃ ষ্টাফ কোয়ার্টার্স প্রাক্তন নিবাসী ফোরাম</h3>';">
                </a>
            </div>

            <div class="header-contacts">
                <div class="row g-2 text-center text-md-start small">
                    <div class="col-md-6">
                        <i class="fa fa-map-marker text-primary"></i>
                        সরকারী কর্মচারী কল্যাণ কেন্দ্র, সড়ক-৮/এ (পুরাতন-১৫), পশ্চিম ধানমণ্ডি, ঢাকা।
                    </div>
                    <div class="col-md-6 text-md-end">
                        <i class="fa fa-phone text-primary"></i>
                        ০১৭২১৩০৮২১৯, ০১৭১২৩৭০১৭২
                        &nbsp;|&nbsp;
                        <i class="fa fa-envelope text-primary"></i>
                        15nocolony1966@gmail.com
                        &nbsp;|&nbsp;
                        <a href="https://www.facebook.com/groups/948315826473087"
                           target="_blank" class="text-decoration-none">
                            <i class="fa fa-facebook-square text-primary"></i> Facebook
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
@endif

{{-- =========================================================
     PAGE CONTENT
========================================================= --}}
@yield('content')

{{-- =========================================================
     PUBLIC FOOTER
========================================================= --}}
@if(!request()->routeIs('admin.*'))
    <footer class="py-4 text-center text-muted small">
        <div class="container">
            &copy; {{ date('Y') }} ধানমন্ডি ১৫ নং গভঃ ষ্টাফ কোয়ার্টার্স প্রাক্তন নিবাসী ফোরাম
        </div>
    </footer>
@endif

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>