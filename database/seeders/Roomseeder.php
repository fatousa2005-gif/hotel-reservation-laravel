<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\RoomCategory;
class Roomseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = RoomCategory::all();

        $rooms = [
            ['number' => '101', 'name' => 'chambre standard 101', 'description' => 'chambre calme au premier etage.','price' => 25000, 'capacity' => 2,'status' => 'disponible','category' => 'standard'],
            ['number' => '102', 'name' => 'chambre standard 102', 'description' => 'chambre lumineuse avec balcon.','price' => 27000, 'capacity' => 2,'status' => 'disponible','category' => 'standard'],
            ['number' => '201', 'name' => 'chambre Deluxe 201', 'description' => 'Vue sur la piscine.','price' => 45000, 'capacity' => 3,'status' => 'disponible','category' => 'Deluxe'],
            ['number' => '301', 'name' => 'suite 301', 'description' => 'suite avec salon et coin bureau.','price' => 75000, 'capacity' => 4,'status' => 'disponible','category' => 'suite'],
            ['number' => '401', 'name' => 'suite presidantielle 401', 'description' => 'suite avec jacuzzi et terrasse privee.','price' => 150000, 'capacity' => 4,'status' => 'disponible','category' => 'suite presidentielle'],
        ];

        foreach ($rooms as $roomData) {
            $category = $categories->firstWhere('name',$roomData['category']);

            Room::create([
              'room_category_id' => $category->id,
              'number' => $roomData['number'],
              'name' => $roomData['name'],
              'description' => $roomData['description'],
              'price' => $roomData['price'],
              'capacity' => $roomData['capacity'],
              'status' => $roomData['status']
            ]);
        }
    }
}
