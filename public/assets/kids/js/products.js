/**
 * Product Card Interactions
 * Handles product card hover effects, quick view, add to cart, and wishlist functionality
 */
document.addEventListener('DOMContentLoaded', function() {
    // Quick view functionality
    const quickViewButtons = document.querySelectorAll('.quickview-btn');
    quickViewButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            openQuickView(productId);
        });
    });

    // Add to cart functionality
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            addToCart(productId);
        });
    });

    // Wishlist functionality
    const wishlistButtons = document.querySelectorAll('.wishlist-btn');
    wishlistButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            toggleWishlist(productId, this);
        });
    });

    // تطبيق الألوان من خلال السمات
    const colorOptions = document.querySelectorAll('.color-option[data-color]');
    colorOptions.forEach(option => {
        const color = option.getAttribute('data-color');
        option.style.backgroundColor = color;
    });

    // تأثيرات حركية للمنتجات
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach((card, index) => {
        // تأخير بسيط للعناصر المختلفة
        card.style.animationDelay = (index * 0.1) + 's';
        card.classList.add('animate-in');
    });
});

/**
 * Open quick view modal for a product
 * @param {number} productId - The ID of the product to view
 */
function openQuickView(productId) {
    // This would typically make an AJAX request to get product details
    // and then display them in a modal
    console.log(`Opening quick view for product ${productId}`);

    // Example implementation:
    // fetch(`/api/products/${productId}/quick-view`)
    //     .then(response => response.json())
    //     .then(data => {
    //         // Display product details in a modal
    //         showQuickViewModal(data);
    //     })
    //     .catch(error => console.error('Error fetching product details:', error));
}

/**
 * Add a product to the cart
 * @param {number} productId - The ID of the product to add
 */
function addToCart(productId) {
    // Get selected color if any
    const productCard = document.querySelector(`.add-to-cart-btn[data-product-id="${productId}"]`).closest('.product-card');
    const selectedColor = productCard.querySelector('.color-option[data-selected="true"]');
    const colorName = selectedColor ? selectedColor.getAttribute('title') : null;

    // This would typically make an AJAX request to add the product to the cart
    console.log(`Adding product ${productId} to cart${colorName ? ` with color ${colorName}` : ''}`);

    // Example implementation:
    // fetch('/cart/add', {
    //     method: 'POST',
    //     headers: {
    //         'Content-Type': 'application/json',
    //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    //     },
    //     body: JSON.stringify({
    //         product_id: productId,
    //         color: colorName,
    //         quantity: 1
    //     })
    // })
    //     .then(response => response.json())
    //     .then(data => {
    //         // Update cart count in the UI
    //         updateCartCount(data.cart_count);
    //         // Show success message
    //         showNotification('تمت إضافة المنتج إلى السلة بنجاح', 'success');
    //     })
    //     .catch(error => {
    //         console.error('Error adding product to cart:', error);
    //         showNotification('حدث خطأ أثناء إضافة المنتج إلى السلة', 'error');
    //     });
}

/**
 * Toggle a product in the wishlist
 * @param {number} productId - The ID of the product to toggle
 * @param {HTMLElement} button - The wishlist button element
 */
function toggleWishlist(productId, button) {
    // This would typically make an AJAX request to toggle the product in the wishlist
    console.log(`Toggling product ${productId} in wishlist`);

    // Example implementation:
    // fetch('/wishlist/toggle', {
    //     method: 'POST',
    //     headers: {
    //         'Content-Type': 'application/json',
    //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    //     },
    //     body: JSON.stringify({
    //         product_id: productId
    //     })
    // })
    //     .then(response => response.json())
    //     .then(data => {
    //         // Toggle the active class on the button
    //         button.classList.toggle('active');
    //         // Show success message
    //         showNotification(data.message, 'success');
    //     })
    //     .catch(error => {
    //         console.error('Error toggling product in wishlist:', error);
    //         showNotification('حدث خطأ أثناء تحديث قائمة المفضلة', 'error');
    //     });
}
