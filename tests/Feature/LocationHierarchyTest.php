<?php

namespace Tests\Feature;

use App\Models\Warehouse;
use App\Models\Zone;
use App\Models\Aisle;
use App\Models\Bin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationHierarchyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_warehouse_has_many_zones()
    {
        $warehouse = Warehouse::create([
            'name' => 'Main Warehouse',
            'address' => '123 Main St',
            'country' => 'US',
        ]);

        $zone = Zone::create([
            'warehouse_id' => $warehouse->id,
            'name' => 'Zone A',
        ]);

        $this->assertTrue($warehouse->zones->contains($zone));
        $this->assertEquals($warehouse->id, $zone->warehouse->id);
    }

    /** @test */
    public function a_zone_has_many_aisles()
    {
        $warehouse = Warehouse::create([
            'name' => 'Main Warehouse',
            'address' => '123 Main St',
            'country' => 'US',
        ]);

        $zone = Zone::create([
            'warehouse_id' => $warehouse->id,
            'name' => 'Zone B',
        ]);

        $aisle = Aisle::create([
            'zone_id' => $zone->id,
            'name' => 'Aisle 1',
        ]);

        $this->assertTrue($zone->aisles->contains($aisle));
        $this->assertEquals($zone->id, $aisle->zone->id);
    }

    /** @test */
    public function an_aisle_has_many_bins()
    {
        $warehouse = Warehouse::create([
            'name' => 'Main Warehouse',
            'address' => '123 Main St',
            'country' => 'US',
        ]);

        $zone = Zone::create([
            'warehouse_id' => $warehouse->id,
            'name' => 'Zone C',
        ]);

        $aisle = Aisle::create([
            'zone_id' => $zone->id,
            'name' => 'Aisle 2',
        ]);

        $bin = Bin::create([
            'aisle_id' => $aisle->id,
            'name' => 'Bin 001',
            'barcode' => 'BIN-20230601-ABC123',
        ]);

        $this->assertTrue($aisle->bins->contains($bin));
        $this->assertEquals($aisle->id, $bin->aisle->id);
    }
}
