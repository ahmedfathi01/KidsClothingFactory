<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') - بر الليث</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/admin/admin-layout.css') }}">

    @yield('styles')
</head>
<body class="h-100">
    <div class="admin-layout">
        <!-- Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <aside class="sidebar shadow-sm" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="بر الليث" height="40" class="me-2">
                </a>
                <button class="d-lg-none btn btn-close" id="closeSidebar" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <nav class="sidebar-nav">
                <!-- Dashboard Section -->
                <div class="nav-section">
                    <div class="nav-section-title">الرئيسية</div>
                    <div class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span class="nav-title">لوحة التحكم</span>
                        </a>
                    </div>
                </div>

                <!-- Products Section -->
                <div class="nav-section">
                    <div class="nav-section-title">المنتجات</div>
                    <div class="nav-item">
                        <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <i class="fas fa-box"></i>
                            <span class="nav-title">المنتجات</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="fas fa-tags"></i>
                            <span class="nav-title">التصنيفات</span>
                        </a>
                    </div>
                </div>

                <!-- Coupons & Discounts Section -->
                <div class="nav-section">
                    <div class="nav-section-title">الخصومات</div>
                    <div class="nav-item">
                        <a href="{{ route('admin.coupons.index') }}" class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                            <i class="fas fa-ticket-alt"></i>
                            <span class="nav-title">كوبونات الخصم</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.quantity-discounts.index') }}" class="nav-link {{ request()->routeIs('admin.quantity-discounts.*') ? 'active' : '' }}">
                            <i class="fas fa-percent"></i>
                            <span class="nav-title">خصومات الكمية</span>
                        </a>
                    </div>
                </div>

                <!-- Orders Section -->
                <div class="nav-section">
                    <div class="nav-section-title">الطلبات</div>
                    <div class="nav-item">
                        <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="nav-title">الطلبات</span>
                        </a>
                    </div>
                </div>

                <!-- Reports Section -->
                <div class="nav-section">
                    <div class="nav-section-title">التقارير</div>
                    <div class="nav-item">
                        <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i>
                            <span class="nav-title">التقارير</span>
                        </a>
                    </div>
                </div>

                <!-- User Management Section -->
                <div class="nav-section">
                    <div class="nav-section-title">إدارة المستخدمين</div>
                    <div class="nav-item">
                        <a href="/user/profile" class="nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                            <i class="fas fa-user"></i>
                            <span class="nav-title">الملف الشخصي</span>
                        </a>
                    </div>
                </div>
            </nav>
        </aside>

        <!-- Mobile Toggle Button -->
        <button class="sidebar-toggle d-lg-none" id="sidebarToggle" aria-label="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Main Content -->
        <main class="main-content-wrapper">
            <!-- Top Navigation -->
            <nav class="navbar navbar-expand-lg navbar-light glass-effect">
                <div class="container-fluid">
                    <div class="d-flex align-items-center">
                        <div>
                            <h1 class="h4 mb-0 page-title text-truncate">@yield('page_title', 'لوحة التحكم')</h1>
                            <div class="page-subtitle">@yield('page_subtitle', 'مرحباً بك في لوحة التحكم')</div>
                        </div>
                    </div>

                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown position-static">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-avatar">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                            </a>
                            <div class="dropdown-menu position-absolute" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user-cog"></i>
                                    <span>الملف الشخصي</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <span>تسجيل الخروج</span>
                                    </button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="container-fluid fade-in">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize all dropdowns
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
        var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl, {
                offset: [0, 10],
                placement: 'bottom-start'
            });
        });

        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const closeSidebar = document.getElementById('closeSidebar');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
            document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
        }

        function closeSidebarFunc() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        sidebarToggle.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', closeSidebarFunc);
        if (closeSidebar) {
            closeSidebar.addEventListener('click', closeSidebarFunc);
        }

        // Close sidebar on window resize if in mobile view
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992 && sidebar.classList.contains('active')) {
                closeSidebarFunc();
            }
        });

        // Add animation to dropdown items
        document.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(5px)';
            });

            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });

        // Add animation to cards
        document.querySelectorAll('.card').forEach(card => {
            card.classList.add('hover-card');
        });

        // Add animation to buttons
        document.querySelectorAll('.btn-primary').forEach(btn => {
            btn.classList.add('btn-modern');
        });
    </script>
    @yield('scripts')
</body>
</html>
