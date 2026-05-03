<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void{
        try {
            $this->call([
                // Action
                ActionSeeder::class,
                // Menu-Action
                MenuActionSeeder::class,
                // Menu
                MenuSeeder::class,
                // Permission
                PermissionSeeder::class,
                // Profil
                ProfileSeeder::class,
                // Utilisateur
                UserSeeder::class,
            ]);
        } catch (QueryException $e) {
            $this->command->info("Erreur d'insertion détectée. Processus de seed ignoré pour cet enregistrement.");
        }
    }
}
