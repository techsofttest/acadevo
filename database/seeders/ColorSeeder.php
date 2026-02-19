<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Color;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        


      $colors = [
    // Basic
    ["name" => "White", "Code" => "#FFFFFF"],
    ["name" => "Black", "Code" => "#000000"],
    ["name" => "Gray", "Code" => "#808080"],
    ["name" => "Light Gray", "Code" => "#D3D3D3"],
    ["name" => "Dark Gray", "Code" => "#505050"],

    // Reds
    ["name" => "Red", "Code" => "#FF0000"],
    ["name" => "Light Red", "Code" => "#FF6666"],
    ["name" => "Dark Red", "Code" => "#8B0000"],
    ["name" => "Maroon", "Code" => "#800000"],

    // Blues
    ["name" => "Blue", "Code" => "#0000FF"],
    ["name" => "Light Blue", "Code" => "#ADD8E6"],
    ["name" => "Sky Blue", "Code" => "#87CEEB"],
    ["name" => "Dark Blue", "Code" => "#00008B"],
    ["name" => "Navy", "Code" => "#000080"],

    // Greens
    ["name" => "Green", "Code" => "#008000"],
    ["name" => "Light Green", "Code" => "#90EE90"],
    ["name" => "Dark Green", "Code" => "#006400"],
    ["name" => "Lime", "Code" => "#00FF00"],
    ["name" => "Olive", "Code" => "#808000"],
    ["name" => "Mint", "Code" => "#98FF98"],

    // Yellows / Oranges
    ["name" => "Yellow", "Code" => "#FFFF00"],
    ["name" => "Light Yellow", "Code" => "#FFFFE0"],
    ["name" => "Gold", "Code" => "#FFD700"],
    ["name" => "Orange", "Code" => "#FFA500"],
    ["name" => "Dark Orange", "Code" => "#FF8C00"],
    ["name" => "Peach", "Code" => "#FFDAB9"],

    // Purples / Pinks
    ["name" => "Purple", "Code" => "#800080"],
    ["name" => "Violet", "Code" => "#EE82EE"],
    ["name" => "Indigo", "Code" => "#4B0082"],
    ["name" => "Lavender", "Code" => "#E6E6FA"],
    ["name" => "Magenta", "Code" => "#FF00FF"],
    ["name" => "Pink", "Code" => "#FFC0CB"],
    ["name" => "Hot Pink", "Code" => "#FF69B4"],

    // Browns / Neutrals
    ["name" => "Brown", "Code" => "#A52A2A"],
    ["name" => "Light Brown", "Code" => "#C4A484"],
    ["name" => "Dark Brown", "Code" => "#654321"],
    ["name" => "Beige", "Code" => "#F5F5DC"],
    ["name" => "Khaki", "Code" => "#F0E68C"],
    ["name" => "Tan", "Code" => "#D2B48C"],

    // Aqua / Cyan tones
    ["name" => "Cyan", "Code" => "#00FFFF"],
    ["name" => "Light Cyan", "Code" => "#E0FFFF"],
    ["name" => "Turquoise", "Code" => "#40E0D0"],
    ["name" => "Teal", "Code" => "#008080"],
    ["name" => "Dark Teal", "Code" => "#014D4D"],

    // Extra modern tones
    ["name" => "Coral", "Code" => "#FF7F50"],
    ["name" => "Salmon", "Code" => "#FA8072"],
    ["name" => "Sea Green", "Code" => "#2E8B57"],
    ["name" => "Forest Green", "Code" => "#228B22"],
    ["name" => "Slate Gray", "Code" => "#708090"],
    ["name" => "Steel Blue", "Code" => "#4682B4"],
    ["name" => "Charcoal", "Code" => "#36454F"],
];

        
        foreach ($colors as $color) {
            Color::create($color);
        }


    }
}
