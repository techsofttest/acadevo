<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Size;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $sizes = [

            ['name' => 'Small', 'type' => 'clothing'],
            ['name' => 'Medium', 'type' => 'clothing'],
            ['name' => 'Large', 'type' => 'clothing'],
            ['name' => '6 mm', 'type' => 'jewellery'],
            ['name' => '7 mm', 'type' => 'jewellery'],

        ];

        foreach ($sizes as $size) {
            Size::create($size);
        }

    }
}
