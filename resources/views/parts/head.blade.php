<!-- Common Head Content -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="بر الليث - ملابس أطفال مميزة">
<meta name="keywords" content="ملابس أطفال, بر الليث, ملابس مميزة">
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Disable Lazy Loading CSS -->
<style>
    /* Fix for elements appearing only on scroll */
    body * {
        transition: none !important;
        animation: none !important;
        opacity: 1 !important;
        visibility: visible !important;
        transform: none !important;
    }
    .navbar, .footer, footer, header, .container, .navbar-brand img, .footer-links, .page-title {
        opacity: 1 !important;
        visibility: visible !important;
        transform: none !important;
    }
    /* Override any scroll-triggered animations */
    [data-aos], [data-scroll], .fade, .animate, .lazy-load, .animate__animated, .reveal, .wow {
        opacity: 1 !important;
        visibility: visible !important;
        transform: none !important;
        animation: none !important;
    }
</style>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Common CSS -->
<link rel="stylesheet" href="{{ asset('assets/kids/css/common.css') }}">
<!-- Favicon -->
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
<!-- Disable Lazy Loading JS -->
<script>
    // Run immediately
    (function() {
        // Disable any existing scroll event listeners that might trigger animations
        var originalAddEventListener = EventTarget.prototype.addEventListener;
        EventTarget.prototype.addEventListener = function(type, listener, options) {
            if (type === 'scroll' || type === 'DOMContentLoaded') {
                // Force elements visibility instead
                document.querySelectorAll('*').forEach(function(el) {
                    if (el.style) {
                        el.style.opacity = '1';
                        el.style.visibility = 'visible';
                        el.style.transform = 'none';
                    }
                });
                return;
            }
            return originalAddEventListener.call(this, type, listener, options);
        };

        // Force visibility when the script loads
        document.querySelectorAll('*').forEach(function(el) {
            if (el.style) {
                el.style.opacity = '1';
                el.style.visibility = 'visible';
                el.style.transform = 'none';
            }
        });
    })();

    // Also run on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function() {
        // Disable any AOS or similar animation libraries
        if (window.AOS) window.AOS.init = function() {};
        if (window.WOW) window.WOW = function() {};

        // Force all elements to be visible
        document.querySelectorAll('*').forEach(function(el) {
            if (el.style) {
                el.style.opacity = '1';
                el.style.visibility = 'visible';
                el.style.transform = 'none';
            }

            // Remove any potential data attributes that control animations
            ['data-aos', 'data-scroll', 'data-animate', 'data-wow'].forEach(function(attr) {
                if (el.hasAttribute(attr)) {
                    el.removeAttribute(attr);
                }
            });
        });
    });
</script>
