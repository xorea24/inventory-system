<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductVariantController extends Controller
{
    public function create(Product $product): View
    {
        $this->authorize('product.create');

        return view('product-variants.create', [
            'product' => $product,
            'variant' => new ProductVariant([
                'uom' => $product->uom,
                'unit_price' => $product->unit_price,
            ]),
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('product.create');

        $variant = $product->variants()->create($this->validateVariant($request));

        activity('product_management')
            ->causedBy($request->user())
            ->performedOn($variant)
            ->log('Product variant created');

        return redirect()
            ->route('products.show', $product)
            ->with('success', "Variant {$variant->name} created successfully.");
    }

    public function edit(Product $product, ProductVariant $variant): View
    {
        $this->authorize('product.edit');
        $this->ensureVariantBelongsToProduct($product, $variant);

        return view('product-variants.edit', compact('product', 'variant'));
    }

    public function update(Request $request, Product $product, ProductVariant $variant): RedirectResponse
    {
        $this->authorize('product.edit');
        $this->ensureVariantBelongsToProduct($product, $variant);

        $variant->update($this->validateVariant($request, $variant));

        activity('product_management')
            ->causedBy($request->user())
            ->performedOn($variant)
            ->log('Product variant updated');

        return redirect()
            ->route('products.show', $product)
            ->with('success', "Variant {$variant->name} updated successfully.");
    }

    public function destroy(Request $request, Product $product, ProductVariant $variant): RedirectResponse
    {
        $this->authorize('product.delete');
        $this->ensureVariantBelongsToProduct($product, $variant);

        $name = $variant->name;

        activity('product_management')
            ->causedBy($request->user())
            ->performedOn($variant)
            ->log('Product variant deleted');

        $variant->delete();

        return redirect()
            ->route('products.show', $product)
            ->with('success', "Variant {$name} deleted successfully.");
    }

    /**
     * @return array<string, mixed>
     */
    private function validateVariant(Request $request, ?ProductVariant $variant = null): array
    {
        return $request->validate([
            'sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('product_variants', 'sku')->ignore($variant),
            ],
            'barcode' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('product_variants', 'barcode')->ignore($variant),
            ],
            'uom' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'quantity' => ['required', 'integer', 'min:0'],
            'unit_price' => ['required', 'numeric', 'min:0'],
        ]);
    }

    private function ensureVariantBelongsToProduct(Product $product, ProductVariant $variant): void
    {
        abort_unless($variant->product_id === $product->id, 404);
    }
}
