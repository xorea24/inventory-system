<?php

namespace App\Http\Controllers;

use App\Models\Aisle;
use App\Models\Bin;
use App\Support\Code39Barcode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BinController extends Controller
{
    public function index(): View
    {
        $this->authorize('warehouse.view');

        $bins = Bin::with('aisle.zone.warehouse')->orderBy('name')->paginate(15);

        return view('bins.index', compact('bins'));
    }

    public function create(Request $request): View
    {
        $this->authorize('warehouse.manage');

        $bin = new Bin;
        if ($request->has('aisle_id')) {
            $bin->aisle_id = $request->integer('aisle_id');
        }

        return view('bins.create', [
            'bin' => $bin,
            'aisles' => Aisle::with('zone.warehouse')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('warehouse.manage');

        $bin = Bin::create($this->validateBin($request));

        activity('warehouse_locations')->causedBy($request->user())->performedOn($bin)->log('Bin created');

        return redirect()->route('bins.index')->with('success', "Bin {$bin->name} created successfully.");
    }

    public function edit(Bin $bin): View
    {
        $this->authorize('warehouse.manage');

        return view('bins.edit', [
            'bin' => $bin,
            'aisles' => Aisle::with('zone.warehouse')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Bin $bin): RedirectResponse
    {
        $this->authorize('warehouse.manage');

        $bin->update($this->validateBin($request, $bin));

        activity('warehouse_locations')->causedBy($request->user())->performedOn($bin)->log('Bin updated');

        return redirect()->route('bins.index')->with('success', "Bin {$bin->name} updated successfully.");
    }

    public function destroy(Request $request, Bin $bin): RedirectResponse
    {
        $this->authorize('warehouse.manage');

        $name = $bin->name;
        activity('warehouse_locations')->causedBy($request->user())->performedOn($bin)->log('Bin deleted');
        $bin->delete();

        return redirect()->route('bins.index')->with('success', "Bin {$name} deleted successfully.");
    }

    public function barcode(Bin $bin): Response
    {
        $this->authorize('warehouse.view');

        return response(Code39Barcode::svg($bin->barcode), 200, [
            'Content-Type' => 'image/svg+xml',
        ]);
    }

    /**
     * @return array{aisle_id: int, name: string}
     */
    private function validateBin(Request $request, ?Bin $bin = null): array
    {
        return $request->validate([
            'aisle_id' => ['required', 'integer', 'exists:aisles,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('bins')->where('aisle_id', $request->integer('aisle_id'))->ignore($bin),
            ],
        ]);
    }
}
