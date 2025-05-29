<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images', 'sizes', 'categories'])
            ->withCount('orderItems');

        // Filter by specific product
        if ($request->product) {
            $query->where('id', $request->product);
        }

        // Filter by category
        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by search term
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        // Handle sorting
        switch ($request->sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'price_high':
                // Order by the maximum price of sizes
                $query->orderBy(function ($q) {
                    return $q->select(DB::raw('MAX(COALESCE(ps.price, 0))'))
                        ->from('products as p')
                        ->leftJoin('product_sizes as ps', 'p.id', '=', 'ps.product_id')
                        ->whereColumn('p.id', 'products.id')
                        ->limit(1);
                }, 'desc');
                break;
            case 'price_low':
                // Order by the minimum price of sizes
                $query->orderBy(function ($q) {
                    return $q->select(DB::raw('MIN(COALESCE(ps.price, 0))'))
                        ->from('products as p')
                        ->leftJoin('product_sizes as ps', 'p.id', '=', 'ps.product_id')
                        ->whereColumn('p.id', 'products.id')
                        ->limit(1);
                });
                break;
            default:
                $query->latest(); // 'newest' is default
                break;
        }

        $products = $query->paginate(15);
        $categories = Category::all();
        $allProducts = Product::orderBy('name')->get(); // Get all products sorted by name

        return view('admin.products.index', compact('products', 'categories', 'allProducts'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Basic validation rules that are always required
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg',
            'is_primary.*' => 'boolean',
            'is_available' => 'boolean',
            'enable_custom_color' => 'boolean',
            'enable_custom_size' => 'boolean',
            'enable_color_selection' => 'boolean',
            'enable_size_selection' => 'boolean',
        ];

        // Add color validation rules only if colors are enabled
        if ($request->has('has_colors')) {
            $rules['colors'] = 'required|array|min:1';
            $rules['colors.*'] = 'required|string|max:255';
            $rules['color_available'] = 'array';
            $rules['color_available.*'] = 'boolean';
        }

        // Add size validation rules only if sizes are enabled
        if ($request->has('has_sizes')) {
            $rules['sizes'] = 'required|array|min:1';
            $rules['sizes.*'] = 'required|string|max:255';
            $rules['size_ids.*'] = 'nullable|exists:product_sizes,id';
            $rules['size_available.*'] = 'nullable|boolean';
            $rules['size_prices.*'] = 'nullable|numeric|min:0';
        }

        $validatedData = $request->validate($rules);

        try {
            DB::beginTransaction();

            // Auto-generate slug from name if not provided
            if (empty($request->slug)) {
                $validatedData['slug'] = $this->generateSlugFromName($request->name);
            } else {
                $validatedData['slug'] = $this->generateUniqueSlug($request->slug);
            }

            // Set default values for feature flags
            $validatedData['enable_custom_color'] = $request->has('enable_custom_color');
            $validatedData['enable_custom_size'] = $request->has('enable_custom_size');
            $validatedData['enable_color_selection'] = $request->has('enable_color_selection');
            $validatedData['enable_size_selection'] = $request->has('enable_size_selection');
            $validatedData['is_available'] = $request->has('is_available');

            $product = Product::create($validatedData);

            // إضافة التصنيفات الإضافية إذا تم توفيرها
            if ($request->has('categories')) {
                $product->categories()->attach($request->categories);
            }

            // Store colors if enabled
            if ($request->has('has_colors') && $request->has('colors')) {
                foreach ($request->colors as $index => $color) {
                    if (!empty($color)) {
                        $product->colors()->create([
                            'color' => $color,
                            'is_available' => $request->color_available[$index] ?? true
                        ]);
                    }
                }
            }

            // Store sizes if enabled
            if ($request->enable_size_selection && isset($request->sizes)) {
                foreach ($request->sizes as $index => $size) {
                    if (!empty($size)) {
                        $price = null;
                        if (isset($request->size_prices[$index]) && !empty($request->size_prices[$index])) {
                            $price = $request->size_prices[$index];
                        }

                        $product->sizes()->create([
                            'size' => $size,
                            'price' => $price,
                            'is_available' => isset($request->size_available[$index]) ? 1 : 0
                        ]);
                    }
                }
            }

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $this->uploadFile($image, 'products');
                    $product->images()->create([
                        'image_path' => $path,
                        'is_primary' => $request->input('is_primary.' . $index, false)
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')
                ->with('success', 'تم إضافة المنتج بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'فشل إضافة المنتج. ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Product $product)
    {
        $product->load(['images', 'colors', 'sizes', 'categories']);
        $categories = Category::all();
        $selectedCategories = $product->categories->pluck('id')->toArray();
        return view('admin.products.edit', compact('product', 'categories', 'selectedCategories'));
    }

    public function update(Request $request, Product $product)
    {
        try {
            // Basic validation rules
            $rules = [
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'categories' => 'nullable|array',
                'categories.*' => 'exists:categories,id',
                'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg',
                'is_primary' => 'nullable|exists:product_images,id',
                'is_primary_new.*' => 'nullable|boolean',
                'remove_images.*' => 'nullable|exists:product_images,id',
                'enable_custom_color' => 'boolean',
                'enable_custom_size' => 'boolean',
                'enable_color_selection' => 'boolean',
                'enable_size_selection' => 'boolean',
            ];

            // Add color validation rules only if colors are enabled
            if ($request->has('has_colors')) {
                $rules['colors'] = 'required|array|min:1';
                $rules['colors.*'] = 'required|string|max:255';
                $rules['color_ids.*'] = 'nullable|exists:product_colors,id';
                $rules['color_available.*'] = 'nullable|boolean';
            }

            // Add size validation rules only if sizes are enabled
            if ($request->has('has_sizes')) {
                $rules['sizes'] = 'required|array|min:1';
                $rules['sizes.*'] = 'required|string|max:255';
                $rules['size_ids.*'] = 'nullable|exists:product_sizes,id';
                $rules['size_available.*'] = 'nullable|boolean';
                $rules['size_prices.*'] = 'nullable|numeric|min:0';
            }

            $validated = $request->validate($rules);

            DB::beginTransaction();

            // Auto-generate slug from name if not provided
            if (empty($request->slug)) {
                $validated['slug'] = $this->generateSlugFromName($request->name, $product->id);
            } else if ($validated['slug'] !== $product->slug) {
                $validated['slug'] = $this->generateUniqueSlug($validated['slug'], 1, $product->id);
            }

            $product->update([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'],
                'category_id' => $validated['category_id'],
                'enable_custom_color' => $request->has('enable_custom_color'),
                'enable_custom_size' => $request->has('enable_custom_size'),
                'enable_color_selection' => $request->has('enable_color_selection'),
                'enable_size_selection' => $request->has('enable_size_selection'),
                'is_available' => $request->has('is_available'),
            ]);

            // تحديث التصنيفات الإضافية
            $product->categories()->sync($request->categories ?? []);

            // Handle colors
            if ($request->has('has_colors')) {
                // Delete colors that are not in the new list
                $currentColorIds = $product->colors->pluck('id')->toArray();
                $updatedColorIds = array_filter($request->color_ids ?? []);
                $deletedColorIds = array_diff($currentColorIds, $updatedColorIds);

                if (!empty($deletedColorIds)) {
                    $product->colors()->whereIn('id', $deletedColorIds)->delete();
                }

                // Update or create colors
                foreach ($request->colors as $index => $colorName) {
                    if (!empty($colorName)) {
                        $colorId = $request->color_ids[$index] ?? null;
                        $colorData = [
                            'color' => $colorName,
                            'is_available' => $request->color_available[$index] ?? true
                        ];

                        if ($colorId && in_array($colorId, $currentColorIds)) {
                            $product->colors()->where('id', $colorId)->update($colorData);
                        } else {
                            $product->colors()->create($colorData);
                        }
                    }
                }
            } else {
                $product->colors()->delete();
            }

            // Handle sizes
            if ($request->has('has_sizes')) {
                // Delete sizes that are not in the new list
                $currentSizeIds = $product->sizes->pluck('id')->toArray();
                $updatedSizeIds = array_filter($request->size_ids ?? []);
                $deletedSizeIds = array_diff($currentSizeIds, $updatedSizeIds);

                if (!empty($deletedSizeIds)) {
                    $product->sizes()->whereIn('id', $deletedSizeIds)->delete();
                }

                // Update or create sizes
                foreach ($request->sizes as $index => $sizeName) {
                    if (!empty($sizeName)) {
                        $sizeId = $request->size_ids[$index] ?? null;
                        $sizeData = [
                            'size' => $sizeName,
                            'is_available' => $request->size_available[$index] ?? true
                        ];

                        // Add price to size data if provided
                        if (isset($request->size_prices[$index])) {
                            $sizeData['price'] = $request->size_prices[$index];
                        }

                        if ($sizeId && in_array($sizeId, $currentSizeIds)) {
                            $product->sizes()->where('id', $sizeId)->update($sizeData);
                        } else {
                            $product->sizes()->create($sizeData);
                        }
                    }
                }
            } else {
                $product->sizes()->delete();
            }

            // Handle image removals
            if ($request->has('remove_images')) {
                foreach ($request->remove_images as $imageId) {
                    $image = $product->images()->find($imageId);
                    if ($image) {
                        $this->deleteFile($image->image_path);
                        $image->delete();
                    }
                }
            }

            // Handle new images
            if ($request->hasFile('new_images')) {
                foreach ($request->file('new_images') as $index => $image) {
                    $path = $this->uploadFile($image, 'products');
                    $product->images()->create([
                        'image_path' => $path,
                        'is_primary' => $request->input('is_primary_new.' . $index, false)
                    ]);
                }
            }

            // Update primary image
            if ($request->has('is_primary')) {
                $product->images()->update(['is_primary' => false]);
                $product->images()->where('id', $request->is_primary)->update(['is_primary' => true]);
            }

            DB::commit();
            return redirect()->route('admin.products.index')
                ->with('success', 'تم تحديث المنتج بنجاح');
        } catch (\Exception $e) {
            Log::error('Product update error: ' . $e->getMessage(), [
                'product_id' => $product->id,
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            DB::rollBack();
            return back()->withInput()
                ->with('error', 'فشل تحديث المنتج. ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            // Delete all associated records first
            $product->colors()->delete();
            $product->sizes()->delete();
            $product->orderItems()->delete();
            // Detach relations
            $product->discounts()->detach();
            $product->categories()->detach();

            // Delete all associated images and their files
            foreach ($product->images as $image) {
                $this->deleteFile($image->image_path);
                $image->delete();
            }

            // Finally delete the product
            $product->delete();

            DB::commit();
            return redirect()->route('admin.products.index')
                ->with('success', 'تم حذف المنتج بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'فشل حذف المنتج. ' . $e->getMessage());
        }
    }

    public function show(Product $product)
    {
        $product->load(['category', 'images', 'colors', 'sizes', 'categories']);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Delete a file from storage
     *
     * @param string $path
     * @return bool
     */
    protected function deleteFile($path)
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }

    protected function getValidationRules(): array
    {
        return [
            // ... existing validation rules ...
            'enable_custom_color' => 'boolean',
            'enable_custom_size' => 'boolean',
            'enable_color_selection' => 'boolean',
            'enable_size_selection' => 'boolean',
        ];
    }

    protected function prepareForValidation($data)
    {
        // Convert checkbox values to boolean
        $checkboxFields = [
            'enable_custom_color',
            'enable_custom_size',
            'enable_color_selection',
            'enable_size_selection'
        ];

        foreach ($checkboxFields as $field) {
            $data[$field] = isset($data[$field]) && $data[$field] === 'on';
        }

        return $data;
    }

    /**
     * Generate a slug from the product name
     *
     * @param string $name
     * @param int|null $excludeId
     * @return string
     */
    protected function generateSlugFromName($name, $excludeId = null)
    {
        $slug = Str::slug($name, '-');

        // Check for Arabic text that won't be properly handled by Str::slug
        if (empty($slug)) {
            // Create a custom slug for Arabic text
            $slug = preg_replace('/\s+/', '-', $name);
            $slug = preg_replace('/[^\p{L}\p{N}\-]/u', '', $slug);
            $slug = mb_strtolower($slug, 'UTF-8');
        }

        return $this->generateUniqueSlug($slug, 1, $excludeId);
    }

    /**
     * Generate a unique slug that doesn't exist in the database
     *
     * @param string $slug
     * @param int $counter
     * @param int|null $excludeId
     * @return string
     */
    protected function generateUniqueSlug($slug, $counter = 1, $excludeId = null)
    {
        $originalSlug = $slug;
        $query = Product::where('slug', $slug);

        // Exclude the current product when updating
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
            $query = Product::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }
}
