<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Products
            ['name' => 'View Products', 'slug' => 'view_products', 'group' => 'Products'],
            ['name' => 'Create Products', 'slug' => 'create_products', 'group' => 'Products'],
            ['name' => 'Edit Products', 'slug' => 'edit_products', 'group' => 'Products'],
            ['name' => 'Delete Products', 'slug' => 'delete_products', 'group' => 'Products'],

            // Categories
            ['name' => 'View Categories', 'slug' => 'view_categories', 'group' => 'Categories'],
            ['name' => 'Create Categories', 'slug' => 'create_categories', 'group' => 'Categories'],
            ['name' => 'Edit Categories', 'slug' => 'edit_categories', 'group' => 'Categories'],
            ['name' => 'Delete Categories', 'slug' => 'delete_categories', 'group' => 'Categories'],

            // Inventory
            ['name' => 'View Inventory', 'slug' => 'view_inventory', 'group' => 'Inventory'],
            ['name' => 'Manage Inventory', 'slug' => 'manage_inventory', 'group' => 'Inventory'],
            ['name' => 'View Inventory Movements', 'slug' => 'view_inventory_movements', 'group' => 'Inventory'],

            // Orders
            ['name' => 'View Orders', 'slug' => 'view_orders', 'group' => 'Orders'],
            ['name' => 'Create Orders', 'slug' => 'create_orders', 'group' => 'Orders'],
            ['name' => 'Edit Orders', 'slug' => 'edit_orders', 'group' => 'Orders'],
            ['name' => 'Cancel Orders', 'slug' => 'cancel_orders', 'group' => 'Orders'],
            ['name' => 'Process Orders', 'slug' => 'process_orders', 'group' => 'Orders'],

            // Gold Prices
            ['name' => 'View Gold Prices', 'slug' => 'view_gold_prices', 'group' => 'Gold Prices'],
            ['name' => 'Manage Gold Prices', 'slug' => 'manage_gold_prices', 'group' => 'Gold Prices'],

            // Users
            ['name' => 'View Users', 'slug' => 'view_users', 'group' => 'Users'],
            ['name' => 'Create Users', 'slug' => 'create_users', 'group' => 'Users'],
            ['name' => 'Edit Users', 'slug' => 'edit_users', 'group' => 'Users'],
            ['name' => 'Delete Users', 'slug' => 'delete_users', 'group' => 'Users'],
            ['name' => 'Manage Roles', 'slug' => 'manage_roles', 'group' => 'Users'],

            // Reports
            ['name' => 'View Reports', 'slug' => 'view_reports', 'group' => 'Reports'],
            ['name' => 'Generate Reports', 'slug' => 'generate_reports', 'group' => 'Reports'],

            // Dashboard
            ['name' => 'View Dashboard', 'slug' => 'view_dashboard', 'group' => 'Dashboard'],
            ['name' => 'View Admin Dashboard', 'slug' => 'view_admin_dashboard', 'group' => 'Dashboard'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        $adminRole = Role::where('slug', 'admin')->first();
        $managerRole = Role::where('slug', 'manager')->first();
        $customerRole = Role::where('slug', 'customer')->first();

        $adminPermissions = Permission::pluck('id')->toArray();
        $adminRole->permissions()->attach($adminPermissions);

        $managerPermissions = Permission::whereIn('slug', [
            'view_products',
            'create_products',
            'edit_products',
            'view_categories',
            'create_categories',
            'edit_categories',
            'view_inventory',
            'manage_inventory',
            'view_inventory_movements',
            'view_orders',
            'create_orders',
            'edit_orders',
            'cancel_orders',
            'process_orders',
            'view_gold_prices',
            'view_dashboard',
        ])->pluck('id')->toArray();
        $managerRole->permissions()->attach($managerPermissions);

        $customerPermissions = Permission::whereIn('slug', [
            'view_products',
            'create_orders',
            'view_orders',
            'view_dashboard',
        ])->pluck('id')->toArray();
        $customerRole->permissions()->attach($customerPermissions);
    }
}
