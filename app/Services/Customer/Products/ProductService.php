<?php

namespace App\Services\Customer\Products;

use App\Models\Category;
use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class ProductService
{
    public function getFilteredProducts(Request $request)
    {
        $query = Product::with(['category', 'images', 'categories'])
            ->where('is_available', true);

        // Filter by categories (single category or multiple categories)
        if ($request->has('categories') && !empty($request->categories)) {
            $categories = $request->categories;
            $query->where(function($q) use ($categories) {
                foreach ($categories as $categorySlug) {
                    // Primary category (belongs to)
                    $q->orWhereHas('category', function($query) use ($categorySlug) {
                        $query->where('slug', $categorySlug);
                    });

                    // Secondary categories (many-to-many)
                    $q->orWhereHas('categories', function($query) use ($categorySlug) {
                        $query->where('slug', $categorySlug);
                    });
                }
            });
        }
        // For backward compatibility - support single category parameter
        elseif ($request->has('category') && !empty($request->category)) {
            $categorySlug = $request->category;
            $query->where(function($q) use ($categorySlug) {
                $q->whereHas('category', function($query) use ($categorySlug) {
                    $query->where('slug', $categorySlug);
                })
                ->orWhereHas('categories', function($query) use ($categorySlug) {
                    $query->where('slug', $categorySlug);
                });
            });
        }

        if ($request->has('min_price')) {
            $query->whereHas('sizes', function($query) use ($request) {
                    $query->where('price', '>=', $request->min_price);
            });
        }

        if ($request->has('max_price')) {
            $query->whereHas('sizes', function($query) use ($request) {
                    $query->where('price', '<=', $request->max_price);
            });
        }

        if ($request->has('has_discounts') && $request->has_discounts) {
            $query->where(function($q) {
                $q->whereHas('discounts', function($query) {
                    $query->where('is_active', true)
                          ->where(function($q2) {
                               $q2->whereNull('expires_at')
                                  ->orWhere('expires_at', '>=', now());
                           })
                          ->where(function($q2) {
                               $q2->whereNull('starts_at')
                                  ->orWhere('starts_at', '<=', now());
                           });
                });

                $q->orWhereHas('quantityDiscounts', function($query) {
                    $query->where('is_active', true);
                });

                $q->orWhere(function($subQ) {
                    $subQ->whereHas('category', function($catQ) {
                        $catQ->whereHas('coupons', function($couponQ) {
                            $couponQ->where('is_active', true)
                                ->where(function($dateQ) {
                                    $dateQ->whereNull('expires_at')
                                        ->orWhere('expires_at', '>=', now());
                                })
                                ->where(function($dateQ) {
                                    $dateQ->whereNull('starts_at')
                                        ->orWhere('starts_at', '<=', now());
                                });
                        });
                    });
                });

                $q->orWhereExists(function($query) {
                    $query->select(\Illuminate\Support\Facades\DB::raw(1))
                          ->from('coupons')
                          ->where('applies_to_all_products', true)
                          ->where('is_active', true)
                          ->where(function($q2) {
                               $q2->whereNull('expires_at')
                                  ->orWhere('expires_at', '>=', now());
                           })
                          ->where(function($q2) {
                               $q2->whereNull('starts_at')
                                  ->orWhere('starts_at', '<=', now());
                           });
                });
            });
        }

        switch ($request->input('sort', 'newest')) {
            case 'price-low':
                $query->orderByRaw('(SELECT MIN(COALESCE(ps.price, 999999))
                                   FROM product_sizes ps
                                   WHERE ps.product_id = products.id) ASC');
                break;
            case 'price-high':
                $query->orderByRaw('(SELECT MAX(COALESCE(ps.price, 0))
                                   FROM product_sizes ps
                                   WHERE ps.product_id = products.id) DESC');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        return $query->paginate(9);
    }

    public function getCategories()
    {
        $categories = Category::select('id', 'name', 'slug')
            ->withCount(['products' => function($query) {
                $query->where('is_available', true);
            }])
            ->get();

        foreach ($categories as $category) {
            // Count products associated through the many-to-many relationship
            // but exclude those already counted through the direct relationship
            // to avoid duplicated counts
            $additionalProductsCount = DB::table('category_product')
                ->join('products', 'category_product.product_id', '=', 'products.id')
                ->where('category_product.category_id', $category->id)
                ->where('products.is_available', true)
                ->whereRaw('products.category_id != ?', [$category->id]) // Exclude direct category products
                ->count();

            // Set the total count - Direct products (products_count) + Related products
            $category->total_products_count = $category->products_count + $additionalProductsCount;

            // Display the total count instead of just direct products
            $category->products_count = $category->total_products_count;
        }

        return $categories;
    }

    public function getPriceRange()
    {
        $minPrice = DB::table('products as p')
            ->leftJoin('product_sizes as ps', 'p.id', '=', 'ps.product_id')
            ->where('p.is_available', true)
            ->min(DB::raw('COALESCE(ps.price, 0)'));

        $maxPrice = DB::table('products as p')
            ->leftJoin('product_sizes as ps', 'p.id', '=', 'ps.product_id')
            ->where('p.is_available', true)
            ->max(DB::raw('COALESCE(ps.price, 0)'));

        return [
            'min' => $minPrice ?: 0,
            'max' => $maxPrice ?: 0
        ];
    }

    public function formatProductsForJson($products)
    {
        return $products->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'category' => [
                    'id' => $product->category_id,
                    'name' => $product->category->name ?? null
                ],
                'categories' => $product->categories->map(function($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name
                    ];
                }),
                'image' => $product->image_url,
                'all_images' => $product->all_images,
                'price_range' => $product->getPriceRange(),
                'rating' => 4.5,
                'reviews' => mt_rand(10, 100),
                'url' => route('products.show', $product->slug),
                'coupon_badge' => $this->getProductCouponBadge($product)
            ];
        });
    }

    public function formatProductsForFilter($products)
    {
        return $products->map(function($product) {
            try {
                $images = $product->images->map(function($image) {
                    return [
                        'id' => $image->id,
                        'image_path' => $image->image_path
                    ];
                })->values()->toArray();

                $priceRange = $product->getPriceRange();

                // Get both primary category and associated categories
                $allCategories = collect([$product->category])->filter()->merge($product->categories);

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'description' => Str::limit($product->description, 100),
                    'category' => $product->category->name ?? null,
                    'category_id' => $product->category_id,
                    'categories' => $allCategories->unique('id')->map(function($category) {
                        if (!$category) return null;
                        return [
                            'id' => $category->id,
                            'name' => $category->name,
                            'slug' => $category->slug ?? null
                        ];
                    })->filter()->values()->toArray(),
                    'image_url' => $product->image_url,
                    'images' => $images,
                    'price' => $priceRange['min'] ?? 0,
                    'min_price' => $priceRange['min'] ?? 0,
                    'max_price' => $priceRange['max'] ?? 0,
                    'price_range' => [
                        'min' => $priceRange['min'] ?? 0,
                        'max' => $priceRange['max'] ?? 0
                    ],
                    'rating' => 4.5,
                    'reviews' => mt_rand(10, 100),
                    'url' => route('products.show', $product->slug),
                    'coupon_badge' => $this->getProductCouponBadge($product)
                ];
            } catch (\Exception $e) {
                return [
                    'id' => $product->id ?? 0,
                    'name' => $product->name ?? 'منتج غير معروف',
                    'slug' => $product->slug ?? 'unknown-product',
                    'description' => Str::limit($product->description ?? '', 100),
                    'category' => null,
                    'category_id' => null,
                    'categories' => [],
                    'price_range' => ['min' => 0, 'max' => 0],
                    'image_url' => asset('images/placeholder.jpg'),
                    'images' => [],
                    'rating' => 0,
                    'reviews' => 0
                ];
            }
        });
    }

    public function getProductCouponBadge($product)
    {
        $availableCoupons = $product->getAvailableCoupons();

        if ($availableCoupons->isEmpty()) {
            return null;
        }

        $bestCoupon = null;
        $highestDiscount = 0;

        foreach ($availableCoupons as $coupon) {
            $price = $product->min_price;
            if ($price <= 0) {
                continue;
            }

            $discountAmount = 0;

            if ($coupon->type === 'percentage') {
                $discountAmount = ($price * $coupon->value) / 100;
            } else {
                $discountAmount = $coupon->value;
            }

            if ($discountAmount > $highestDiscount) {
                $highestDiscount = $discountAmount;
                $bestCoupon = $coupon;
            }
        }

        if (!$bestCoupon) {
            return null;
        }

        $badgeText = '';
        if ($bestCoupon->type === 'percentage') {
            $badgeText = "خصم {$bestCoupon->value}%";
        } else {
            $badgeText = "خصم {$bestCoupon->value} ر.س";
        }

        return [
            'code' => $bestCoupon->code,
            'discount_text' => $badgeText,
            'value' => $bestCoupon->value,
            'type' => $bestCoupon->type,
            'badge_html' => '<div class="coupon-badge position-absolute"><span class="badge bg-danger"><i class="fas fa-tag me-1"></i>' . $badgeText . '</span><small class="d-block mt-1 text-white bg-dark px-1 rounded">كود: ' . $bestCoupon->code . '</small></div>'
        ];
    }

    public function getProductDetails(Product $product)
    {
        $priceRange = $product->getPriceRange();

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'description' => $product->description,
            'price_range' => [
                'min' => $priceRange['min'],
                'max' => $priceRange['max']
            ],
            'category' => $product->category->name,
            'image_url' => $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : asset('images/placeholder.jpg'),
            'images' => collect($product->images)->map(function($image) {
                return asset('storage/' . $image->image_path);
            })->toArray(),
            'colors' => $product->allow_color_selection ? collect($product->colors)->map(function($color) {
                return [
                    'name' => $color->color,
                    'is_available' => $color->is_available
                ];
            })->toArray() : [],
            'sizes' => $product->allow_size_selection ? collect($product->sizes)->map(function($size) {
                return [
                    'name' => $size->size,
                    'is_available' => $size->is_available,
                    'price' => $size->price
                ];
            })->toArray() : [],
            'is_available' => $product->stock > 0,
            'features' => [
                'allow_custom_color' => $product->allow_custom_color,
                'allow_custom_size' => $product->allow_custom_size,
                'allow_color_selection' => $product->allow_color_selection,
                'allow_size_selection' => $product->allow_size_selection,
            ]
        ];
    }

    public function getRelatedProducts(Product $product)
    {
        return Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_available', true)
            ->with(['category', 'images'])
            ->take(4)
            ->get();
    }

    public function getAvailableFeatures(Product $product)
    {
        $features = [];

        // الألوان
        if ($product->enable_color_selection && $product->colors->isNotEmpty()) {
            $features['colors'] = $product->colors->where('is_available', true)->pluck('color')->toArray();
        }

        // المقاسات
        if ($product->enable_size_selection && $product->sizes->isNotEmpty()) {
            $sizes = $product->sizes->where('is_available', true)->map(function($size) {
                $sizeData = [
                    'size' => $size->size
            ];

                if ($size->price) {
                    $sizeData['price'] = $size->price;
                }

                return $sizeData;
            })->toArray();

            $features['sizes'] = $sizes;
        }

        // إضافة الخيارات الأخرى
        $features['allow_custom_color'] = $product->enable_custom_color;
        $features['allow_custom_size'] = $product->enable_custom_size;
        $features['has_discount'] = $product->hasDiscounts();

        return $features;
    }
}
