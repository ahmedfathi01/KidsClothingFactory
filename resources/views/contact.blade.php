<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    @include('parts.head')
    <title>معلومات التواصل - بر الليث</title>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/kids/css/contact.css') }}">
</head>
<body>
    @include('parts.navbar')

    @include('parts.breadcrumbs', ['title' => 'معلومات التواصل'])

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="contact-info">
                        <h2>تواصل معنا</h2>
                        <p>يسعدنا تواصلكم معنا لأي استفسار أو ملاحظة.</p>

                        <h4>المملكة العربية السعودية</h4>
                        <p class="address">195 شارع باركر، كولورادو 801</p>
                        <p class="phone">+43 982-314-0958</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="contact-form">
                        <h2>أرسل لنا رسالة</h2>
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="اسمك" value="{{ old('name') }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="بريدك الإلكتروني" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-4">
                                <textarea name="message" class="form-control @error('message') is-invalid @enderror" placeholder="رسالتك">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-success">إرسال الرسالة</button>
                        </form>
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
