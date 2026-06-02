<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (['product.view', 'product.create', 'product.edit', 'product.delete'] as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }

    #[Test]
    public function an_authorized_user_can_create_update_and_delete_a_product(): void
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['product.view', 'product.create', 'product.edit', 'product.delete']);

        $this->actingAs($user)
            ->post(route('products.store'), $this->productPayload([
                'sku' => 'PRD-001',
                'barcode' => '100000000001',
                'uom' => 'box',
                'name' => 'Boxed Gloves',
            ]))
            ->assertRedirect();

        $product = Product::query()->where('sku', 'PRD-001')->firstOrFail();

        $this->assertSame('100000000001', $product->barcode);
        $this->assertSame('box', $product->uom);

        $this->actingAs($user)
            ->put(route('products.update', $product), $this->productPayload([
                'sku' => 'PRD-001-A',
                'barcode' => '100000000002',
                'uom' => 'case',
                'name' => 'Updated Gloves',
            ]))
            ->assertRedirect(route('products.show', $product));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'sku' => 'PRD-001-A',
            'barcode' => '100000000002',
            'uom' => 'case',
            'name' => 'Updated Gloves',
        ]);

        $this->actingAs($user)
            ->delete(route('products.destroy', $product))
            ->assertRedirect(route('products.index'));

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    #[Test]
    public function an_authorized_user_can_create_update_and_delete_a_product_variant(): void
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['product.view', 'product.create', 'product.edit', 'product.delete']);

        $product = Product::create($this->productPayload([
            'sku' => 'PRD-002',
            'barcode' => '200000000001',
            'name' => 'Safety Vest',
        ]));

        $this->actingAs($user)
            ->post(route('products.variants.store', $product), $this->variantPayload([
                'sku' => 'PRD-002-RED',
                'barcode' => '200000000002',
                'uom' => 'pc',
                'name' => 'Safety Vest - Red',
            ]))
            ->assertRedirect(route('products.show', $product));

        $variant = ProductVariant::query()->where('sku', 'PRD-002-RED')->firstOrFail();

        $this->assertSame($product->id, $variant->product_id);

        $this->actingAs($user)
            ->put(route('products.variants.update', [$product, $variant]), $this->variantPayload([
                'sku' => 'PRD-002-BLU',
                'barcode' => '200000000003',
                'uom' => 'pack',
                'name' => 'Safety Vest - Blue',
            ]))
            ->assertRedirect(route('products.show', $product));

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'sku' => 'PRD-002-BLU',
            'barcode' => '200000000003',
            'uom' => 'pack',
            'name' => 'Safety Vest - Blue',
        ]);

        $this->actingAs($user)
            ->delete(route('products.variants.destroy', [$product, $variant]))
            ->assertRedirect(route('products.show', $product));

        $this->assertDatabaseMissing('product_variants', [
            'id' => $variant->id,
        ]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function productPayload(array $overrides = []): array
    {
        return array_merge([
            'sku' => 'PRD-DEFAULT',
            'barcode' => null,
            'uom' => 'pcs',
            'name' => 'Default Product',
            'description' => null,
            'category' => 'General',
            'quantity' => 10,
            'reorder_level' => 2,
            'unit_price' => 25.50,
            'supplier' => 'Default Supplier',
        ], $overrides);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function variantPayload(array $overrides = []): array
    {
        return array_merge([
            'sku' => 'VAR-DEFAULT',
            'barcode' => null,
            'uom' => 'pcs',
            'name' => 'Default Variant',
            'description' => null,
            'quantity' => 3,
            'unit_price' => 30.25,
        ], $overrides);
    }
}
