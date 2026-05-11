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
                'status' => true,
                'type_id' => 1,
            ]
        );
        Category::firstOrCreate(
            ['libelle' => "Dépenses"],
            [
                'status' => true,
                'type_id' => 2,
            ]
        );
        Category::firstOrCreate(
            ['libelle' => "Approvisionnement"],
            [
                'status' => true,
                'type_id' => 1,
            ]
        );
    }
}
