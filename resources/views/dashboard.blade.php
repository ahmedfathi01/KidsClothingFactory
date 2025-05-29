@extends('layouts.customer')

@section('title', 'لوحة التحكم')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/customer/dashboard.css') }}">
@endsection

@section('content')
<div class="container py-4">
    <!-- Welcome Section -->
    <div class="welcome-section mb-4">
        <div class="row align-items-center">
            <div class="col-12 col-md-8 mb-3 mb-md-0">
                <h1 class="h3 mb-1">مرحباً، {{ Auth::user()->name }}</h1>
                <p class="text-muted mb-0">مرحباً بك في لوحة التحكم الخاصة بك</p>
            </div>
            <div class="col-12 col-md-4 text-center text-md-end">
                <span class="badge bg-primary">{{ Auth::user()->role === 'admin' ? 'مدير' : 'عميل' }}</span>
            </div>
        </div>
        <!-- Guide Hint -->
        <div class="guide-hint mt-3">
            <div class="alert alert-info d-flex align-items-center border-0" role="alert">
                <i class="fas fa-lightbulb me-2 text-warning"></i>
                <span>تحتاج مساعدة؟ اضغط على زر <i class="fas fa-question-circle mx-1 text-primary"></i> في أسفل يسار الشاشة لعرض دليل استخدام لوحة التحكم</span>
            </div>
        </div>
    </div>

    <!-- Guide Toggle Button -->
    <button class="guide-toggle-btn" id="guideToggle" title="دليل الاستخدام">
        <i class="fas fa-question"></i>
    </button>

    <!-- User Guide Section -->
    <div class="user-guide-section" id="userGuide">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="fas fa-book-reader me-2 text-primary"></i>
                    دليل استخدام لوحة التحكم
                </h5>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="guide-item">
                            <h6>
                                <i class="fas fa-phone-alt text-primary me-2"></i>
                                إدارة أرقام الهاتف
                            </h6>
                            <ul class="text-muted small">
                                <li>اضغط على "إضافة رقم" لتسجيل رقم هاتف جديد</li>
                                <li>يمكنك تعيين رقم كرقم رئيسي باستخدام أيقونة النجمة</li>
                                <li>استخدم أيقونة التعديل لتحديث رقم موجود</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="guide-item">
                            <h6>
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                إدارة العناوين
                            </h6>
                            <ul class="text-muted small">
                                <li>اضغط على "إضافة عنوان" لتسجيل عنوان جديد</li>
                                <li>أدخل تفاصيل العنوان كاملة للتوصيل السريع</li>
                                <li>يمكنك تحديد عنوان رئيسي للطلبات المستقبلية</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="guide-item">
                            <h6>
                                <i class="fas fa-shopping-bag text-primary me-2"></i>
                                متابعة الطلبات
                            </h6>
                            <ul class="text-muted small">
                                <li>راقب آخر طلباتك وحالتها في قسم "آخر الطلبات"</li>
                                <li>اضغط على أيقونة العين لعرض تفاصيل أي طلب</li>
                                <li>تابع حالة طلبك من خلال الألوان المميزة</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 g-md-4 mb-4">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="dashboard-card orders">
                <div class="card-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="card-info">
                    <h3>{{ $stats['orders_count'] }}</h3>
                    <p>الطلبات</p>
                </div>
                <div class="card-arrow">
                    <a href="/orders" class="stretched-link">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="dashboard-card cart">
                <div class="card-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="card-info">
                    <h3>{{ $stats['cart_items_count'] }}</h3>
                    <p>منتجات في السلة</p>
                </div>
                <div class="card-arrow">
                    <a href="/cart" class="stretched-link">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="dashboard-card notifications">
                <div class="card-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="card-info">
                    <h3>{{ $stats['unread_notifications'] }}</h3>
                    <p>إشعارات جديدة</p>
                </div>
                <div class="card-arrow">
                    <a href="/notifications" class="stretched-link">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Phone Numbers & Addresses Section -->
    <div class="row g-3 g-md-4 mb-4">
        <!-- Phone Numbers -->
        <div class="col-12 col-xl-6">
            <div class="card h-100">
                <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                    <h5 class="mb-0">أرقام الهاتف</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPhoneModal">
                        <i class="fas fa-plus ms-1"></i>إضافة رقم
                    </button>
                </div>
                <div class="card-body">
                    @if($phones->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-phone"></i>
                        <p>لا توجد أرقام هاتف مسجلة</p>
                    </div>
                    @else
                    <div class="list-group">
                        @foreach($phones as $phone)
                        <div class="list-group-item {{ $phone['is_primary'] ? 'active' : '' }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-phone me-2"></i>
                                        <span class="phone-number" dir="ltr">{{ substr($phone['phone'], 0, 4) }} {{ substr($phone['phone'], 4, 3) }} {{ substr($phone['phone'], 7) }}</span>
                                        @if($phone['is_primary'])
                                        <span class="badge bg-warning ms-2 primary-badge">رئيسي</span>
                                        @endif
                                        <span class="badge bg-{{ $phone['type_color'] }} ms-2">{{ $phone['type_text'] }}</span>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        تم الإضافة: {{ $phone['created_at'] }}
                                    </small>
                                </div>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary edit-phone"
                                        data-id="{{ $phone['id'] }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editPhoneModal"
                                        title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @if(!$phone['is_primary'])
                                    <button class="btn btn-sm btn-outline-warning make-primary-phone"
                                        data-id="{{ $phone['id'] }}"
                                        title="تعيين كرقم رئيسي">
                                        <i class="fas fa-star"></i>
                                    </button>
                                    @endif
                                    <button class="btn btn-sm btn-outline-danger delete-phone"
                                        data-id="{{ $phone['id'] }}"
                                        title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Addresses -->
        <div class="col-12 col-xl-6">
            <div class="card h-100">
                <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                    <h5 class="mb-0">العناوين</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                        <i class="fas fa-plus ms-1"></i>إضافة عنوان
                    </button>
                </div>
                <div class="card-body">
                    @if($addresses->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-map-marker-alt"></i>
                        <p>لا توجد عناوين مسجلة</p>
                    </div>
                    @else
                    <div class="list-group">
                        @foreach($addresses as $address)
                        <div class="list-group-item {{ $address['is_primary'] ? 'active' : '' }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        <span>{{ $address['full_address'] }}</span>
                                        @if($address['is_primary'])
                                        <span class="badge bg-warning ms-2 primary-badge">رئيسي</span>
                                        @endif
                                        <span class="badge bg-{{ $address['type_color'] }} ms-2">{{ $address['type_text'] }}</span>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        تم الإضافة: {{ $address['created_at'] }}
                                    </small>
                                </div>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary edit-address"
                                        data-id="{{ $address['id'] }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editAddressModal"
                                        title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @if(!$address['is_primary'])
                                    <button class="btn btn-sm btn-outline-warning make-primary-address"
                                        data-id="{{ $address['id'] }}"
                                        title="تعيين كعنوان رئيسي">
                                        <i class="fas fa-star"></i>
                                    </button>
                                    @endif
                                    <button class="btn btn-sm btn-outline-danger delete-address"
                                        data-id="{{ $address['id'] }}"
                                        title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders Section -->
    <div class="row g-3 g-md-4">
        <!-- Recent Orders -->
        <div class="col-12">
            <div class="section-card h-100">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
                    <h2 class="mb-0">آخر الطلبات</h2>
                    <a href="/orders" class="btn btn-outline-primary btn-sm">
                        عرض الكل <i class="fas fa-arrow-left me-1"></i>
                    </a>
                </div>
                @if(count($recent_orders) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>رقم الطلب</th>
                                <th>التاريخ</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_orders as $order)
                            <tr>
                                <td>#{{ $order['order_number'] }}</td>
                                <td>{{ $order['created_at']->format('Y/m/d') }}</td>
                                <td>
                                    <span class="badge bg-{{ $order['status_color'] }}">
                                        {{ $order['status_text'] }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('orders.show', $order['uuid']) }}"
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-shopping-bag"></i>
                    <p>لا توجد طلبات حتى الآن</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Phone Modal -->
<div class="modal fade" id="addPhoneModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة رقم هاتف</h5>
                <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal"></button>
            </div>
            <form id="addPhoneForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">رقم الهاتف</label>
                        <input type="tel" class="form-control" name="phone" required>
                        <div class="form-text">مثال: 0512345678</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">النوع</label>
                        <select class="form-select" name="type" required>
                            <option value="">اختر نوع الرقم</option>
                            @foreach(App\Models\PhoneNumber::TYPES as $value => $text)
                                <option value="{{ $value }}">{{ $text }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Phone Modal -->
<div class="modal fade" id="editPhoneModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل رقم الهاتف</h5>
                <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal"></button>
            </div>
            <form id="editPhoneForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">رقم الهاتف</label>
                        <input type="tel" class="form-control" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">النوع</label>
                        <select class="form-select" name="type" required>
                            @foreach(App\Models\PhoneNumber::TYPES as $value => $text)
                                <option value="{{ $value }}">{{ $text }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="phone_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة عنوان</h5>
                <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal"></button>
            </div>
            <form id="addAddressForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">النوع</label>
                        <select class="form-select" name="type" required>
                            <option value="">اختر نوع العنوان</option>
                            @foreach(App\Models\Address::TYPES as $value => $text)
                                <option value="{{ $value }}">{{ $text }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">المدينة</label>
                        <input type="text" class="form-control" name="city" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">المنطقة</label>
                        <input type="text" class="form-control" name="area" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">الشارع</label>
                        <input type="text" class="form-control" name="street" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">رقم المبنى</label>
                        <input type="text" class="form-control" name="building_no">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تفاصيل إضافية</label>
                        <textarea class="form-control" name="details" rows="3"
                                  placeholder="مثال: بجوار مسجد، خلف مدرسة، الخ..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div class="modal fade" id="editAddressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل العنوان</h5>
                <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal"></button>
            </div>
            <form id="editAddressForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">النوع</label>
                        <select class="form-select" name="type" required>
                            @foreach(App\Models\Address::TYPES as $value => $text)
                                <option value="{{ $value }}">{{ $text }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">المدينة</label>
                        <input type="text" class="form-control" name="city" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">المنطقة</label>
                        <input type="text" class="form-control" name="area" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">الشارع</label>
                        <input type="text" class="form-control" name="street" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">رقم المبنى</label>
                        <input type="text" class="form-control" name="building_no">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تفاصيل إضافية</label>
                        <textarea class="form-control" name="details" rows="3"></textarea>
                    </div>
                    <input type="hidden" name="address_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>

<script>
    // تهيئة CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@endsection
