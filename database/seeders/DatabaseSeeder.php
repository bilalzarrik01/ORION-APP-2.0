<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = collect(['admin', 'editor', 'viewer'])->mapWithKeys(function (string $name) {
            $role = Role::firstOrCreate(['name' => $name]);

            return [$name => $role->id];
        });

        $admin = User::factory()->firstOrCreate([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $admin->roles()->syncWithoutDetaching([$roles['admin']]);
    }
}
