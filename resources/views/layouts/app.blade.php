<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ধানমন্ডি ১৫ নং গভঃ ষ্টাফ কোয়ার্টার্স প্রাক্তন নিবাসী ফোরাম')</title>

    <link rel="shortcut icon" href="{{ asset('images/logo-square.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-square.png') }}">

    <meta property="og:image" content="{{ asset('images/logo-square.png') }}">
    <meta property="og:title" content="ধানমন্ডি ১৫ নং গভঃ ষ্টাফ কোয়ার্টার্স প্রাক্তন নিবাসী ফোরাম">
    <meta property="og:description" content="সদস্য রেজিস্ট্রেশন ফরম ও স্ট্যাটাস ট্র্যাকিং">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* ===== Base ===== */
        html, body { overflow-x: hidden; font-family: 'Noto Sans Bengali', system-ui, sans-serif; }
        .shadow-soft { box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.08) !important; }
        .required-asterisk { color: #dc3545; }
        .form-control:focus { border-color: #0d6efd; box-shadow: 0 0 0 .2rem rgba(13,110,253,.15); }

        /* ===== Public header ===== */
        .site-header { background: #f5efe1; padding: 1rem 0 1.25rem; border-bottom: 1px solid #e8e0ce; }
        .header-banner-wrap { text-align: center; margin-bottom: .75rem; }
        .header-banner { display: block; width: 100%; max-width: 900px; margin: 0 auto; height: auto; border-radius: 12px; }
        .header-contacts { background: #fff; border: 1px solid #e8e0ce; border-radius: 10px; padding: .6rem .9rem; color: #4b5563; font-size: .88rem; }

        /* ============================================================
         | Admin Navbar
         | ============================================================ */
        .admin-navbar { background: #1f2937; color: #fff; padding: .65rem 0; }
        .admin-navbar .navbar-inner { display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
        .admin-navbar .navbar-brand { color: #fff; font-weight: 700; letter-spacing: .5px; display: flex; align-items: center; gap: .5rem; text-decoration: none; flex-shrink: 0; }
        .admin-navbar .brand-logo { height: 32px; width: 32px; object-fit: contain; border-radius: 6px; background: #fff; padding: 2px; }
        .admin-navbar .nav-links { display: flex; flex-wrap: wrap; align-items: center; gap: .25rem; }
        .admin-navbar a.nav-link { color: #d1d5db; text-decoration: none; padding: .35rem .75rem; border-radius: 6px; font-size: .92rem; position: relative; white-space: nowrap; }
        .admin-navbar a.nav-link:hover { background: #374151; color: #fff; }
        .admin-navbar a.nav-link.active { background: #0d6efd; color: #fff; }
        .admin-navbar .badge-pending { background: #dc3545; color: #fff; font-size: .7rem; padding: .25em .5em; margin-left: .35rem; border-radius: 999px; }
        .admin-navbar .user-name { color: #d1d5db; font-size: .85rem; margin-left: .5rem; }
        .admin-navbar .logout-btn { border: 1px solid #d1d5db; color: #fff; background: transparent; border-radius: 6px; padding: .25rem .65rem; font-size: .85rem; margin-left: .5rem; }
        .admin-navbar .logout-btn:hover { background: #374151; color: #fff; }

        .admin-navbar .hamburger {
            display: none;
            background: transparent;
            border: 1px solid #4b5563;
            color: #fff;
            border-radius: 6px;
            padding: .35rem .7rem;
            font-size: 1.2rem;
            line-height: 1;
            cursor: pointer;
        }
        .admin-navbar .hamburger:hover { background: #374151; }

        /* ============================================================
         | Mobile overlay + drawer
         | ============================================================ */
        .mobile-menu-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .55);
            z-index: 1040;
            opacity: 0;
            transition: opacity .25s ease;
        }
        .mobile-menu-overlay.open { display: block; opacity: 1; }

        .mobile-menu-drawer {
            position: fixed;
            top: 0;
            right: 0;
            height: 100%;
            width: 82%;
            max-width: 340px;
            background: #111827;
            color: #fff;
            z-index: 1050;
            transform: translateX(100%);
            transition: transform .3s cubic-bezier(.4, 0, .2, 1);
            display: flex;
            flex-direction: column;
            box-shadow: -8px 0 32px rgba(0, 0, 0, .35);
            overflow-y: auto;
        }
        .mobile-menu-drawer.open { transform: translateX(0); }

        .mobile-menu-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #374151;
            background: #0f172a;
            position: sticky;
            top: 0;
            z-index: 2;
        }
        .mobile-menu-header .brand { display: flex; align-items: center; gap: .65rem; color: #fff; text-decoration: none; font-weight: 700; font-size: 1rem; }
        .mobile-menu-header .brand img { width: 30px; height: 30px; border-radius: 6px; background: #fff; padding: 2px; object-fit: contain; }
        .mobile-menu-close {
            background: transparent;
            border: 1px solid #4b5563;
            color: #fff;
            border-radius: 6px;
            padding: .3rem .6rem;
            font-size: 1rem;
            line-height: 1;
            cursor: pointer;
            transition: .15s ease;
        }
        .mobile-menu-close:hover { background: #dc3545; border-color: #dc3545; color: #fff; }

        .mobile-menu-links { display: flex; flex-direction: column; flex-grow: 1; padding: .5rem 0; }
        .mobile-menu-links a.nav-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .9rem 1.25rem;
            color: #d1d5db;
            text-decoration: none;
            font-size: 1rem;
            border-bottom: 1px solid #1f2937;
            border-radius: 0;
            transition: .15s ease;
        }
        .mobile-menu-links a.nav-link i { color: #6b7280; font-size: .9rem; margin-right: .75rem; width: 18px; text-align: center; }
        .mobile-menu-links a.nav-link:hover { background: #1f2937; color: #fff; }
        .mobile-menu-links a.nav-link.active { background: #0d6efd; color: #fff; }
        .mobile-menu-links a.nav-link.active i { color: #fff; }

        .mobile-menu-user {
            padding: 1rem 1.25rem;
            background: #0f172a;
            border-top: 1px solid #374151;
            border-bottom: 1px solid #374151;
            font-size: .88rem;
            color: #9ca3af;
        }
        .mobile-menu-user .user-line { display: flex; align-items: center; gap: .5rem; color: #fff; font-weight: 600; margin-bottom: .35rem; }
        .mobile-menu-user .role-badge { background: #0d6efd; color: #fff; font-size: .7rem; padding: .15em .55em; border-radius: 999px; text-transform: capitalize; }

        .mobile-menu-footer { padding: 1rem 1.25rem; margin-top: auto; border-top: 1px solid #1f2937; }
        .mobile-menu-footer .logout-btn {
            width: 100%;
            padding: .7rem;
            background: transparent;
            border: 1px solid #dc3545;
            color: #dc3545;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: .15s ease;
            font-size: .95rem;
        }
        .mobile-menu-footer .logout-btn:hover { background: #dc3545; color: #fff; }

        body.menu-open { overflow: hidden; }

        @media (max-width: 991.98px) {
            .admin-navbar .nav-links { display: none; }
            .admin-navbar .hamburger { display: inline-block; }
        }
        @media (min-width: 992px) {
            .mobile-menu-overlay,
            .mobile-menu-drawer { display: none !important; }
        }

        /* ============================================================
         | Photo preview
         | ============================================================ */
        #preview { max-width: 200px; max-height: 200px; margin-bottom: 10px; cursor: pointer; border-radius: 8px; object-fit: cover; }
        #avatar { display: none; }

        /* ===== Registration hero ===== */
        .reg-hero { background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%); color: #fff; padding: 2.5rem 1rem 3rem; border-radius: 18px; margin-top: 2rem; margin-bottom: -2rem; position: relative; overflow: hidden; }
        .reg-hero::after { content: ""; position: absolute; bottom: -60px; right: -60px; width: 220px; height: 220px; background: rgba(255,255,255,.08); border-radius: 50%; }
        .reg-hero h1 { font-weight: 700; font-size: 1.75rem; margin-bottom: .35rem; }
        .reg-hero p { opacity: .9; margin: 0; font-size: .95rem; }

        /* ===== Form cards ===== */
        .form-card { background: #fff; border-radius: 16px; box-shadow: 0 6px 24px rgba(15, 23, 42, .06); border: 1px solid #eef1f5; margin-bottom: 1.25rem; overflow: hidden; }
        .form-card-header { display: flex; align-items: center; gap: .75rem; padding: 1rem 1.25rem; background: #f8fafc; border-bottom: 1px solid #eef1f5; }
        .step-badge { flex: 0 0 32px; width: 32px; height: 32px; border-radius: 50%; background: #0d6efd; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .9rem; }
        .form-card-header h5 { margin: 0; font-size: 1rem; font-weight: 600; }
        .form-card-header small { display: block; color: #6b7280; font-weight: 400; font-size: .82rem; }
        .form-card-body { padding: 1.25rem; }

        .field-label { font-weight: 600; font-size: .92rem; margin-bottom: .35rem; color: #1f2937; }
        .field-label .bn { color: #6b7280; font-weight: 500; font-size: .82rem; }
        .field-hint { font-size: .78rem; color: #94a3b8; margin-top: .25rem; }
        .form-control, .form-select { border-radius: 10px; padding: .65rem .85rem; border-color: #e5e7eb; font-size: .95rem; }

        .photo-box { border: 2px dashed #cbd5e1; border-radius: 14px; padding: 1.25rem; text-align: center; cursor: pointer; transition: .2s; background: #fafbfc; }
        .photo-box:hover { border-color: #0d6efd; background: #f0f6ff; }
        .photo-box img#preview { width: 140px; height: 140px; border-radius: 50%; object-fit: cover; border: 4px solid #fff; box-shadow: 0 4px 12px rgba(0,0,0,.1); margin-bottom: .65rem; }
        .photo-box .cta { color: #0d6efd; font-weight: 600; font-size: .9rem; }
        .photo-box .meta { color: #94a3b8; font-size: .78rem; margin-top: .25rem; }

        .submit-bar { background: #fff; border-radius: 14px; box-shadow: 0 -4px 20px rgba(0,0,0,.04); padding: 1rem 1.25rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: .75rem; border: 1px solid #eef1f5; }
        .btn-submit { background: linear-gradient(135deg, #0d6efd, #6610f2); border: 0; border-radius: 10px; padding: .75rem 2rem; color: #fff; font-weight: 600; font-size: 1rem; transition: .2s; }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(13,110,253,.25); color: #fff; }

        .pay-guide { background: #fff8e6; border: 1px solid #fde68a; border-radius: 12px; padding: 1rem; }
        .pay-guide .bkash-amount { font-size: 1.35rem; font-weight: 700; color: #0d6efd; }

        /* ===== bKash guide image ===== */
        .pay-guide-img {
            display: block; margin: 0 auto;
            max-width: 100%; max-height: 720px;
            width: 100%; height: auto; object-fit: contain;
            border-radius: 12px; border: 1px solid #e5e7eb;
            background: #fff; padding: 8px; cursor: zoom-in;
            transition: transform .2s ease, box-shadow .2s ease;
            box-shadow: 0 3px 12px rgba(0,0,0,.10);
        }
        .pay-guide-img:hover { transform: scale(1.01); box-shadow: 0 8px 24px rgba(0,0,0,.18); }
        .pay-guide-hint { font-size: .85rem; color: #6b7280; margin-top: .75rem; }
        .pay-guide-hint i { color: #0d6efd; }

        /* ===== Payment method selector ===== */
        .payment-methods { display: grid; grid-template-columns: repeat(3, 1fr); gap: .75rem; }
        .payment-methods.two-methods { grid-template-columns: repeat(2, 1fr); }

        @media (max-width: 575.98px) {
            .payment-methods,
            .payment-methods.two-methods { grid-template-columns: 1fr; }
        }

        .payment-method-card {
            display: flex; align-items: center; gap: .75rem;
            padding: .9rem 1rem;
            border: 2px solid #e5e7eb; border-radius: 12px;
            background: #fff; cursor: pointer;
            transition: all .15s ease; position: relative;
        }
        .payment-method-card:hover { border-color: #c7d2fe; background: #f8faff; }
        .payment-method-card.active { border-color: #0d6efd; background: #eff6ff; box-shadow: 0 2px 8px rgba(13,110,253,.12); }

        .pm-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; color: #fff; flex-shrink: 0; }
        .pm-bkash { background: #E2136E; }
        .pm-bank  { background: #0d6efd; }
        .pm-cash  { background: #198754; }

        .pm-body { flex-grow: 1; }
        .pm-title { font-weight: 700; font-size: .92rem; color: #1f2937; }
        .pm-sub { font-size: .75rem; color: #6b7280; }
        .pm-check { font-size: 1.1rem; color: #cbd5e1; transition: color .15s ease; }
        .payment-method-card.active .pm-check { color: #0d6efd; }

        .payment-panel { display: none; padding: .25rem 0; }
        .payment-panel.active { display: block; animation: fadeIn .2s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: translateY(0); } }

        /* Bank option cards */
        .bank-options { display: flex; flex-direction: column; gap: .65rem; }
        .bank-option { cursor: pointer; margin: 0; }
        .bank-option input[type="radio"] { display: none; }
        .bank-card { border: 2px solid #e5e7eb; border-radius: 12px; padding: 1rem 1.15rem; background: #fff; transition: all .15s ease; }
        .bank-option input:checked + .bank-card { border-color: #0d6efd; background: #eff6ff; box-shadow: 0 2px 8px rgba(13,110,253,.12); }
        .bank-name { font-weight: 700; font-size: .95rem; color: #1f2937; margin-bottom: .5rem; }
        .bank-name i { color: #0d6efd; }
        .bank-detail { font-size: .82rem; color: #4b5563; line-height: 1.6; }
        .bank-detail strong { color: #1f2937; }
        .bank-instructions { margin-top: .5rem; padding-top: .5rem; border-top: 1px dashed #e5e7eb; }

        /* ===== Admin table thumbnails ===== */
        .table img.rounded.border { transition: transform .15s ease, box-shadow .15s ease; }
        .table img.rounded.border:hover { transform: scale(2.2); position: relative; z-index: 5; box-shadow: 0 4px 15px rgba(0,0,0,.25); }

        /* ===== Circular action buttons ===== */
        .action-btns { display: inline-flex; gap: .35rem; align-items: center; justify-content: flex-end; }
        .action-btn {
            display: inline-flex; align-items: center; justify-content: center;
            width: 34px; height: 34px; border-radius: 50%;
            border: 1.5px solid transparent; background: #fff;
            font-size: .85rem; line-height: 1; padding: 0;
            cursor: pointer; transition: all .18s ease; position: relative;
            text-decoration: none; box-shadow: 0 1px 3px rgba(0,0,0,.06);
        }
        .action-btn i { font-size: .95rem; transition: transform .18s ease; }
        .action-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,.15); }
        .action-btn:hover i { transform: scale(1.15); }
        .action-btn:active { transform: translateY(0); box-shadow: 0 1px 3px rgba(0,0,0,.1); }

        .action-btn.view { color: #0d6efd; border-color: #cfe2ff; background: #f0f6ff; }
        .action-btn.view:hover { background: #0d6efd; color: #fff; border-color: #0d6efd; }
        .action-btn.edit { color: #6c757d; border-color: #e2e6ea; background: #f8f9fa; }
        .action-btn.edit:hover { background: #6c757d; color: #fff; border-color: #6c757d; }
        .action-btn.delete { color: #dc3545; border-color: #f5c2c7; background: #fdf0f2; }
        .action-btn.delete:hover { background: #dc3545; color: #fff; border-color: #dc3545; }
        .action-btn.verify { color: #198754; border-color: #badbcc; background: #e8f5ee; }
        .action-btn.verify:hover { background: #198754; color: #fff; border-color: #198754; }

        /* ============================================================
         | Filter bar — search always visible, advanced collapsible
         | ============================================================ */
        .filter-card {
            background: #fff;
            border: 1px solid #eef1f5;
            border-radius: 12px;
            padding: .85rem 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.04);
        }

        .filter-form { width: 100%; }

        .search-row {
            display: flex;
            align-items: center;
            gap: .5rem;
            flex-wrap: wrap;
        }

        .search-field {
            flex: 1 1 300px;
            min-width: 200px;
            position: relative;
        }

        .search-field input {
            width: 100%;
            height: 42px;
            padding: .5rem .85rem .5rem 2.4rem;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: #fafbfc;
            font-size: .95rem;
            transition: all .15s ease;
            outline: none;
        }

        .search-field input:focus {
            border-color: #0d6efd;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(13,110,253,.08);
        }

        .search-field input::placeholder { color: #94a3b8; }

        .search-field i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: .9rem;
            pointer-events: none;
        }

        .search-submit-btn {
            height: 42px;
            padding: 0 1.25rem;
            flex: 0 0 auto;
        }

        .filter-btn-toggle {
            height: 42px;
            padding: 0 1rem;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: #f8f9fa;
            color: #374151;
            font-weight: 600;
            font-size: .9rem;
            cursor: pointer;
            transition: all .15s ease;
            align-items: center;
            gap: .35rem;
            flex: 0 0 auto;
            display: inline-flex;
        }

        .filter-btn-toggle:hover {
            background: #eff6ff;
            color: #0d6efd;
            border-color: #c7d2fe;
        }

        .advanced-filters {
            display: block;
            margin-top: .85rem;
            padding-top: .85rem;
            border-top: 1px dashed #eef1f5;
        }

        .filter-row { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; }

        .filter-field { position: relative; flex: 1 1 140px; min-width: 120px; }

        .filter-field i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: .78rem;
            pointer-events: none;
            z-index: 1;
        }

        .filter-field input,
        .filter-field select {
            width: 100%;
            height: 38px;
            padding: .35rem .65rem .35rem 2rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #fafbfc;
            font-size: .85rem;
            color: #1f2937;
            transition: all .15s ease;
            outline: none;
        }

        .filter-field input:focus,
        .filter-field select:focus {
            border-color: #0d6efd;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(13,110,253,.08);
        }

        .filter-field input::placeholder { color: #94a3b8; font-size: .82rem; }

        .filter-field select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2394a3b8' d='M6 9L1.5 4.5h9L6 9z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 8px center;
            padding-right: 1.8rem;
            cursor: pointer;
        }

        .filter-select { flex: 0 1 145px; }

        .filter-btn {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            height: 38px;
            padding: 0 1rem;
            border-radius: 8px;
            border: none;
            font-size: .85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all .15s ease;
            white-space: nowrap;
            text-decoration: none;
        }

        .filter-btn-primary {
            background: linear-gradient(135deg, #0d6efd, #3d7cff);
            color: #fff;
            box-shadow: 0 2px 6px rgba(13,110,253,.25);
        }
        .filter-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(13,110,253,.35);
            color: #fff;
        }

        .filter-btn-reset {
            background: #f8f9fa;
            color: #6c757d;
            border: 1px solid #e5e7eb;
        }
        .filter-btn-reset:hover {
            background: #fff;
            color: #dc3545;
            border-color: #f5c2c7;
        }

        .filter-chips {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: .35rem;
            margin-top: .6rem;
            padding-top: .6rem;
            border-top: 1px dashed #eef1f5;
        }

        .filter-chip {
            display: inline-block;
            padding: .15rem .5rem;
            border-radius: 999px;
            background: #eef4ff;
            color: #0d6efd;
            font-size: .72rem;
            font-weight: 600;
            border: 1px solid #d6e4ff;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (min-width: 992px) {
            .filter-btn-toggle { display: none !important; }
        }

        @media (max-width: 991.98px) {
            .search-row {
                flex-direction: column;
                align-items: stretch;
            }
            .search-submit-btn { width: 100%; justify-content: center; }
            .filter-btn-toggle { width: 100%; justify-content: center; }

            .advanced-filters { display: none; }
            .advanced-filters.open {
                display: block;
                animation: slideDown .25s ease;
            }

            .filter-field { flex: 1 1 calc(50% - .5rem); min-width: 0; }
            .filter-field.filter-select { flex: 1 1 calc(50% - .5rem); }
            .filter-btn { flex: 1 1 auto; justify-content: center; }
            .filter-btn-reset { flex: 0 0 auto; }
        }

        /* ===== Responsive ===== */
        @media (max-width: 767.98px) {
            .site-header { padding: .75rem 0; }
            .header-banner { border-radius: 8px; }
            .header-contacts { font-size: .8rem; padding: .5rem .65rem; }
            .reg-hero { padding: 1.5rem 1rem 2.5rem; border-radius: 12px; margin-top: 1rem; }
            .reg-hero h1 { font-size: 1.35rem; }
            .form-card-body { padding: 1rem; }
            .btn-submit { width: 100%; }
            #preview { max-width: 150px !important; }
            .pay-guide-img { max-width: 100%; max-height: 520px; padding: 6px; }
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- ADMIN NAVBAR --}}
@auth
    @if(request()->routeIs('admin.*'))
        @php
            $pendingCount = \App\Models\Registration::where('payment_status', 'pending')->count();
        @endphp

        <nav class="admin-navbar">
            <div class="container">
                <div class="navbar-inner">

                    <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                        <img src="{{ asset('images/logo-square.png') }}"
                             alt="D15" class="brand-logo"
                             onerror="this.style.display='none'">
                        <span>D15 Admin</span>
                    </a>

                    <div class="nav-links">
                        <a href="{{ route('admin.registrations.pending') }}"
                           class="nav-link {{ request()->routeIs('admin.registrations.pending') ? 'active' : '' }}">
                            Pending
                            @if($pendingCount)
                                <span class="badge-pending">{{ $pendingCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.registrations') }}"
                           class="nav-link {{ request()->routeIs('admin.registrations') && !request()->routeIs('admin.registrations.pending') ? 'active' : '' }}">All</a>
                        <a href="{{ route('admin.sms.index') }}"
                           class="nav-link {{ request()->routeIs('admin.sms.index') || request()->routeIs('admin.sms.test') ? 'active' : '' }}">SMS</a>
                        <a href="{{ route('admin.sms.templates.index') }}"
                           class="nav-link {{ request()->routeIs('admin.sms.templates.*') ? 'active' : '' }}">Templates</a>
                        @if(auth()->user()->role !== 'moderator')
                            <a href="{{ route('admin.banks.index') }}" class="nav-link {{ request()->routeIs('admin.banks.*') ? 'active' : '' }}">Banks</a>
                            <a href="{{ route('admin.bkash.index') }}" class="nav-link {{ request()->routeIs('admin.bkash.*') ? 'active' : '' }}">bKash</a>
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Users</a>
                            <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">Settings</a>
                        @endif
                        <span class="user-name d-none d-xl-inline">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button class="logout-btn">Logout</button>
                        </form>
                    </div>

                    <button class="hamburger" type="button" onclick="openMobileMenu()" aria-label="Open menu">
                        <i class="fa fa-bars"></i>
                    </button>
                </div>
            </div>
        </nav>

        {{-- MOBILE OVERLAY + DRAWER --}}
        <div class="mobile-menu-overlay" id="mobileMenuOverlay" onclick="closeMobileMenu()"></div>

        <div class="mobile-menu-drawer" id="mobileMenuDrawer">
            <div class="mobile-menu-header">
                <a href="{{ route('admin.dashboard') }}" class="brand">
                    <img src="{{ asset('images/logo-square.png') }}" alt="D15" onerror="this.style.display='none'">
                    <span>D15 Admin</span>
                </a>
                <button class="mobile-menu-close" onclick="closeMobileMenu()" aria-label="Close menu">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <div class="mobile-menu-links">
                <a href="{{ route('admin.registrations.pending') }}"
                   class="nav-link {{ request()->routeIs('admin.registrations.pending') ? 'active' : '' }}">
                    <span><i class="fa fa-hourglass-half"></i> Pending</span>
                    @if($pendingCount)<span class="badge-pending">{{ $pendingCount }}</span>@endif
                </a>
                <a href="{{ route('admin.registrations') }}"
                   class="nav-link {{ request()->routeIs('admin.registrations') && !request()->routeIs('admin.registrations.pending') ? 'active' : '' }}">
                    <span><i class="fa fa-list"></i> All Registrations</span>
                    <i class="fa fa-chevron-right"></i>
                </a>
                <a href="{{ route('admin.sms.index') }}"
                   class="nav-link {{ request()->routeIs('admin.sms.index') || request()->routeIs('admin.sms.test') ? 'active' : '' }}">
                    <span><i class="fa fa-comment"></i> SMS Logs</span>
                    <i class="fa fa-chevron-right"></i>
                </a>
                <a href="{{ route('admin.sms.templates.index') }}"
                   class="nav-link {{ request()->routeIs('admin.sms.templates.*') ? 'active' : '' }}">
                    <span><i class="fa fa-file-text-o"></i> SMS Templates</span>
                    <i class="fa fa-chevron-right"></i>
                </a>
                @if(auth()->user()->role !== 'moderator')
                    <a href="{{ route('admin.banks.index') }}"
                       class="nav-link {{ request()->routeIs('admin.banks.*') ? 'active' : '' }}">
                        <span><i class="fa fa-university"></i> Banks</span>
                        <i class="fa fa-chevron-right"></i>
                    </a>
                    <a href="{{ route('admin.bkash.index') }}"
                       class="nav-link {{ request()->routeIs('admin.bkash.*') ? 'active' : '' }}">
                        <span><i class="fa fa-credit-card"></i> bKash Settings</span>
                        <i class="fa fa-chevron-right"></i>
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                       class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <span><i class="fa fa-users"></i> Users</span>
                        <i class="fa fa-chevron-right"></i>
                    </a>
                    <a href="{{ route('admin.settings.index') }}"
                       class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <span><i class="fa fa-cog"></i> Settings</span>
                        <i class="fa fa-chevron-right"></i>
                    </a>
                @endif
            </div>

            <div class="mobile-menu-user">
                <div class="user-line">
                    <i class="fa fa-user-circle"></i>
                    <span>{{ auth()->user()->name }}</span>
                    <span class="role-badge">{{ auth()->user()->role }}</span>
                </div>
                <div>{{ auth()->user()->email }}</div>
            </div>

            <div class="mobile-menu-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="logout-btn"><i class="fa fa-sign-out"></i> Logout</button>
                </form>
            </div>
        </div>
    @endif
@endauth

{{-- PUBLIC HEADER --}}
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
                        <i class="fa fa-phone text-primary"></i> ০১৭২১৩০৮২১৯, ০১৭১২৩৭০১৭২
                        &nbsp;|&nbsp;
                        <i class="fa fa-envelope text-primary"></i> 15nocolony1966@gmail.com
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

@yield('content')

@if(!request()->routeIs('admin.*'))
    <footer class="py-4 text-center text-muted small">
        <div class="container">
            &copy; {{ date('Y') }} ধানমন্ডি ১৫ নং গভঃ ষ্টাফ কোয়ার্টার্স প্রাক্তন নিবাসী ফোরাম
        </div>
    </footer>
@endif

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function openMobileMenu() {
        const overlay = document.getElementById('mobileMenuOverlay');
        const drawer  = document.getElementById('mobileMenuDrawer');
        if (!overlay || !drawer) return;
        overlay.classList.add('open');
        drawer.classList.add('open');
        document.body.classList.add('menu-open');
    }

    function closeMobileMenu() {
        const overlay = document.getElementById('mobileMenuOverlay');
        const drawer  = document.getElementById('mobileMenuDrawer');
        if (!overlay || !drawer) return;
        overlay.classList.remove('open');
        drawer.classList.remove('open');
        document.body.classList.remove('menu-open');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const drawer = document.getElementById('mobileMenuDrawer');
        if (drawer) {
            drawer.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', closeMobileMenu);
            });
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeMobileMenu();
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth >= 992) closeMobileMenu();
        });

        document.querySelectorAll('.action-btn[title]').forEach(el => {
            new bootstrap.Tooltip(el, { delay: { show: 400, hide: 100 } });
        });
    });
</script>
@stack('scripts')
</body>
</html>