<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        // Tableau de bord
        Permission::firstOrCreate(
            [
                'menu_id' => 1,
                'action_id' => 1,
                'profile_id' => 1,
            ]
        );
        // Caisses
        Permission::firstOrCreate(
            [
                'menu_id' => 2,
                'action_id' => 1,
                'profile_id' => 1,
            ]
        );
        Permission::firstOrCreate(
            [
                'menu_id' => 2,
                'action_id' => 2,
                'profile_id' => 1,
            ]
        );
        Permission::firstOrCreate(
            [
                'menu_id' => 2,
                'action_id' => 3,
                'profile_id' => 1,
            ]
        );
        Permission::firstOrCreate(
            [
                'menu_id' => 2,
                'action_id' => 4,
                'profile_id' => 1,
            ]
        );
        Permission::firstOrCreate(
            [
                'menu_id' => 2,
                'action_id' => 6,
                'profile_id' => 1,
            ]
        );
        Permission::firstOrCreate(
            [
                'menu_id' => 2,
                'action_id' => 7,
                'profile_id' => 1,
            ]
        );
        Permission::firstOrCreate(
            [
                'menu_id' => 2,
                'action_id' => 8,
                'profile_id' => 1,
            ]
        );
        Permission::firstOrCreate(
            [
                'menu_id' => 2,
                'action_id' => 9,
                'profile_id' => 1,
            ]
        );
        // Gestion de stock
        Permission::firstOrCreate(
            [
                'menu_id' => 3,
                'action_id' => 1,
                'profile_id' => 1,
            ]
        );
        // Produits
        Permission::firstOrCreate(
            [
                'menu_id' => 4,
                'action_id' => 1,
                'profile_id' => 1,
            ]
        );
        Permission::firstOrCreate(
            [
                'menu_id' => 4,
                'action_id' => 2,
                'profile_id' => 1,
            ]
        );
        Permission::firstOrCreate(
            [
                'menu_id' => 4,
                'action_id' => 3,
                'profile_id' => 1,
            ]
        );
        Permission::firstOrCreate(
            [
                'menu_id' => 4,
                'action_id' => 4,
                'profile_id' => 1,
            ]
        );
        Permission::firstOrCreate(
            [
                'menu_id' => 4,
                'action_id' => 5,
                'profile_id' => 1,
            ]
        );
        // Categories
        Permission::firstOrCreate(
            [
                'menu_id' => 5,
                'action_id' => 1,
                'profile_id' => 1,
            ]
        );
        Permission::firstOrCreate(
            [
                'menu_id' => 5,
                'action_id' => 2,
                'profile_id' => 1,
            ]
        );
        Permission::firstOrCreate(
            [
                'menu_id' => 5,
                'action_id' => 3,
                'profile_id' => 1,
            ]
        );
        Permission::firstOrCreate(
            [
                'menu_id' => 5,
                'action_id' => 4,
                'profile_id' => 1,
            ]
        );
        Permission::firstOrCreate(
            [
                'menu_id' => 5,
                'action_id' => 5,
                'profile_id' => 1,
            ]
        );
        // Gestion des Profils
        Permission::firstOrCreate(
            [
                'menu_id' => 6,
                'action_id' => 1,
                'profile_id' => 1,
            ]
        );
        Permission::firstOrCreate(
            [
                'menu_id' => 6,
                'action_id' => 2,
                'profile_id' => 1,
            ]
        );
        Permission::firstOrCreate(
            [
                'menu_id' => 6,
                'action_id' => 3,
                'profile_id' => 1,
            ]
        );
        Permission::firstOrCreate(
            [
                'menu_id' => 6,
                'action_id' => 4,
                'profile_id' => 1,
            ]
        );
        Permission::firstOrCreate(
            [
                'menu_id' => 6,
                'action_id' => 5,
                'profile_id' => 1,
            ]
        );
        // Gestion des Utilisateurs
        Permission::firstOrCreate(
            [
                'menu_id' => 7,
                'action_id' => 1,
                'profile_id' => 1,
            ]
        );
        Permission::firstOrCreate(
            [
                'menu_id' => 7,
                'action_id' => 2,
                'profile_id' => 1,
            ]
        );
        Permission::firstOrCreate(
            [
                'menu_id' => 7,
                'action_id' => 3,
                'profile_id' => 1,
            ]
        );
        Permission::firstOrCreate(
            [
                'menu_id' => 7,
                'action_id' => 4,
                'profile_id' => 1,
            ]
        );
        Permission::firstOrCreate(
            [
                'menu_id' => 7,
                'action_id' => 5,
                'profile_id' => 1,
            ]
        );
        // Piste d'audit
        Permission::firstOrCreate(
            [
                'menu_id' => 8,
                'action_id' => 1,
                'profile_id' => 1,
            ]
        );
    }
}
