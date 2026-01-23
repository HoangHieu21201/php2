<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản trị Hệ thống - Master Layout</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- CSS CỦA BẠN NẰM Ở ĐÂY -->
    <style>
        :root {
            --primary-color: #009981;
            --primary-hover: #007a67;
            --sidebar-width: 250px;
            --header-height: 60px;
            --dark-sidebar-bg: #1e293b;
        }

        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
            overflow-x: hidden;
        }

        /* --- 1. SIDEBAR STYLES --- */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--dark-sidebar-bg);
            color: #fff;
            transition: all 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-brand {
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: bold;
            color: var(--primary-color);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-decoration: none;
        }

        .sidebar-menu {
            padding: 1rem 0;
            list-style: none;
            margin: 0;
        }

        .sidebar-menu .nav-item {
            width: 100%;
            margin-bottom: 5px;
        }

        .sidebar-menu .nav-link {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-menu .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .sidebar-menu .nav-link.active {
            background-color: rgba(0, 153, 129, 0.15);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
            font-weight: 600;
        }

        .sidebar-menu .nav-link i {
            font-size: 1.2rem;
            width: 30px;
            text-align: center;
            margin-right: 10px;
        }

        /* --- 2. MAIN CONTENT WRAPPER --- */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* --- 3. TOP HEADER STYLES --- */
        .top-header {
            height: var(--header-height);
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        /* --- RESPONSIVE MOBILE --- */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 998;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }
    </style>
</head>

<body>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    @include('layout.components.admin.sidebar')

    
    <div class="main-wrapper">

        @include('layout.components.admin.header')

        <main class="p-4">
            @yield('content')
        </main>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', toggleSidebar);
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', toggleSidebar);
        }
    </script>
</body>
</html>