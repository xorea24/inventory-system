<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\Zone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ZoneController extends Controller
{
    public function index(): View
    {
        $this->authorize('warehouse.view');

        $zones = Zone::with('warehouse')->orderBy('name')->paginate(15);

        return view('zones.index', compact('zones'));
    }

    public function create(Request $request): View
    {
        $this->authorize('warehouse.manage');

        $zone = new Zone;
        if ($request->has('warehouse_id')) {
            $zone->warehouse_id = $request->integer('warehouse_id');
        }

        return view('zones.create', [
            'zone' => $zone,
            'warehouses' => Warehouse::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('warehouse.manage');

        $zone = Zone::create($this->validateZone($request));

        activity('warehouse_locations')->causedBy($request->user())->performedOn($zone)->log('Zone created');

        return redirect()->route('zones.index')->with('success', "Zone {$zone->name} created successfully.");
    }

    public function edit(Zone $zone): View
    {
        $this->authorize('warehouse.manage');

        return view('zones.edit', [
            'zone' => $zone,
            'warehouses' => Warehouse::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Zone $zone): RedirectResponse
    {
        $this->authorize('warehouse.manage');

        $zone->update($this->validateZone($request, $zone));

        activity('warehouse_locations')->causedBy($request->user())->performedOn($zone)->log('Zone updated');

        return redirect()->route('zones.index')->with('success', "Zone {$zone->name} updated successfully.");
    }

    public function destroy(Request $request, Zone $zone): RedirectResponse
    {
        $this->authorize('warehouse.manage');

        $name = $zone->name;
        activity('warehouse_locations')->causedBy($request->user())->performedOn($zone)->log('Zone deleted');
        $zone->delete();

        return redirect()->route('zones.index')->with('success', "Zone {$name} deleted successfully.");
    }

    public function show(Zone $zone): View
    {
        $this->authorize('warehouse.view');

        $aisles = $zone->aisles()->with('zone.warehouse')->orderBy('name')->paginate(15);

        return view('zones.show', compact('zone', 'aisles'));
    }

    /**
     * @return array{warehouse_id: int, name: string}
     */
    private function validateZone(Request $request, ?Zone $zone = null): array
    {
        return $request->validate([
            'warehouse_id' => ['required', 'integer', 'exists:warehouses,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('zones')->where('warehouse_id', $request->integer('warehouse_id'))->ignore($zone),
            ],
        ]);
    }
}
