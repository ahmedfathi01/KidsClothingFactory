<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $featuredProducts = Product::with(['category', 'images', 'colors'])
            ->where('is_available', true)
            ->take(4)
            ->get();

        foreach ($featuredProducts as $product) {
            if ($product->colors->isNotEmpty()) {
                $product->colors->transform(function ($color) {
                    if (!isset($color->color_code) || empty($color->color_code)) {
                        $color->color_code = $color->color ?? '#000000';
                    }
                    return $color;
                });
            }
        }

        $allCoupons = Coupon::where('is_active', 1)
            ->where('expires_at', '>', now())
            ->orderBy('expires_at', 'asc')
            ->get();

        $currentPage = $request->get('page', 1);
        $perPage = 2;
        $coupons = $allCoupons->forPage($currentPage, $perPage);
        $totalPages = ceil($allCoupons->count() / $perPage);

        $topCategories = Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->take(5)
            ->get();

        return view('index', compact(
            'featuredProducts',
            'allCoupons',
            'coupons',
            'currentPage',
            'totalPages',
            'topCategories'
        ));
    }
}
