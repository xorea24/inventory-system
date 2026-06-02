<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToCollection, WithHeadingRow
{
    private int $created = 0;

    private int $updated = 0;

    public function collection(Collection $rows): void
    {
        $errors = [];
        $validatedRows = [];

        foreach ($rows as $index => $row) {
            $data = $this->normalizeRow($row);

            if ($this->isEmptyRow($data)) {
                continue;
            }

            $validator = Validator::make($data, [
                'sku' => ['required', 'string', 'max:255'],
                'barcode' => ['nullable', 'string', 'max:255'],
                'uom' => ['nullable', 'string', 'max:255'],
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'category' => ['nullable', 'string', 'max:255'],
                'quantity' => ['nullable', 'integer', 'min:0'],
                'reorder_level' => ['nullable', 'integer', 'min:0'],
                'unit_price' => ['nullable', 'numeric', 'min:0'],
                'supplier' => ['nullable', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                $errors[$index + 2] = $validator->errors()->all();

                continue;
            }

            $validatedRows[] = $validator->validated();
        }

        if ($errors !== []) {
            throw ValidationException::withMessages([
                'file' => collect($errors)
                    ->map(fn (array $messages, int $row) => 'Row '.$row.': '.implode(' ', $messages))
                    ->all(),
            ]);
        }

        foreach ($validatedRows as $data) {
            $product = Product::query()->where('sku', $data['sku'])->first();

            Product::query()->updateOrCreate(
                ['sku' => $data['sku']],
                $this->productAttributes($data)
            );

            $product ? $this->updated++ : $this->created++;
        }
    }

    public function createdCount(): int
    {
        return $this->created;
    }

    public function updatedCount(): int
    {
        return $this->updated;
    }

    /**
     * @return array<string, mixed>
     */
    private function normalizeRow(Collection $row): array
    {
        $data = [
            'sku' => $this->cleanValue($row->get('sku')),
            'name' => $this->cleanValue($row->get('name')),
        ];

        foreach (['barcode', 'uom', 'description', 'category', 'quantity', 'reorder_level', 'unit_price', 'supplier'] as $column) {
            if ($row->has($column)) {
                $data[$column] = $this->cleanValue($row->get($column));
            }
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function productAttributes(array $data): array
    {
        $attributes = [
            'name' => $data['name'],
        ];

        foreach (['barcode', 'uom', 'description', 'category', 'quantity', 'reorder_level', 'unit_price', 'supplier'] as $column) {
            if (array_key_exists($column, $data)) {
                $attributes[$column] = $data[$column];
            }
        }

        return $attributes;
    }

    private function cleanValue(mixed $value): mixed
    {
        if (is_string($value)) {
            $value = trim($value);

            return $value === '' ? null : $value;
        }

        return $value;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function isEmptyRow(array $data): bool
    {
        foreach ($data as $value) {
            if ($value !== null && $value !== '') {
                return false;
            }
        }

        return true;
    }
}
