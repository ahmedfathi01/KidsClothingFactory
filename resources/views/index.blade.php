<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    @include('parts.head')
    <title>بر الليث - ملابس أطفال مميزة</title>

    <!-- Preload critical assets -->
    <link rel="preload" href="{{ asset('assets/kids/img/hero/hero-1.jpg') }}" as="image">
    <link rel="preload" href="{{ asset('assets/kids/css/common.css') }}" as="style">
    <link rel="preload" href="{{ asset('assets/kids/css/home.css') }}" as="style">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/kids/css/home.css') }}">
    <style>
        .color-options {
            display: flex;
            gap: 8px;
            margin-top: 8px;
        }

        .color-option {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #fff;
            cursor: pointer;
            border: 1px solid #ddd;
            transition: transform 0.2s;
        }

        .color-option:hover {
            transform: scale(1.1);
        }

        .more-colors {
            background-color: #f5f5f5;
            color: #333;
        }
    </style>
</head>
<body>
    @include('parts.navbar')

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container-fluid p-0">
            <div class="position-relative">
                <img src="{{ asset('assets/kids/img/hero/hero-1.jpg') }}" class="img-fluid w-100" alt="تشكيلة شتاء للأطفال" width="1920" height="900">
                <div class="hero-content">
                    <span class="small-text">تشكيلة الشتاء</span>
                    <h1>تشكيلة خريف - شتاء<br>2030</h1>
                    <p>علامة تجارية متخصصة في صناعة الملابس الفاخرة. مصنوعة بحرفية عالية مع التزام لا يتزعزع بالجودة الاستثنائية.</p>
                    <a href="{{ route('shop') }}" class="btn btn-dark px-4 py-2">تسوق الآن</a>
                    <div class="social-icons mt-4">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Collections Section -->
    <section class="collections-section py-5">
        <div class="container">
            <div class="row">
                @if(App\Models\Category::count() > 0)
                    @foreach(App\Models\Category::withCount('products')->latest()->take(3)->get() as $index => $category)
                        @if($index == 0)
                            <div class="col-md-6 mb-4">
                                <div class="collection-card position-relative">
                                    <img src="{{ $category->image ? $category->image_url : asset('assets/kids/img/banner/banner-1.jpg') }}" class="img-fluid" alt="{{ $category->name }}" width="600" height="300" loading="lazy">
                                    <div class="collection-content">
                                        <h3>{{ $category->name }}</h3>
                                        <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="shop-link">تسوق الآن</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="row">
                        @elseif($index <= 2)
                                    <div class="col-12 {{ $index == 1 ? 'mb-4' : '' }}">
                                        <div class="collection-card position-relative">
                                            <img src="{{ $category->image ? $category->image_url : asset('assets/kids/img/banner/banner-'.($index + 1).'.jpg') }}" class="img-fluid" alt="{{ $category->name }}" width="600" height="140" loading="lazy">
                                            <div class="collection-content">
                                                <h3>{{ $category->name }}</h3>
                                                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="shop-link">تسوق الآن</a>
                                            </div>
                                        </div>
                                    </div>
                        @endif
                        @if($index == 2)
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="col-md-6 mb-4">
                        <div class="collection-card position-relative">
                            <img src="{{ asset('assets/kids/img/banner/banner-1.jpg') }}" class="img-fluid" alt="تشكيلة الملابس" width="600" height="300" loading="lazy">
                            <div class="collection-content">
                                <h3>تشكيلة<br>ملابس 2025</h3>
                                <a href="{{ route('products.index') }}" class="shop-link">تسوق الآن</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="collection-card position-relative">
                                    <img src="{{ asset('assets/kids/img/banner/banner-2.jpg') }}" class="img-fluid" alt="إكسسوارات" width="600" height="140" loading="lazy">
                                    <div class="collection-content">
                                        <h3>إكسسوارات</h3>
                                        <a href="{{ route('products.index') }}" class="shop-link">تسوق الآن</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="collection-card position-relative">
                                    <img src="{{ asset('assets/kids/img/banner/banner-3.jpg') }}" class="img-fluid" alt="مجموعة ملابس" width="600" height="140" loading="lazy">
                                    <div class="collection-content">
                                        <h3>مجموعة ملابس<br>2025</h3>
                                        <a href="{{ route('products.index') }}" class="shop-link">تسوق الآن</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories-section py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="categories-menu">
                        <h4 class="mb-4">ملابس رائجة</h4>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('products.index') }}" class="active">جميع المنتجات</a></li>
                            @foreach($topCategories as $category)
                                <li><a href="{{ route('products.index', ['category' => $category->slug]) }}">{{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-8">
                    @if($allCoupons->isNotEmpty())
                    <div class="deals-carousel">
                        @foreach($coupons as $coupon)
                        <div class="deal-box mb-3">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="sale-badge">
                                        <span>تخفيض</span>
                                        <h5>{{ $coupon->type == 'percentage' ? $coupon->value . '%' : $coupon->value . ' ريال' }}</h5>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="deal-content">
                                        <span class="deal-label">كوبون خصم</span>
                                        <h3>{{ $coupon->name }}</h3>
                                        <h5>كود الخصم: <span class="text-primary">{{ $coupon->code }}</span></h5>
                                        <div class="countdown-timer"
                                             data-expires="{{ $coupon->expires_at->format('Y-m-d H:i:s') }}">
                                            <div class="expired-message">انتهى العرض</div>
                                            <div class="countdown-title">ينتهي العرض خلال:</div>
                                            <div class="countdown-container">
                                                <div class="countdown-item">
                                                    <div class="countdown-circle">
                                                        <span class="number days">0</span>
                                                    </div>
                                                    <span class="text">يوم</span>
                                                </div>
                                                <div class="countdown-item">
                                                    <div class="countdown-circle">
                                                        <span class="number hours">0</span>
                                                    </div>
                                                    <span class="text">ساعة</span>
                                                </div>
                                                <div class="countdown-item">
                                                    <div class="countdown-circle">
                                                        <span class="number minutes">0</span>
                                                    </div>
                                                    <span class="text">دقيقة</span>
                                                </div>
                                                <div class="countdown-item">
                                                    <div class="countdown-circle">
                                                        <span class="number seconds">0</span>
                                                    </div>
                                                    <span class="text">ثانية</span>
                                                </div>
                                            </div>
                                        </div>
                                        @if($coupon->categories->isNotEmpty())
                                            <div class="mt-2">
                                                <p class="mb-2">الفئات المشمولة:</p>
                                                @foreach($coupon->categories->take(3) as $category)
                                                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="badge bg-secondary me-1">{{ $category->name }}</a>
                                                @endforeach
                                                @if($coupon->categories->count() > 3)
                                                    <span class="badge bg-secondary">+{{ $coupon->categories->count() - 3 }}</span>
                                                @endif
                                            </div>
                                        @endif
                                        @if($coupon->products->isNotEmpty())
                                            <div class="mt-2">
                                                <p class="mb-2">المنتجات المشمولة:</p>
                                                @foreach($coupon->products->take(3) as $product)
                                                    <a href="{{ route('products.show', $product->slug) }}" class="badge bg-primary me-1">{{ $product->name }}</a>
                                                @endforeach
                                                @if($coupon->products->count() > 3)
                                                    <span class="badge bg-primary">+{{ $coupon->products->count() - 3 }}</span>
                                                @endif
                                            </div>
                                        @endif
                                        <a href="{{ route('products.index') }}" class="btn btn-dark px-4 py-2 mt-3">تسوق الآن</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @if($totalPages > 1)
                        <div class="pagination-container text-center mt-4">
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    @if($currentPage > 1)
                                        <li class="page-item">
                                            <a class="page-link" href="?page={{ $currentPage - 1 }}" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                    @endif

                                    @for($i = 1; $i <= $totalPages; $i++)
                                        <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                                            <a class="page-link" href="?page={{ $i }}">{{ $i }}</a>
                                        </li>
                                    @endfor

                                    @if($currentPage < $totalPages)
                                        <li class="page-item">
                                            <a class="page-link" href="?page={{ $currentPage + 1 }}" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="deal-box">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="sale-badge">
                                    <span>تخفيض</span>
                                    <h5>29.99 ريال</h5>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="deal-content">
                                    <span class="deal-label">عرض الأسبوع</span>
                                    <h3>حقيبة صدر سوداء متعددة الجيوب</h3>
                                    @php
                                        $defaultExpiry = now()->addDays(30);
                                    @endphp
                                    <div class="countdown-timer"
                                        data-expires="{{ $defaultExpiry->format('Y-m-d H:i:s') }}">
                                        <div class="expired-message">انتهى العرض</div>
                                        <div class="countdown-title">ينتهي العرض خلال:</div>
                                        <div class="countdown-container">
                                            <div class="countdown-item">
                                                <div class="countdown-circle">
                                                    <span class="number days">0</span>
                                                </div>
                                                <span class="text">يوم</span>
                                            </div>
                                            <div class="countdown-item">
                                                <div class="countdown-circle">
                                                    <span class="number hours">0</span>
                                                </div>
                                                <span class="text">ساعة</span>
                                            </div>
                                            <div class="countdown-item">
                                                <div class="countdown-circle">
                                                    <span class="number minutes">0</span>
                                                </div>
                                                <span class="text">دقيقة</span>
                                            </div>
                                            <div class="countdown-item">
                                                <div class="countdown-circle">
                                                    <span class="number seconds">0</span>
                                                </div>
                                                <span class="text">ثانية</span>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('products.index') }}" class="btn btn-dark px-4 py-2 mt-3">تسوق الآن</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="featured-products-section py-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <span class="section-badge">منتجات مميزة</span>
                <h2 class="section-title">منتجاتنا المميزة</h2>
                <p class="section-subtitle">اكتشف تشكيلاتنا من ملابس الأطفال الفاخرة</p>
            </div>

            <div class="featured-products-container">
                <div class="row g-4">
                    @foreach($featuredProducts as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="product-card">
                            <div class="product-image-wrapper">
                                <a href="{{ route('products.show', $product->slug) }}" class="product-link">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image" loading="lazy">
                                    @if($product->discount_percentage > 0)
                                    <div class="discount-badge">-{{ $product->discount_percentage }}%</div>
                                    @endif
                                </a>
                                <div class="product-actions">
                                    <a href="{{ route('products.show', $product->slug) }}" class="action-btn quickview-btn" title="عرض المنتج">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="product-info">
                                <div class="product-category">{{ $product->category_name }}</div>
                                <h3 class="product-title">
                                    <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                </h3>
                                <div class="product-price">
                                    @if($product->min_price == $product->max_price)
                                        @if($product->original_price > $product->min_price)
                                            <span class="original-price">{{ number_format($product->original_price, 2) }} ريال</span>
                                        @endif
                                        <span class="current-price">{{ number_format($product->min_price, 2) }} ريال</span>
                                    @else
                                        @if($product->original_price > $product->min_price)
                                            <span class="original-price">{{ number_format($product->original_price, 2) }} ريال</span>
                                        @endif
                                        <span class="current-price">{{ number_format($product->min_price, 2) }} - {{ number_format($product->max_price, 2) }} ريال</span>
                                    @endif
                                </div>
                                <div class="product-colors">
                                    @if($product->allow_color_selection && $product->colors->isNotEmpty())
                                        <div class="color-options">
                                            @foreach($product->colors->take(4) as $color)
                                                <span class="color-option" style="background-color: {{ $color->color_code }}" title="{{ $color->name ?? $color->color }}"></span>
                                            @endforeach
                                            @if($product->colors->count() > 4)
                                                <span class="color-option more-colors">+{{ $product->colors->count() - 4 }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <a href="{{ route('products.show', $product->slug) }}" class="buy-now-btn">اشتري الآن</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="view-all-container text-center mt-5">
                <a href="{{ route('shop') }}" class="btn-view-all">عرض جميع المنتجات</a>
            </div>
        </div>
    </section>

    <!-- Instagram Section -->
    <section class="instagram-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="instagram-grid">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <img src="{{ asset('assets/kids/img/instagram/instagram-1.jpg') }}" class="img-fluid" alt="منشور انستغرام" width="300" height="180" loading="lazy">
                            </div>
                            <div class="col-md-4">
                                <img src="{{ asset('assets/kids/img/instagram/instagram-2.jpg') }}" class="img-fluid" alt="منشور انستغرام" width="300" height="180" loading="lazy">
                            </div>
                            <div class="col-md-4">
                                <img src="{{ asset('assets/kids/img/instagram/instagram-3.jpg') }}" class="img-fluid" alt="منشور انستغرام" width="300" height="180" loading="lazy">
                            </div>
                            <div class="col-md-4">
                                <img src="{{ asset('assets/kids/img/instagram/instagram-4.jpg') }}" class="img-fluid" alt="منشور انستغرام" width="300" height="180" loading="lazy">
                            </div>
                            <div class="col-md-4">
                                <img src="{{ asset('assets/kids/img/instagram/instagram-5.jpg') }}" class="img-fluid" alt="منشور انستغرام" width="300" height="180" loading="lazy">
                            </div>
                            <div class="col-md-4">
                                <img src="{{ asset('assets/kids/img/instagram/instagram-6.jpg') }}" class="img-fluid" alt="منشور انستغرام" width="300" height="180" loading="lazy">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="instagram-content">
                        <h2>انستغرام</h2>
                        <p>تابعونا على انستغرام لمشاهدة أحدث تشكيلاتنا وعروضنا الحصرية من ملابس الأطفال المميزة.</p>
                        <h4 class="hashtag">#أزياء_الأطفال</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('parts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>

    <script src="{{ asset('assets/kids/js/products.js') }}" defer></script>

    <script>
        (function() {
            function initCountdowns() {
                const countdownTimers = document.querySelectorAll('.countdown-timer');

                if (countdownTimers.length === 0) {
                    setTimeout(initCountdowns, 500);
                    return;
                }

                countdownTimers.forEach(function(timer, index) {
                    if (!timer.dataset.expires) {
                        return;
                    }

                    const expiryTime = new Date(timer.dataset.expires).getTime();

                    const expiredMessage = timer.querySelector('.expired-message');
                    const daysElement = timer.querySelector('.days');
                    const hoursElement = timer.querySelector('.hours');
                    const minutesElement = timer.querySelector('.minutes');
                    const secondsElement = timer.querySelector('.seconds');
                    const countdownContainer = timer.querySelector('.countdown-container');

                    if (!daysElement || !hoursElement || !minutesElement || !secondsElement) {
                        return;
                    }

                    function updateCountdown() {
                        const now = new Date().getTime();
                        const timeLeft = expiryTime - now;

                        if (timeLeft <= 0) {
                            if (expiredMessage) expiredMessage.style.display = 'block';
                            if (countdownContainer) countdownContainer.style.display = 'none';
                            return;
                        }

                        const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                        // Add animation class only when the value changes
                        if (daysElement.textContent != days) {
                            addPulseAnimation(daysElement.closest('.countdown-circle'));
                        }
                        if (hoursElement.textContent != hours) {
                            addPulseAnimation(hoursElement.closest('.countdown-circle'));
                        }
                        if (minutesElement.textContent != minutes) {
                            addPulseAnimation(minutesElement.closest('.countdown-circle'));
                        }
                        if (secondsElement.textContent != seconds) {
                            addPulseAnimation(secondsElement.closest('.countdown-circle'));
                        }

                        daysElement.textContent = days;
                        hoursElement.textContent = hours;
                        minutesElement.textContent = minutes;
                        secondsElement.textContent = seconds;
                    }

                    function addPulseAnimation(element) {
                        if (!element) return;
                        element.classList.add('pulse-animation');
                        setTimeout(() => {
                            element.classList.remove('pulse-animation');
                        }, 500);
                    }

                    updateCountdown();

                    const intervalId = setInterval(updateCountdown, 1000);
                });
            }

            initCountdowns();

            document.addEventListener('DOMContentLoaded', initCountdowns);
            window.addEventListener('load', initCountdowns);
        })();
    </script>
</body>
</html>
