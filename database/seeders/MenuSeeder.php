<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        Menu::firstOrCreate(
            ['libelle' => "Tableau de bord"],
            [
                'icone' => "ki-element-7",
                'target' => "dashboard",
                'position' => 1,
            ]
        );
        Menu::firstOrCreate(
            ['libelle' => "Caisses"],
            [
                'icone' => "ki-calendar-8",
                'target' => "cashs",
                'position' => 2,
            ]
        );
        Menu::firstOrCreate(
            ['libelle' => "Gestion de stock"],
            [
                'icone' => "ki-abstract-26",
                'target' => "products",
                'position' => 3,
            ]
        );
        Menu::firstOrCreate(
            ['libelle' => "Autres charges"],
            [
                'icone' => "ki-abstract-25",
                'target' => "category",
                'position' => 4,
            ]
        );
        Menu::firstOrCreate(
            ['libelle' => "Profils"],
            [
                'icone' => "ki-map",
                'target' => "profiles",
                'position' => 5,
            ]
        );
        Menu::firstOrCreate(
            ['libelle' => "Utilisateurs"],
            [
                'icone' => "ki-address-book",
                'target' => "users",
                'position' => 6,
            ]
        );
        Menu::firstOrCreate(
            ['libelle' => "Piste d'audit"],
            [
                'icone' => "ki-code",
                'target' => "logs",
                'position' => 7,
            ]
        );
    }
}
