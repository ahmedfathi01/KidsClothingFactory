<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use App\Services\Customer\Products\ProductService;
use App\Services\Customer\Products\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected $productService;
    protected $cartService;

    public function __construct(ProductService $productService, CartService $cartService)
    {
        $this->productService = $productService;
        $this->cartService = $cartService;
    }

    public function index(Request $request)
    {
        $products = $this->productService->getFilteredProducts($request);
        $categories = $this->productService->getCategories();
        $priceRange = $this->productService->getPriceRange();

        if ($request->ajax()) {
            return response()->json([
                'products' => $this->productService->formatProductsForJson($products),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total()
                ]
            ]);
        }

        return view('products.index', compact('products', 'categories', 'priceRange'));
    }

    public function show(Product $product)
    {
        if (!$product->is_available) {
            abort(404, 'المنتج غير متوفر حالياً');
        }

        $product->load(['category', 'images', 'colors', 'sizes', 'quantityDiscounts']);

        $availableFeatures = $this->productService->getAvailableFeatures($product);
        $relatedProducts = $this->productService->getRelatedProducts($product);
        $quantityDiscounts = $product->quantityDiscounts()->where('is_active', true)->orderBy('min_quantity')->get();

        return view('products.show', compact(
            'product',
            'relatedProducts',
            'availableFeatures',
            'quantityDiscounts'
        ));
    }

    public function filter(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'categories' => 'nullable|array',
                'categories.*' => 'nullable|string',
                'minPrice' => 'nullable|numeric|min:0',
                'maxPrice' => 'nullable|numeric|min:0',
                'sort' => 'nullable|string|in:newest,price-low,price-high',
                'has_discounts' => 'nullable|boolean'
            ]);

            $request->merge([
                'min_price' => $validatedData['minPrice'] ?? null,
                'max_price' => $validatedData['maxPrice'] ?? null,
                'sort' => $validatedData['sort'] ?? 'newest',
                'has_discounts' => $validatedData['has_discounts'] ?? null,
                'categories' => $validatedData['categories'] ?? []
            ]);

            if (!empty($validatedData['categories'])) {
                $request->merge(['category' => $validatedData['categories'][0]]);
            }

            $products = $this->productService->getFilteredProducts($request);

            return response()->json([
                'success' => true,
                'products' => $this->productService->formatProductsForFilter($products),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث المنتجات - ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getProductDetails(Product $product)
    {
        if (!$product->is_available) {
            return response()->json([
                'success' => false,
                'message' => 'المنتج غير متوفر حالياً'
            ], 404);
        }

        $product->load(['category', 'images', 'colors', 'sizes']);

        return response()->json($this->productService->getProductDetails($product));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'color' => 'nullable|string|max:50',
            'size' => 'nullable|string|max:50'
        ]);

        $result = $this->cartService->addToCart($request);

        if (!$result['success']) {
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message']
            ], $result['status']);
        }

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'cart_count' => $result['cart_count'],
            'cart_total' => $result['cart_total'],
            'product_name' => $result['product_name'],
            'product_id' => $result['product_id'],
            'cart_item_id' => $result['cart_item_id']
        ]);
    }

    public function getCartItems(Request $request)
    {
        return response()->json($this->cartService->getCartItems($request));
    }

    public function updateCartItem(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $result = $this->cartService->updateCartItem($cartItem, $request->quantity);

        return response()->json($result);
    }

    public function removeCartItem(CartItem $cartItem)
    {
        try {
            $result = $this->cartService->removeCartItem($cartItem);

            if (!$result['success']) {
                return response()->json([
                    'success' => $result['success'],
                    'message' => $result['message']
                ], $result['status'] ?? 403);
            }

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المنتج'
            ], 500);
        }
    }
}
