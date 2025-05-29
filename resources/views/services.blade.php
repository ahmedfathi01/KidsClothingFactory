<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    @include('parts.head')
    <title>خدماتنا - بر الليث</title>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/kids/css/services.css') }}">
</head>
<body>
    @include('parts.navbar')

    @include('parts.breadcrumbs', ['title' => 'خدماتنا'])

    <!-- Services Section -->
    <section class="services-section">
        <div class="container">
            <h2>الخدمات التي نقدمها</h2>
            <div class="row">
                <!-- Service 1 -->
                <div class="col-md-4 mb-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-tshirt"></i>
                        </div>
                        <h3>ملابس أطفال مخصصة</h3>
                        <p>نصمم ونصنع ملابس أطفال أنيقة ومريحة باستخدام أفضل أنواع الأقمشة ذات الجودة العالية.</p>
                    </div>
                </div>
                <!-- Service 2 -->
                <div class="col-md-4 mb-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h3>أقمشة صديقة للبيئة</h3>
                        <p>نحرص على استخدام مواد عضوية وآمنة للأطفال في جميع عمليات التصنيع لدينا.</p>
                    </div>
                </div>
                <!-- Service 3 -->
                <div class="col-md-4 mb-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <h3>توصيل سريع وآمن</h3>
                        <p>نوفر خدمة توصيل سريعة وآمنة لضمان وصول ملابس أطفالكم في الوقت المطلوب.</p>
                    </div>
                </div>
                <!-- Service 4 -->
                <div class="col-md-4 mb-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <h3>تصنيع أخلاقي</h3>
                        <p>نلتزم بممارسات عمل عادلة ونحرص على السلامة والجودة في مصنعنا.</p>
                    </div>
                </div>
                <!-- Service 5 -->
                <div class="col-md-4 mb-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <h3>طلبات الجملة</h3>
                        <p>نقدم عروضًا مميزة للطلبات بالجملة، مثالية للمتاجر ومنظمي الفعاليات.</p>
                    </div>
                </div>
                <!-- Service 6 -->
                <div class="col-md-4 mb-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3>دعم فني على مدار الساعة</h3>
                        <p>فريق الدعم لدينا متاح 24/7 لمساعدتكم في أي استفسارات أو مشكلات.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('parts.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>
