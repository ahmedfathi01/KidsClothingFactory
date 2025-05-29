<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    @include('parts.head')
    <title>من نحن - بر الليث</title>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/kids/css/about.css') }}">
</head>
<body>
    @include('parts.navbar')

    @include('parts.breadcrumbs', ['title' => 'من نحن'])

    <!-- About Section -->
    <section class="about-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('assets/kids/img/about/clothes-wooden-table.jpg') }}" alt="من نحن" class="img-fluid rounded">
                </div>
                <div class="col-lg-6">
                    <h2 class="mb-4">تعرف على متجر بر الليث</h2>
                    <p>نقدم لكم أفضل المنتجات للأطفال بجودة عالية وأسعار مناسبة</p>

                    <h2 class="mb-4 mt-5">من نحن</h2>
                    <p>نحن متجر بر الليث، نعمل في مجال ملابس الأطفال منذ سنوات طويلة، ونسعى دائماً لتقديم الأفضل لعملائنا الكرام. نفتخر بكوننا أحد أبرز المتاجر المتخصصة في ملابس الأطفال في منطقة مكة المكرمة.</p>

                    <h2 class="mb-4 mt-5">رؤيتنا</h2>
                    <p>أن نكون الخيار الأول للأهل الذين يبحثون عن الجودة والأناقة لملابس أطفالهم، مع الحفاظ على الأسعار المناسبة التي تناسب جميع الفئات.</p>

                    <h2 class="mb-4 mt-5">لماذا تختارنا؟</h2>
                    <ul class="list-unstyled why-choose-list">
                        <li><i class="fas fa-check-circle text-success ms-2"></i> <strong>جودة عالية:</strong> نختار موادنا بعناية لضمان الراحة والمتانة لأطفالكم</li>
                        <li><i class="fas fa-check-circle text-success ms-2"></i> <strong>تصاميم عصرية:</strong> أحدث صيحات الموضة للأطفال بألوان وتصاميم جذابة</li>
                        <li><i class="fas fa-check-circle text-success ms-2"></i> <strong>أسعار تنافسية:</strong> نقدم أفضل الأسعار مع الحفاظ على الجودة العالية</li>
                        <li><i class="fas fa-check-circle text-success ms-2"></i> <strong>خدمة عملاء مميزة:</strong> فريق خدمة العملاء لدينا جاهز لمساعدتك في أي وقت</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    @include('parts.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>
