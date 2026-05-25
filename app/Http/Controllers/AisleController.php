<?php

namespace App\Http\Controllers;

use App\Models\Aisle;
use App\Models\Zone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AisleController extends Controller
{
    public function index(): View
    {
        $this->authorize('warehouse.view');

        $aisles = Aisle::with('zone.warehouse')->orderBy('name')->paginate(15);

        return view('aisles.index', compact('aisles'));
    }

    public function create(): View
    {
        $this->authorize('warehouse.manage');

        return view('aisles.create', [
            'aisle' => new Aisle,
            'zones' => Zone::with('warehouse')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('warehouse.manage');

        $aisle = Aisle::create($this->validateAisle($request));

        activity('warehouse_locations')->causedBy($request->user())->performedOn($aisle)->log('Aisle created');

        return redirect()->route('aisles.index')->with('success', "Aisle {$aisle->name} created successfully.");
    }

    public function edit(Aisle $aisle): View
    {
        $this->authorize('warehouse.manage');

        return view('aisles.edit', [
            'aisle' => $aisle,
            'zones' => Zone::with('warehouse')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Aisle $aisle): RedirectResponse
    {
        $this->authorize('warehouse.manage');

        $aisle->update($this->validateAisle($request, $aisle));

        activity('warehouse_locations')->causedBy($request->user())->performedOn($aisle)->log('Aisle updated');

        return redirect()->route('aisles.index')->with('success', "Aisle {$aisle->name} updated successfully.");
    }

    public function destroy(Request $request, Aisle $aisle): RedirectResponse
    {
        $this->authorize('warehouse.manage');

        $name = $aisle->name;
        activity('warehouse_locations')->causedBy($request->user())->performedOn($aisle)->log('Aisle deleted');
        $aisle->delete();

        return redirect()->route('aisles.index')->with('success', "Aisle {$name} deleted successfully.");
    }

    /**
     * @return array{zone_id: int, name: string}
     */
    private function validateAisle(Request $request, ?Aisle $aisle = null): array
    {
        return $request->validate([
            'zone_id' => ['required', 'integer', 'exists:zones,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('aisles')->where('zone_id', $request->integer('zone_id'))->ignore($aisle),
            ],
        ]);
    }
}
