<?php

namespace Database\Seeders;

use App\Models\Core\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => 'Super Admin']);

        $user = User::firstOrCreate([
            'email' => 'admin@kalansoft.com'
        ], [
            'nom' => 'Admin',
            'prenom' => 'Super',
            'password' => Hash::make('password'),
            'etat' => true,
        ]);

        $user->assignRole($role);
    }
}
