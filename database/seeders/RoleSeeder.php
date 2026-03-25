<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Full control over all system features including products, orders, reports, users, and gold price settings',
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Manage products, inventory, and orders',
            ],
            [
                'name' => 'Customer',
                'slug' => 'customer',
                'description' => 'Browse products, add to cart, place orders, and view order history',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
