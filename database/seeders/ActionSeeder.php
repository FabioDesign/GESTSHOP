<?php

namespace Database\Seeders;

use App\Models\Action;
use Illuminate\Database\Seeder;

class ActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        Action::firstOrCreate(
            ['libelle' => "Voir"]
        );
        Action::firstOrCreate(
            ['libelle' => "Ajouter"]
        );
        Action::firstOrCreate(
            ['libelle' => "Modifier"]
        );
        Action::firstOrCreate(
            ['libelle' => "Supprimer"]
        );
        Action::firstOrCreate(
            ['libelle' => "Activer/Désactiver"]
        );
        Action::firstOrCreate(
            ['libelle' => "Transmettre"]
        );
        Action::firstOrCreate(
            ['libelle' => "Valider"]
        );
        Action::firstOrCreate(
            ['libelle' => "Rejeter"]
        );
        Action::firstOrCreate(
            ['libelle' => "Imprimer"]
        );
    }
}
