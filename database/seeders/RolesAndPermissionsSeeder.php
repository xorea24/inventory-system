<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ─── Define all permissions ───────────────────────────────────────
        $permissions = [

            // Users
            'user.view', 'user.create', 'user.edit', 'user.deactivate',

            // Products & Stock
            'product.view', 'product.create', 'product.edit', 'product.delete',
            'stock.view', 'stock.adjust',

            // Inbound / Purchase Orders
            'purchase_order.view', 'purchase_order.create',
            'purchase_order.edit', 'purchase_order.receive',

            // Outbound / Sales Orders
            'sales_order.view', 'sales_order.create',
            'sales_order.edit', 'sales_order.dispatch',

            // Transfers
            'transfer.view', 'transfer.create', 'transfer.confirm',

            // Warehouses & Locations
            'warehouse.view', 'warehouse.manage',

            // Reports
            'report.view', 'report.export',

            // Settings
            'settings.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ─── Admin ────────────────────────────────────────────────────────
        // Full access to everything
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        // ─── Manager ──────────────────────────────────────────────────────
        // Can do everything except settings & user deactivation
        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $manager->syncPermissions([
            'user.view', 'user.create', 'user.edit',
            'product.view', 'product.create', 'product.edit',
            'stock.view', 'stock.adjust',
            'purchase_order.view', 'purchase_order.create',
            'purchase_order.edit', 'purchase_order.receive',
            'sales_order.view', 'sales_order.create',
            'sales_order.edit', 'sales_order.dispatch',
            'transfer.view', 'transfer.create', 'transfer.confirm',
            'warehouse.view',
            'report.view', 'report.export',
        ]);

        // ─── Staff ────────────────────────────────────────────────────────
        // Day-to-day warehouse operations only
        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $staff->syncPermissions([
            'product.view',
            'stock.view', 'stock.adjust',
            'purchase_order.view', 'purchase_order.receive',
            'sales_order.view', 'sales_order.dispatch',
            'transfer.view', 'transfer.confirm',
            'warehouse.view',
            'report.view',
        ]);

        // ─── Viewer ───────────────────────────────────────────────────────
        // Read-only access — cannot modify anything
        $viewer = Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web']);
        $viewer->syncPermissions([
            'product.view',
            'stock.view',
            'purchase_order.view',
            'sales_order.view',
            'transfer.view',
            'warehouse.view',
            'report.view',
        ]);

        $this->command->info('✅ Roles and permissions seeded successfully!');
        $this->command->table(
            ['Role', 'Permissions Count'],
            [
                ['admin',   Permission::count()],
                ['manager', $manager->permissions->count()],
                ['staff',   $staff->permissions->count()],
                ['viewer',  $viewer->permissions->count()],
            ]
        );
    }
}