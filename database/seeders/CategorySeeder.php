<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        Category::firstOrCreate(
            ['libelle' => "Ventes"],
            [
                'status' => 1,
                'type_id' => 1,
            ]
        );
        Category::firstOrCreate(
            ['libelle' => "Dépenses"],
            [
                'status' => 1,
                'type_id' => 2,
            ]
        );
        Category::firstOrCreate(
            ['libelle' => "Approvisionnement"],
            [
                'status' => 1,
                'type_id' => 1,
            ]
        );
    }
}
