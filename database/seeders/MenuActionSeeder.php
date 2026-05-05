<?php

namespace Database\Seeders;

use App\Models\MenuAction;
use Illuminate\Database\Seeder;

class MenuActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        // Tableau de bord
        MenuAction::firstOrCreate(
            [
                'menu_id' => 1,
                'action_id' => 1,
            ]
        );
        // Caisses
        MenuAction::firstOrCreate(
            [
                'menu_id' => 2,
                'action_id' => 1,
            ]
        );
        MenuAction::firstOrCreate(
            [
                'menu_id' => 2,
                'action_id' => 2,
            ]
        );
        MenuAction::firstOrCreate(
            [
                'menu_id' => 2,
                'action_id' => 3,
            ]
        );
        MenuAction::firstOrCreate(
            [
                'menu_id' => 2,
                'action_id' => 4,
            ]
        );
        MenuAction::firstOrCreate(
            [
                'menu_id' => 2,
                'action_id' => 6,
            ]
        );
        MenuAction::firstOrCreate(
            [
                'menu_id' => 2,
                'action_id' => 7,
            ]
        );
        MenuAction::firstOrCreate(
            [
                'menu_id' => 2,
                'action_id' => 8,
            ]
        );
        MenuAction::firstOrCreate(
            [
                'menu_id' => 2,
                'action_id' => 9,
            ]
        );
        // Gestion de stock
        MenuAction::firstOrCreate(
            [
                'menu_id' => 3,
                'action_id' => 1,
            ]
        );
        MenuAction::firstOrCreate(
            [
                'menu_id' => 3,
                'action_id' => 2,
            ]
        );
        MenuAction::firstOrCreate(
            [
                'menu_id' => 3,
                'action_id' => 3,
            ]
        );
        MenuAction::firstOrCreate(
            [
                'menu_id' => 3,
                'action_id' => 4,
            ]
        );
        MenuAction::firstOrCreate(
            [
                'menu_id' => 3,
                'action_id' => 5,
            ]
        );
        // Autres charges
        MenuAction::firstOrCreate(
            [
                'menu_id' => 4,
                'action_id' => 1,
            ]
        );
        MenuAction::firstOrCreate(
            [
                'menu_id' => 4,
                'action_id' => 2,
            ]
        );
        MenuAction::firstOrCreate(
            [
                'menu_id' => 4,
                'action_id' => 3,
            ]
        );
        MenuAction::firstOrCreate(
            [
                'menu_id' => 4,
                'action_id' => 4,
            ]
        );
        MenuAction::firstOrCreate(
            [
                'menu_id' => 4,
                'action_id' => 5,
            ]
        );
        // Gestion des Profils
        MenuAction::firstOrCreate(
            [
                'menu_id' => 5,
                'action_id' => 1,
            ]
        );
        MenuAction::firstOrCreate(
            [
                'menu_id' => 5,
                'action_id' => 2,
            ]
        );
        MenuAction::firstOrCreate(
            [
                'menu_id' => 5,
                'action_id' => 3,
            ]
        );
        MenuAction::firstOrCreate(
            [
                'menu_id' => 5,
                'action_id' => 4,
            ]
        );
        MenuAction::firstOrCreate(
            [
                'menu_id' => 5,
                'action_id' => 5,
            ]
        );
        // Gestion des Utilisateurs
        MenuAction::firstOrCreate(
            [
                'menu_id' => 6,
                'action_id' => 1,
            ]
        );
        MenuAction::firstOrCreate(
            [
                'menu_id' => 6,
                'action_id' => 2,
            ]
        );
        MenuAction::firstOrCreate(
            [
                'menu_id' => 6,
                'action_id' => 3,
            ]
        );
        MenuAction::firstOrCreate(
            [
                'menu_id' => 6,
                'action_id' => 4,
            ]
        );
        MenuAction::firstOrCreate(
            [
                'menu_id' => 6,
                'action_id' => 5,
            ]
        );
        // Piste d'audit
        MenuAction::firstOrCreate(
            [
                'menu_id' => 7,
                'action_id' => 1,
            ]
        );
    }
}
