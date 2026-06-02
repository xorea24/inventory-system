<?php

namespace Tests\Feature;

use App\Imports\ProductsImport;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductImportTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_imports_new_products_and_updates_existing_products_by_sku(): void
    {
        Product::create([
            'sku' => 'SKU-001',
            'name' => 'Old name',
            'quantity' => 1,
        ]);

        $import = new ProductsImport;

        $import->collection(new Collection([
            new Collection([
                'sku' => 'SKU-001',
                'name' => 'Updated product',
                'description' => 'Existing item',
                'category' => 'Parts',
                'quantity' => 12,
                'reorder_level' => 4,
                'unit_price' => 99.95,
                'supplier' => 'Acme',
            ]),
            new Collection([
                'sku' => 'SKU-002',
                'name' => 'New product',
                'description' => null,
                'category' => 'Tools',
                'quantity' => 5,
                'reorder_level' => 2,
                'unit_price' => 15.5,
                'supplier' => null,
            ]),
        ]));

        $this->assertSame(1, $import->createdCount());
        $this->assertSame(1, $import->updatedCount());

        $this->assertDatabaseHas('products', [
            'sku' => 'SKU-001',
            'name' => 'Updated product',
            'quantity' => 12,
        ]);

        $this->assertDatabaseHas('products', [
            'sku' => 'SKU-002',
            'name' => 'New product',
            'quantity' => 5,
        ]);
    }

    #[Test]
    public function it_rejects_invalid_rows_before_writing_any_products(): void
    {
        $import = new ProductsImport;

        try {
            $import->collection(new Collection([
                new Collection([
                    'sku' => 'SKU-001',
                    'name' => 'Valid product',
                    'description' => null,
                    'category' => null,
                    'quantity' => 1,
                    'reorder_level' => 1,
                    'unit_price' => 1,
                    'supplier' => null,
                ]),
                new Collection([
                    'sku' => null,
                    'name' => 'Invalid product',
                    'description' => null,
                    'category' => null,
                    'quantity' => 1,
                    'reorder_level' => 1,
                    'unit_price' => 1,
                    'supplier' => null,
                ]),
            ]));
        } catch (ValidationException $exception) {
            $this->assertDatabaseCount('products', 0);
            $this->assertStringContainsString('Row 3', $exception->errors()['file'][0]);

            return;
        }

        $this->fail('Expected product import validation to fail.');
    }

    #[Test]
    public function it_preserves_existing_optional_values_when_optional_columns_are_omitted(): void
    {
        Product::create([
            'sku' => 'SKU-001',
            'name' => 'Old name',
            'category' => 'Original',
            'quantity' => 8,
        ]);

        $import = new ProductsImport;

        $import->collection(new Collection([
            new Collection([
                'sku' => 'SKU-001',
                'name' => 'Updated name',
            ]),
        ]));

        $this->assertDatabaseHas('products', [
            'sku' => 'SKU-001',
            'name' => 'Updated name',
            'category' => 'Original',
            'quantity' => 8,
        ]);
    }
}
