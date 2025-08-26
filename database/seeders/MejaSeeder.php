<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Meja;

class MejaSeeder extends Seeder
{
    public function run(): void
    {
        // Data disesuaikan dari file App.tsx
        $mejas = [
            ['nomorMeja' => '1', 'kapasitas' => 2, 'pos_x' => 50, 'pos_y' => 50],
            ['nomorMeja' => '2', 'kapasitas' => 4, 'pos_x' => 150, 'pos_y' => 50],
            ['nomorMeja' => '3', 'kapasitas' => 4, 'pos_x' => 250, 'pos_y' => 50],
            ['nomorMeja' => '4', 'kapasitas' => 6, 'pos_x' => 50, 'pos_y' => 150],
            ['nomorMeja' => '5', 'kapasitas' => 2, 'pos_x' => 150, 'pos_y' => 150],
            ['nomorMeja' => '6', 'kapasitas' => 4, 'pos_x' => 250, 'pos_y' => 150],
            ['nomorMeja' => '7', 'kapasitas' => 8, 'pos_x' => 50, 'pos_y' => 250],
            ['nomorMeja' => '8', 'kapasitas' => 2, 'pos_x' => 150, 'pos_y' => 250],
            ['nomorMeja' => '9', 'kapasitas' => 4, 'pos_x' => 250, 'pos_y' => 250],
            ['nomorMeja' => '10', 'kapasitas' => 2, 'pos_x' => 350, 'pos_y' => 150],
        ];

        foreach ($mejas as $meja) {
            Meja::create([
                'nomorMeja' => $meja['nomorMeja'],
                'kapasitas' => $meja['kapasitas'],
                'pos_x' => $meja['pos_x'],
                'pos_y' => $meja['pos_y'],
                'statusMeja' => 'Tersedia',
            ]);
        }
    }
}