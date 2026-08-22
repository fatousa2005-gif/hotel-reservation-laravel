<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoomCategory;


class RoomCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
           ['name' => 'standard', 'description'=> 'chambre confortable avec les equipements essentiels.'],
           ['name' => 'Deluxe', 'description'=> 'chambre spacieuse avec vue et equipements haut de gamme.'],
           ['name' => 'suite', 'description'=> 'suite luxueuse avec salon separe.'],
           ['name' => 'suite presidentielle', 'description'=> 'le summum du luxe, avec services exclusifs.'],
        ];

        foreach ($categories as $category){
            RoomCategory::create($category);
        }
    }
}
