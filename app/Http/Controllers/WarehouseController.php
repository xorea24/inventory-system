<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarehouseController extends Controller
{
    public function index(): View
    {
        $this->authorize('warehouse.view');

        $warehouses = Warehouse::query()
            ->orderBy('name')
            ->paginate(15);

        return view('warehouses.index', compact('warehouses'));
    }

    public function create(): View
    {
        $this->authorize('warehouse.manage');

        return view('warehouses.create', [
            'warehouse' => new Warehouse,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('warehouse.manage');

        $warehouse = Warehouse::create($this->validateWarehouse($request));

        activity('warehouse_management')
            ->causedBy($request->user())
            ->performedOn($warehouse)
            ->log('Warehouse created');

        return redirect()
            ->route('warehouses.index')
            ->with('success', "Warehouse {$warehouse->name} created successfully.");
    }

    public function edit(Warehouse $warehouse): View
    {
        $this->authorize('warehouse.manage');

        return view('warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse): RedirectResponse
    {
        $this->authorize('warehouse.manage');

        $warehouse->update($this->validateWarehouse($request));

        activity('warehouse_management')
            ->causedBy($request->user())
            ->performedOn($warehouse)
            ->log('Warehouse updated');

        return redirect()
            ->route('warehouses.index')
            ->with('success', "Warehouse {$warehouse->name} updated successfully.");
    }

    public function destroy(Request $request, Warehouse $warehouse): RedirectResponse
    {
        $this->authorize('warehouse.manage');

        $name = $warehouse->name;

        activity('warehouse_management')
            ->causedBy($request->user())
            ->performedOn($warehouse)
            ->log('Warehouse deleted');

        $warehouse->delete();

        return redirect()
            ->route('warehouses.index')
            ->with('success', "Warehouse {$name} deleted successfully.");
    }

    /**
     * @return array{name: string, address?: string|null, country?: string|null}
     */
    private function validateWarehouse(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'country' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
