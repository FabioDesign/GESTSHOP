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
            ['libelle' => "Demandes"],
            [
                'icone' => "ki-calendar-8",
                'target' => "demands",
                'position' => 2,
            ]
        );
        Menu::firstOrCreate(
            ['libelle' => "Documents"],
            [
                'icone' => "ki-abstract-26",
                'target' => "documents",
                'position' => 3,
            ]
        );
        Menu::firstOrCreate(
            ['libelle' => "Pièces à fournir"],
            [
                'icone' => "ki-abstract-25",
                'target' => "files",
                'position' => 4,
            ]
        );
        Menu::firstOrCreate(
            ['libelle' => "Villes"],
            [
                'icone' => "ki-bank",
                'target' => "towns",
                'position' => 5,
            ]
        );
        Menu::firstOrCreate(
            ['libelle' => "Profils"],
            [
                'icone' => "ki-map",
                'target' => "profiles",
                'position' => 6,
            ]
        );
        Menu::firstOrCreate(
            ['libelle' => "Utilisateurs"],
            [
                'icone' => "ki-address-book",
                'target' => "users",
                'position' => 7,
            ]
        );
        Menu::firstOrCreate(
            ['libelle' => "Piste d'audit"],
            [
                'icone' => "ki-code",
                'target' => "logs",
                'position' => 8,
            ]
        );
    }
}
