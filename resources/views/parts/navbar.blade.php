    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg glass-navbar sticky-top">
      <div class="container">
            <a class="navbar-brand" href="/">
               <img src="/assets/images/logo.png" alt="Madil" height="70">
            </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
              <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
              <!-- Center Navigation -->
              <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                  <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">الرئيسية</a>
                  </li>
                  <li class="nav-item">
                        <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="/about">من نحن</a>
                  </li>
                  <li class="nav-item">
                        <a class="nav-link {{ request()->is('services') ? 'active' : '' }}" href="/services">الخدمات</a>
                  </li>
                  <li class="nav-item">
                        <a class="nav-link {{ request()->is('products*') ? 'active' : '' }}" href="/products">المنتجات</a>
                  </li>
                  <li class="nav-item">
                        <a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="/contact">تواصل معنا</a>
                  </li>
              </ul>

              <!-- Right Side Icons -->
              <div class="d-flex align-items-center gap-2">
                  <!-- Cart Button -->
                  <div class="cart-wrapper">
                      @auth
                      <a href="{{ route('cart.index') }}" class="btn btn-outline-primary position-relative" id="cartToggle" style="min-width: 44px;">
                          <i class="fas fa-shopping-cart"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count">
                                {{ app(\App\Http\Controllers\CartController::class)->getCartCount() }}
                            </span>
                      </a>
                      @else
                      <button class="btn btn-outline-primary position-relative" id="cartToggle" style="min-width: 44px;" onclick="window.location.href='{{ route('login') }}'">
                          <i class="fas fa-shopping-cart"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count">
                                0
                            </span>
                      </button>
                      @endauth
                  </div>

                  <!-- Profile Dropdown (updated to a simpler implementation) -->
                  <div class="dropdown profile-dropdown-container">
                      <button class="btn btn-outline-primary profile-dropdown" type="button" id="profileMenuButton" onclick="toggleProfileMenu()">
                          <i class="fas fa-user"></i>
                          <span class="dropdown-arrow-wrapper">
                              <i class="fas fa-caret-down dropdown-arrow"></i>
                          </span>
                      </button>
                      <div class="dropdown-menu dropdown-menu-end profile-menu" id="profileDropdownMenu">
                          @auth
                              <a class="dropdown-item {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">لوحة التحكم</a>
                              <a class="dropdown-item {{ request()->is('user/profile') ? 'active' : '' }}" href="/user/profile">الملف الشخصي</a>
                              <div class="dropdown-divider"></div>
                              <form action="{{ route('logout') }}" method="POST">
                                  @csrf
                                  <button type="submit" class="dropdown-item">تسجيل الخروج</button>
                              </form>
                          @else
                              <a class="dropdown-item {{ request()->is('login') ? 'active' : '' }}" href="/login">تسجيل الدخول</a>
                              <a class="dropdown-item {{ request()->is('register') ? 'active' : '' }}" href="/register">إنشاء حساب</a>
                          @endauth
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </nav>

    <!-- Add Custom JavaScript for profile dropdown -->
    <script>
        function toggleProfileMenu() {
            const menu = document.getElementById('profileDropdownMenu');
            const button = document.getElementById('profileMenuButton');

            if (menu) {
                // Toggle show class
                menu.classList.toggle('show');

                // Update button state
                if (menu.classList.contains('show')) {
                    button.classList.add('active');
                    button.setAttribute('aria-expanded', 'true');
                    button.style.backgroundColor = 'var(--primary-color)';
                    button.style.color = 'white';

                    // Calculate position from the button, fixed with respect to viewport
                    const buttonRect = button.getBoundingClientRect();

                    // Position menu relative to viewport rather than document
                    menu.style.position = 'fixed';
                    menu.style.top = (buttonRect.bottom + 5) + 'px'; // Add small margin

                    // Calculate horizontal position (right-aligned)
                    const menuWidth = menu.offsetWidth || 200; // Default if not measurable yet
                    const rightEdge = buttonRect.right;
                    let leftPosition = rightEdge - menuWidth;

                    // Check if menu is off-screen to the left
                    if (leftPosition < 10) {
                        leftPosition = 10; // Minimum margin from left edge
                    }

                    // Check if menu is off-screen to the right
                    const windowWidth = window.innerWidth;
                    if (leftPosition + menuWidth > windowWidth - 10) {
                        leftPosition = windowWidth - menuWidth - 10; // 10px margin from right edge
                    }

                    // Apply position
                    menu.style.left = leftPosition + 'px';

                    // Ensure it's visible
                    menu.style.zIndex = '1050'; // Higher than navbar
                } else {
                    button.classList.remove('active');
                    button.setAttribute('aria-expanded', 'false');
                    button.style.backgroundColor = '';
                    button.style.color = '';
                }

                // Close menu when clicking outside
                const closeMenu = function(e) {
                    if (!menu.contains(e.target) && !button.contains(e.target)) {
                        menu.classList.remove('show');
                        button.classList.remove('active');
                        button.setAttribute('aria-expanded', 'false');
                        button.style.backgroundColor = '';
                        button.style.color = '';
                        document.removeEventListener('click', closeMenu);
                    }
                };

                // Remove existing handler and add a new one
                document.removeEventListener('click', closeMenu);

                // Delay adding the event listener to prevent immediate closing
                if (menu.classList.contains('show')) {
                    setTimeout(() => {
                        document.addEventListener('click', closeMenu);
                    }, 10);
                }
            }
        }

        // Update dropdown position when scrolling to keep it with the button
        window.addEventListener('scroll', function() {
            const menu = document.getElementById('profileDropdownMenu');
            const button = document.getElementById('profileMenuButton');

            if (menu && menu.classList.contains('show') && button) {
                // Recalculate position
                const buttonRect = button.getBoundingClientRect();
                menu.style.top = (buttonRect.bottom + 5) + 'px';

                // Horizontal position
                const menuWidth = menu.offsetWidth;
                const rightEdge = buttonRect.right;
                let leftPosition = rightEdge - menuWidth;

                // Apply bounds checking
                const windowWidth = window.innerWidth;
                if (leftPosition < 10) {
                    leftPosition = 10;
                } else if (leftPosition + menuWidth > windowWidth - 10) {
                    leftPosition = windowWidth - menuWidth - 10;
                }

                menu.style.left = leftPosition + 'px';
            }
        });

        // Close any open dropdown when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            const menus = document.querySelectorAll('.dropdown-menu.show');
            menus.forEach(menu => menu.classList.remove('show'));
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const menu = document.getElementById('profileDropdownMenu');
            const button = document.getElementById('profileMenuButton');

            if (menu && menu.classList.contains('show') && button) {
                // Recalculate position
                const buttonRect = button.getBoundingClientRect();
                menu.style.top = (buttonRect.bottom + 5) + 'px';

                // Horizontal position
                const menuWidth = menu.offsetWidth;
                const rightEdge = buttonRect.right;
                let leftPosition = rightEdge - menuWidth;

                // Apply bounds checking
                const windowWidth = window.innerWidth;
                if (leftPosition < 10) {
                    leftPosition = 10;
                } else if (leftPosition + menuWidth > windowWidth - 10) {
                    leftPosition = windowWidth - menuWidth - 10;
                }

                menu.style.left = leftPosition + 'px';
            }
        });
    </script>

    @auth
    <!-- إضافة كود JavaScript لتحديث عداد السلة للمستخدمين المسجلين -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateCartCounter() {
            fetch('{{ route('cart.count') }}')
                .then(response => response.json())
                .then(data => {
                    const cartCounterElements = document.querySelectorAll('.cart-count');
                    cartCounterElements.forEach(element => {
                        element.textContent = data.count;
                        // إخفاء العداد إذا كان صفر
                        if (data.count == 0) {
                            element.classList.add('d-none');
                        } else {
                            element.classList.remove('d-none');
                        }
                    });
                })
                .catch(error => console.error('خطأ في تحديث عداد السلة:', error));
        }

        // تحديث العداد عند تحميل الصفحة
        updateCartCounter();

        // تحديث العداد كل دقيقة
        setInterval(updateCartCounter, 60000);

        // تعريف الدالة التي تستمع لأحداث إضافة أو حذف منتجات من السلة
        function setupCartListeners() {
            // الاستماع لأزرار إضافة المنتج للسلة
            document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                button.addEventListener('click', function() {
                    // تأخير قصير للسماح للطلب بالانتهاء
                    setTimeout(updateCartCounter, 500);
                });
            });

            // الاستماع لأزرار حذف المنتج من السلة
            document.querySelectorAll('.remove-from-cart-btn').forEach(button => {
                button.addEventListener('click', function() {
                    // تأخير قصير للسماح للطلب بالانتهاء
                    setTimeout(updateCartCounter, 500);
                });
            });
        }

        // تنفيذ الاستماع عند تحميل الصفحة
        setupCartListeners();

        // إعادة تنفيذ الاستماع عند التنقل بين الصفحات بـ Ajax
        document.addEventListener('page-loaded', setupCartListeners);
    });
    </script>
    @endauth
