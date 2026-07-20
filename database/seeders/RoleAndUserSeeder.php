<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Core\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Rôles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $professeurRole = Role::firstOrCreate(['name' => 'professeur']);
        $parentRole = Role::firstOrCreate(['name' => 'parent']);
        $eleveRole = Role::firstOrCreate(['name' => 'eleve']);

        // Utilisateurs
        $admin = User::firstOrCreate(
            ['email' => 'admin@kalansoft.com'],
            ['prenom' => 'Admin', 'nom' => 'System', 'password' => Hash::make('password')]
        );
        $admin->assignRole($adminRole);
        if (!$admin->personnel) {
            $admin->personnel()->create([
                'matricule' => 'ADM-001',
                'fonction' => 'Administrateur Système',
                'type_personnel' => 'Administration'
            ]);
        }

        $professeur = User::firstOrCreate(
            ['email' => 'professeur@kalansoft.com'],
            ['prenom' => 'Professeur', 'nom' => 'Test', 'password' => Hash::make('password')]
        );
        $professeur->assignRole($professeurRole);
        if (!$professeur->personnel) {
            $professeur->personnel()->create([
                'matricule' => 'PROF-001',
                'fonction' => 'Professeur Principal',
                'type_personnel' => 'Enseignant'
            ]);
        }

        $parent = User::firstOrCreate(
            ['email' => 'parent@kalansoft.com'],
            ['prenom' => 'Parent', 'nom' => 'Test', 'password' => Hash::make('password')]
        );
        $parent->assignRole($parentRole);
        if (!$parent->parentEleve) {
            $parent->parentEleve()->create([
                'profession' => 'Ingénieur',
                'adresse' => 'Quartier ACI 2000, Bamako'
            ]);
        }

        $eleve = User::firstOrCreate(
            ['email' => 'eleve@kalansoft.com'],
            ['prenom' => 'Eleve', 'nom' => 'Test', 'password' => Hash::make('password')]
        );
        $eleve->assignRole($eleveRole);
        if (!$eleve->eleve) {
            $eleve->eleve()->create([
                'matricule' => 'ELV-001',
                'date_naissance' => '2010-05-15',
                'lieu_naissance' => 'Bamako',
                'nom_tuteur' => 'Parent Test',
                'telephone_tuteur' => '12345678'
            ]);
        }
    }
}
