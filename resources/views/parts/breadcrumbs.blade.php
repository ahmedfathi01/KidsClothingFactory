<!-- Page Title -->
<section class="page-title bg-light py-4">
    <div class="container">
        <h1 class="mb-0">{{ $title ?? 'Page Title' }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $title ?? 'Page Title' }}</li>
            </ol>
        </nav>
    </div>
</section>
