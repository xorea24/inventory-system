<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            // Users
            'user.view', 'user.create', 'user.edit', 'user.deactivate',

            // Products and stock
            'product.view', 'product.create', 'product.edit', 'product.delete',
            'stock.view', 'stock.adjust',

            // Inbound / purchase orders
            'purchase_order.view', 'purchase_order.create',
            'purchase_order.edit', 'purchase_order.receive',

            // Outbound / sales orders
            'sales_order.view', 'sales_order.create',
            'sales_order.edit', 'sales_order.dispatch',

            // Transfers
            'transfer.view', 'transfer.create', 'transfer.confirm',

            // Warehouses and locations
            'warehouse.view', 'warehouse.manage',

            // Reports
            'report.view', 'report.export',

            // Settings
            'settings.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $admin = $this->role('Admin');
        $admin->syncPermissions(Permission::where('guard_name', 'web')->get());

        $manager = $this->role('Manager');
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

        $staff = $this->role('Staff');
        $staff->syncPermissions([
            'product.view',
            'stock.view', 'stock.adjust',
            'purchase_order.view', 'purchase_order.receive',
            'sales_order.view', 'sales_order.dispatch',
            'transfer.view', 'transfer.confirm',
            'warehouse.view',
            'report.view',
        ]);

        $viewer = $this->role('Viewer');
        $viewer->syncPermissions([
            'product.view',
            'stock.view',
            'purchase_order.view',
            'sales_order.view',
            'transfer.view',
            'warehouse.view',
            'report.view',
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->command->info('Roles and permissions seeded successfully.');
        $this->command->table(
            ['Role', 'Permissions Count'],
            [
                ['Admin', $admin->permissions->count()],
                ['Manager', $manager->permissions->count()],
                ['Staff', $staff->permissions->count()],
                ['Viewer', $viewer->permissions->count()],
            ]
        );
    }

    private function role(string $name): Role
    {
        $role = Role::query()
            ->where('guard_name', 'web')
            ->whereRaw('LOWER(name) = ?', [strtolower($name)])
            ->first();

        if ($role) {
            $role->update(['name' => $name]);

            return $role;
        }

        return Role::create([
            'name' => $name,
            'guard_name' => 'web',
        ]);
    }
}
