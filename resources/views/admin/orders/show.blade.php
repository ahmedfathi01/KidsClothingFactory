@extends('layouts.admin')

@section('title', 'تفاصيل الطلب #' . $order->order_number)
@section('page_title', 'تفاصيل الطلب #' . $order->order_number)

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid px-0">
            <div class="row mx-0">
                <div class="col-12 px-0">
                    <div class="orders-container">
                        <!-- Header Actions -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-1 d-flex align-items-center">
                                                <span class="icon-circle bg-primary text-white me-2">
                                                    <i class="fas fa-info-circle"></i>
                                                </span>
                                                تفاصيل الطلب #{{ $order->order_number }}
                                            </h5>
                                            <p class="text-muted mb-0 fs-sm">عرض تفاصيل الطلب والمنتجات</p>
                                        </div>
                                        <div class="actions d-flex gap-2">
                                            <a href="{{ route('admin.orders.index') }}" class="btn btn-light-secondary">
                                                <i class="fas fa-arrow-right me-2"></i>
                                                عودة للطلبات
                                            </a>
                                            <button onclick="window.print()" class="btn btn-light-primary">
                                                <i class="fas fa-print me-2"></i>
                                                طباعة الطلب
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Stats -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm stat-card bg-gradient-primary h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-circle bg-white text-primary me-3">
                                                <i class="fas fa-shopping-cart fa-lg"></i>
                                            </div>
                                            <div>
                                                <h6 class="text-white mb-1">رقم الطلب</h6>
                                                <h3 class="text-white mb-0">#{{ $order->order_number }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm stat-card bg-gradient-success h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-circle bg-white text-success me-3">
                                                <i class="fas fa-box-open fa-lg"></i>
                                            </div>
                                            <div>
                                                <h6 class="text-white mb-1">عدد المنتجات</h6>
                                                <h3 class="text-white mb-0">{{ $order->items->count() }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm stat-card bg-gradient-info h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-circle bg-white text-info me-3">
                                                <i class="fas fa-money-bill-wave fa-lg"></i>
                                            </div>
                                            <div>
                                                <h6 class="text-white mb-1">إجمالي الطلب</h6>
                                                <h3 class="text-white mb-0">{{ number_format($order->total_amount) }} ريال</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm stat-card bg-gradient-warning h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-circle bg-white text-warning me-3">
                                                <i class="fas fa-clock fa-lg"></i>
                                            </div>
                                            <div>
                                                <h6 class="text-white mb-1">تاريخ الطلب</h6>
                                                <h3 class="text-white mb-0">{{ $order->created_at->format('Y/m/d') }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Details -->
                        <div class="row g-4">
                            <!-- Order Info -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4 d-flex align-items-center">
                                            <span class="icon-circle bg-primary text-white me-2">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                            معلومات الطلب
                                        </h5>
                                        <div class="info-list">
                                            <div class="info-item d-flex justify-content-between py-2">
                                                <span class="text-muted">حالة الطلب</span>
                                                <div>
                                                    <select name="order_status" class="form-select form-select-sm d-inline-block w-auto me-2"
                                                        onchange="this.form.submit()" form="update-status-form">
                                                        <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                                        <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                                                        <option value="out_for_delivery" {{ $order->order_status === 'out_for_delivery' ? 'selected' : '' }}>جاري التوصيل</option>
                                                        <option value="on_the_way" {{ $order->order_status === 'on_the_way' ? 'selected' : '' }}>في الطريق</option>
                                                        <option value="delivered" {{ $order->order_status === 'delivered' ? 'selected' : '' }}>تم التوصيل</option>
                                                        <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>مكتمل</option>
                                                        <option value="returned" {{ $order->order_status === 'returned' ? 'selected' : '' }}>مرتجع</option>
                                                        <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                                                    </select>
                                                    <span class="badge bg-{{ $order->status_color }}-subtle text-{{ $order->status_color }} rounded-pill">
                                                        {{ $order->status_text }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="info-item d-flex justify-content-between py-2">
                                                <span class="text-muted">طريقة الدفع</span>
                                                <span>{{ $order->payment_method === 'cash' ? 'كاش' : 'بطاقة' }}</span>
                                            </div>
                                            <div class="info-item d-flex justify-content-between py-2">
                                                <span class="text-muted">حالة الدفع</span>
                                                <div>
                                                    <select name="payment_status" class="form-select form-select-sm d-inline-block w-auto me-2"
                                                        onchange="this.form.submit()" form="update-payment-status-form">
                                                        <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>تم الدفع</option>
                                                        <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>فشل الدفع</option>
                                                    </select>
                                                    <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'danger') }}-subtle
                                                                 text-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'danger') }} rounded-pill">
                                                        {{ $order->payment_status === 'paid' ? 'تم الدفع' : ($order->payment_status === 'pending' ? 'قيد الانتظار' : 'فشل الدفع') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Customer Info -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4 d-flex align-items-center">
                                            <span class="icon-circle bg-primary text-white me-2">
                                                <i class="fas fa-user"></i>
                                            </span>
                                            معلومات العميل
                                        </h5>
                                        <div class="customer-info">
                                            <div class="d-flex align-items-center mb-4">
                                                <div class="avatar-circle bg-primary text-white me-3">
                                                    {{ substr($order->user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $order->user->name }}</h6>
                                                    <p class="text-muted mb-0">{{ $order->user->email }}</p>
                                                </div>
                                            </div>
                                            <div class="info-list">
                                                <div class="info-item d-flex align-items-center py-2">
                                                    <i class="fas fa-phone text-primary me-3"></i>
                                                    <span>{{ $order->phone }}</span>
                                                </div>
                                                <div class="info-item d-flex align-items-center py-2">
                                                    <i class="fas fa-map-marker-alt text-primary me-3"></i>
                                                    <span>{{ $order->shipping_address }}</span>
                                                </div>
                                                @if($order->notes)
                                                <div class="info-item d-flex align-items-center py-2">
                                                    <i class="fas fa-sticky-note text-primary me-3"></i>
                                                    <span>{{ $order->notes }}</span>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Products List -->
                            <div class="col-12">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4 d-flex align-items-center">
                                            <span class="icon-circle bg-primary text-white me-2">
                                                <i class="fas fa-shopping-bag"></i>
                                            </span>
                                            منتجات الطلب
                                        </h5>

                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="text-center" style="width: 40px">#</th>
                                                        <th>المنتج</th>
                                                        <th>السعر</th>
                                                        <th>الكمية</th>
                                                        <th>المجموع</th>
                                                        <th>الخيارات</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($order->items as $item)
                                                    <tr>
                                                        <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-shrink-0">
                                                                    @if($item->product->image)
                                                                    <img src="{{ asset($item->product->image) }}"
                                                                        class="product-image border"
                                                                        width="60" height="60"
                                                                        alt="{{ $item->product->name }}">
                                                                    @else
                                                                    <div class="product-image border d-flex align-items-center justify-content-center bg-light">
                                                                        <i class="fas fa-box text-muted fa-lg"></i>
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                                <div class="flex-grow-1 ms-3">
                                                                    <h6 class="mb-1 fw-bold">{{ $item->product->name }}</h6>
                                                                    @if($item->product->category)
                                                                    <span class="badge bg-primary-subtle text-primary">
                                                                        {{ $item->product->category->name }}
                                                                    </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-money-bill-wave text-success me-2"></i>
                                                                <span class="fw-bold">{{ number_format($item->unit_price) }}</span>
                                                                <small class="text-muted ms-1">ريال</small>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-light text-dark fw-bold">
                                                                {{ $item->quantity }} قطعة
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-calculator text-primary me-2"></i>
                                                                <span class="fw-bold">{{ number_format($item->subtotal) }}</span>
                                                                <small class="text-muted ms-1">ريال</small>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if($item->color || $item->size)
                                                            <div class="options-card p-2">
                                                                @if($item->color)
                                                                <div class="mb-1">
                                                                    <i class="fas fa-palette text-primary me-1"></i>
                                                                    <span class="text-muted">اللون:</span>
                                                                    <span class="fw-bold">{{ $item->color }}</span>
                                                                </div>
                                                                @endif
                                                                @if($item->size)
                                                                <div>
                                                                    <i class="fas fa-ruler text-primary me-1"></i>
                                                                    <span class="text-muted">المقاس:</span>
                                                                    <span class="fw-bold">{{ $item->size }}</span>
                                                                </div>
                                                                @endif
                                                            </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Contact Information -->
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title mb-4 d-flex align-items-center">
                                        <span class="icon-circle bg-primary text-white me-2">
                                            <i class="fas fa-address-book"></i>
                                        </span>
                                        معلومات الاتصال الإضافية
                                    </h5>

                                    @if($additionalAddresses->isNotEmpty())
                                    <div class="mb-4">
                                        <h6 class="mb-3">العناوين الإضافية</h6>
                                        <div class="row g-3">
                                            @foreach($additionalAddresses as $address)
                                            <div class="col-md-6">
                                                <div class="address-card bg-light p-3 rounded">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                        <span class="fw-bold">{{ $address->type_text }}</span>
                                                    </div>
                                                    <p class="mb-0">{{ $address->full_address }}</p>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                    @if($additionalPhones->isNotEmpty())
                                    <div>
                                        <h6 class="mb-3">أرقام الهواتف الإضافية</h6>
                                        <div class="row g-3">
                                            @foreach($additionalPhones as $phone)
                                            <div class="col-md-4">
                                                <div class="phone-card bg-light p-3 rounded">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-phone text-primary me-2"></i>
                                                        <div>
                                                            <div class="fw-bold">{{ $phone->phone }}</div>
                                                            <small class="text-muted">{{ $phone->type_text }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                    @if($additionalAddresses->isEmpty() && $additionalPhones->isEmpty())
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle mb-2 fa-2x"></i>
                                        <p class="mb-0">لا توجد معلومات اتصال إضافية</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="col-md-6">
                            <div class="card bg-white border-0 shadow-sm order-summary-clean">
                                <div class="card-header bg-white border-0 py-3">
                                    <h5 class="card-title mb-0 d-flex align-items-center">
                                        <span class="icon-circle bg-primary text-white me-2">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                        </span>
                                        ملخص الطلب
                                    </h5>
                                </div>
                                <div class="card-body py-0">
                                    <div class="order-summary-list">
                                        <div class="summary-item d-flex justify-content-between py-3 border-bottom">
                                            <span class="text-muted">رقم الطلب:</span>
                                            <strong class="text-dark">#{{ $order->order_number }}</strong>
                                        </div>
                                        <div class="summary-item d-flex justify-content-between py-3 border-bottom">
                                            <span class="text-muted">تاريخ الطلب:</span>
                                            <span>{{ $order->created_at->format('Y-m-d H:i') }}</span>
                                        </div>
                                        <div class="summary-item d-flex justify-content-between py-3 border-bottom">
                                            <span class="text-muted">السعر الأصلي:</span>
                                            <span>{{ number_format($order->original_amount, 2) }} ريال</span>
                                        </div>
                                        @if($order->quantity_discount > 0)
                                        <div class="summary-item d-flex justify-content-between py-3 border-bottom">
                                            <span class="text-muted">خصم الكمية:</span>
                                            <span class="text-success">- {{ number_format($order->quantity_discount, 2) }} ريال</span>
                                        </div>
                                        @endif
                                        @if($order->coupon_discount > 0)
                                        <div class="summary-item d-flex justify-content-between py-3 border-bottom">
                                            <span class="text-muted">خصم الكوبون:</span>
                                            <span class="text-success">- {{ number_format($order->coupon_discount, 2) }} ريال</span>
                                        </div>
                                        @if($order->coupon_code)
                                        <div class="summary-item d-flex justify-content-between py-3 border-bottom">
                                            <span class="text-muted">كود الخصم:</span>
                                            <span><span class="badge bg-primary">{{ $order->coupon_code }}</span></span>
                                        </div>
                                        @endif
                                        @endif
                                        <div class="summary-item d-flex justify-content-between py-3 bg-light rounded-3 mt-2 mb-2">
                                            <strong class="text-primary fs-5">إجمالي الطلب:</strong>
                                            <strong class="text-primary fs-5">{{ number_format($order->total_amount, 2) }} ريال</strong>
                                        </div>
                                    </div>

                                    @if($order->quantity_discount > 0 || $order->coupon_discount > 0)
                                    <div class="alert alert-info mt-3 mb-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        @if($order->quantity_discount > $order->coupon_discount)
                                        <span>تم تطبيق خصم الكمية ({{ number_format($order->quantity_discount, 2) }} ريال) لأنه أكبر من خصم الكوبون.</span>
                                        @elseif($order->coupon_discount > $order->quantity_discount)
                                        <span>تم تطبيق خصم الكوبون ({{ number_format($order->coupon_discount, 2) }} ريال) لأنه أكبر من خصم الكمية.</span>
                                        @elseif($order->coupon_discount == $order->quantity_discount && $order->coupon_discount > 0)
                                        <span>تم تطبيق خصم متساوٍ ({{ number_format($order->coupon_discount, 2) }} ريال) من كلا النوعين.</span>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Forms for Status Updates -->
<form id="update-status-form" action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="d-none">
    @csrf
    @method('PUT')
</form>

<form id="update-payment-status-form" action="{{ route('admin.orders.update-payment-status', $order) }}" method="POST" class="d-none">
    @csrf
    @method('PUT')
</form>
@endsection

@section('styles')
<link rel="stylesheet" href="/assets/css/admin/orders.css">
@endsection