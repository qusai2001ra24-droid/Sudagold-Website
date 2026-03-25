<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (Role::count() === 0) {
            $this->call([
                RoleSeeder::class,
                PermissionSeeder::class,
            ]);
        }

        $this->call([
            ProductSeeder::class,
        ]);

        if (User::count() === 0) {
            $admin = User::factory()->create([
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@sudagold.com',
            ]);
            $admin->role()->associate(Role::where('slug', 'admin')->first());
            $admin->save();

            $manager = User::factory()->create([
                'first_name' => 'Manager',
                'last_name' => 'User',
                'email' => 'manager@sudagold.com',
            ]);
            $manager->role()->associate(Role::where('slug', 'manager')->first());
            $manager->save();

            $customer = User::factory()->create([
                'first_name' => 'Customer',
                'last_name' => 'User',
                'email' => 'customer@example.com',
            ]);
            $customer->role()->associate(Role::where('slug', 'customer')->first());
            $customer->save();
        }
    }
}
