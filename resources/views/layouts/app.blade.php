<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preparation Dashboard | @yield('title')</title>

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            overflow-x: hidden;
            transition: background 0.3s ease, color 0.3s ease;
        }
        .topbar {
            background-color: #f8f9fa;
            padding: 10px 20px;
            border-bottom: 1px solid #dee2e6;
            margin-left: 220px;
            transition: margin-left 0.3s ease;
        }
        .main-content {
            margin-left: 220px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }
        .sidebar {
            width: 220px;
            height: 100vh;
            background-color: #2c3e50;
            position: fixed;
            padding-top: 15px;
            top: 0;
            left: 0;
            transition: transform 0.3s ease;
            z-index: 999;
            overflow-y: auto;
        }
        .sidebar.collapsed .sidebar-title {
            display: none;
            width: 60px;
            transition: width 0.3s ease;
        }
        .sidebar a {
            color: #ecf0f1;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #34495e;
        }
        
        .sidebar.collapsed {
            width: 60px;
        }
        .sidebar.collapsed a {
            position: relative;
        }
        .sidebar.collapsed a span {
            position: fixed;
            left: 60px;
            top: auto;
            transform: translateY(-50%);
            background-color: #2c3e50;
            color: #fff;
            padding: 4px 8px;
            white-space: nowrap;
            border-radius: 4px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
            z-index: 9999;
        }

        .sidebar.collapsed a:hover span {
            opacity: 1;
            pointer-events: auto;
        }


        .sidebar.collapsed ~ .topbar,
        .sidebar.collapsed ~ .main-content {
            margin-left: 60px;
        }
        .sidebar a i {
            font-size: 18px;
            min-width: 20px;
            text-align: center;
        }
        .sidebar a.active i {
            color: #28a745; /* hijau Bootstrap */
        }
        .btn-xs {
            padding: 2px 6px;
            font-size: 15px;
            line-height: 1;
            border-radius: 4px;
        }

        .toggle-btn {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
        }
        .theme-toggle {
            cursor: pointer;
            font-size: 18px;
        }

        [data-theme="dark"] body {
            background-color: #1e1e1e;
            color: #f1f1f1;
        }

        [data-theme="dark"] .topbar {
            background-color: #2c2c2c;
            border-bottom: 1px solid #444;
        }

        [data-theme="dark"] .sidebar {
            background-color: #111;
        }

        [data-theme="dark"] .sidebar a {
            color: #ccc;
        }

        [data-theme="dark"] .sidebar a:hover, 
        [data-theme="dark"] .sidebar a.active {
            background-color: #333;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .topbar, .main-content {
                margin-left: 0 !important;
            }
        }
    </style>
</head>
<body>

    {{-- Sidebar --}}
    <div id="sidebar" class="sidebar">
        <div class="d-flex justify-content-between align-items-center text-white px-3 mb-3">
            <h6 class="mb-0"><span class="sidebar-title">Preparation Menu</span></h5>
            <button onclick="toggleSidebar()" class="btn btn-xs btn-light text-dark">
                <i id="toggle-icon" class="bi bi-chevron-double-left"></i>
            </button>
        </div>

        <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
        </a>
        <a href="{{ route('komponen_produk_jadi.index') }}" class="{{ request()->is('komponen_produk_jadi*') ? 'active' : '' }}">
            <i class="bi bi-cpu"></i> <span>Komponen Produk</span>
        </a>
        <a href="{{ route('produk_jadi.index') }}" class="{{ request()->is('produk_jadi*') ? 'active' : '' }}">
            <i class="bi bi-box"></i> <span>Produk Jadi</span>
        </a>
        <a href="{{ route('hasil_upper.index') }}" class="{{ request()->is('hasil_upper*') ? 'active' : '' }}">
            <i class="bi bi-graph-up"></i> <span>Hasil Upper</span>
        </a>
        <a href="{{ route('distribute.index') }}" class="{{ request()->is('distribute*') ? 'active' : '' }}">
            <i class="bi bi-send"></i> <span>Distribute</span>
        </a>
        <a href="{{ route('users.index') }}" class="{{ request()->is('users*') ? 'active' : '' }}">
            <i class="bi bi-person-lines-fill"></i> <span>Manajemen User</span>
        </a>
        <a href="#" onclick="confirmLogout(event)">
            <i class="bi bi-box-arrow-right"></i> <span>Keluar</span>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    {{-- Topbar --}}
    <div class="topbar d-flex justify-content-between align-items-center" id="topbar">
        <button class="toggle-btn d-md-none" onclick="toggleSidebar()">☰</button>
        <h4 class="mb-0">@yield('title', 'Preparation')</h4>
        <div>
            <strong>{{ auth()->user()->nama ?? 'Guest' }}</strong>
            <span class="badge bg-secondary">{{ auth()->user()->role ?? '' }}</span>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="main-content" id="mainContent">
        @yield('content')
    </div>

{{-- Script --}}
@yield('scripts')

<script>
    const sidebar = document.getElementById('sidebar');
    const toggleIcon = document.getElementById('toggle-icon');

    function toggleSidebar() {
        sidebar.classList.toggle('collapsed');
        sidebar.classList.toggle('show');

        const isCollapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('sidebarCollapsed', isCollapsed ? 'true' : 'false');

        // Ganti icon toggle
        if (toggleIcon) {
            toggleIcon.className = isCollapsed
                ? 'bi bi-chevron-double-right'
                : 'bi bi-chevron-double-left';
        }
    }

    function toggleTheme() {
        const html = document.documentElement;
        const newTheme = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
    }

    // Saat halaman diload
    document.addEventListener('DOMContentLoaded', function () {
        // Sidebar collapsed?
        const collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (collapsed) {
            sidebar.classList.add('collapsed');
            if (toggleIcon) {
                toggleIcon.className = 'bi bi-chevron-double-right';
            }
        }

        // Tema dark/light
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
    });

    function confirmLogout(e) {
        e.preventDefault();
        if (confirm('Yakin ingin logout?')) {
            document.getElementById('logout-form').submit();
        }
    }

</script>

</body>
</html>
