<?php

namespace App\Http\Controllers;

use App\Imports\ProductsImport;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index(): View
    {
        $this->authorize('product.view');

        $products = Product::query()
            ->withCount('variants')
            ->orderBy('name')
            ->paginate(15);

        return view('products.index', compact('products'));
    }

    public function create(): View
    {
        $this->authorize('product.create');

        return view('products.create', [
            'product' => new Product(['uom' => 'pcs']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('product.create');

        $product = Product::create($this->validateProduct($request));

        activity('product_management')
            ->causedBy($request->user())
            ->performedOn($product)
            ->log('Product created');

        return redirect()
            ->route('products.show', $product)
            ->with('success', "Product {$product->name} created successfully.");
    }

    public function show(Product $product): View
    {
        $this->authorize('product.view');

        $variants = $product->variants()
            ->orderBy('name')
            ->paginate(15);

        return view('products.show', compact('product', 'variants'));
    }

    public function edit(Product $product): View
    {
        $this->authorize('product.edit');

        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('product.edit');

        $product->update($this->validateProduct($request, $product));

        activity('product_management')
            ->causedBy($request->user())
            ->performedOn($product)
            ->log('Product updated');

        return redirect()
            ->route('products.show', $product)
            ->with('success', "Product {$product->name} updated successfully.");
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('product.delete');

        $name = $product->name;

        activity('product_management')
            ->causedBy($request->user())
            ->performedOn($product)
            ->log('Product deleted');

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', "Product {$name} deleted successfully.");
    }

    public function import(Request $request): RedirectResponse
    {
        $this->authorize('product.create');

        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv,txt', 'max:10240'],
        ]);

        $import = new ProductsImport;

        try {
            Excel::import($import, $validated['file']);
        } catch (ValidationException $exception) {
            throw $exception;
        }

        activity('product_management')
            ->causedBy($request->user())
            ->withProperties([
                'created' => $import->createdCount(),
                'updated' => $import->updatedCount(),
            ])
            ->log('Products imported');

        return redirect()
            ->route('products.index')
            ->with('success', "Products imported successfully. Created {$import->createdCount()}, updated {$import->updatedCount()}.");
    }

    /**
     * @return array<string, mixed>
     */
    private function validateProduct(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'sku')->ignore($product),
            ],
            'barcode' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products', 'barcode')->ignore($product),
            ],
            'uom' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:0'],
            'reorder_level' => ['required', 'integer', 'min:0'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'supplier' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
