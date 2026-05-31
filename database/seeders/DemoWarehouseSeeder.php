<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use App\Models\Zone;
use App\Models\Aisle;
use App\Models\Bin;
use Illuminate\Database\Seeder;

class DemoWarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouse = Warehouse::firstOrCreate(
            ['name' => 'Main Distribution Center'],
            [
                'address' => '123 Logistics Way, Industrial Park',
                'country' => 'USA',
            ]
        );

        $zones = [
            'Zone A - Cold Storage',
            'Zone B - Dry Goods',
            'Zone C - Hazardous Materials',
        ];

        foreach ($zones as $zoneName) {
            $zone = Zone::firstOrCreate([
                'warehouse_id' => $warehouse->id,
                'name' => $zoneName,
            ]);

            for ($i = 1; $i <= 3; $i++) {
                $aisle = Aisle::firstOrCreate([
                    'zone_id' => $zone->id,
                    'name' => "Aisle {$i}",
                ]);

                for ($j = 1; $j <= 5; $j++) {
                    Bin::firstOrCreate([
                        'aisle_id' => $aisle->id,
                        'name' => "Bin {$j}",
                    ]);
                }
            }
        }
    }
}
