<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class LandlordTenantSeeder extends Seeder
{
    /**
     * Seed the landlord database with local development tenants.
     */
    public function run(): void
    {
        collect([
            ['name' => 'Local Inventory', 'domain' => 'localhost'],
            ['name' => 'Local Inventory IP', 'domain' => '127.0.0.1'],
        ])->each(function (array $tenant): void {
            Tenant::updateOrCreate([
                'domain' => $tenant['domain'],
            ], [
                'name' => $tenant['name'],
                'database' => 'inventory_system',
            ]);
        });
    }
}
