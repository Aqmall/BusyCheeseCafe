<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path disesuaikan dengan file di public/img/
        Menu::create(['namaMenu' => 'Cheesee Cake', 'kategori' => 'Main Menu', 'harga' => 35000, 'image_url' => 'img/cheesee-cake.jpg']);
        Menu::create(['namaMenu' => 'Dulce', 'kategori' => 'Main Menu', 'harga' => 58000, 'image_url' => 'img/dulce.jpg']);
        Menu::create(['namaMenu' => 'Chocolate Mousse', 'kategori' => 'Main Menu', 'harga' => 25000, 'image_url' => 'img/chocolate-mousse.jpg']);

        Menu::create(['namaMenu' => 'Matcha Latte', 'kategori' => 'Beverage', 'harga' => 74000, 'image_url' => 'img/matcha-latte.jpg']);
        Menu::create(['namaMenu' => 'Chai Latte', 'kategori' => 'Beverage', 'harga' => 32000, 'image_url' => 'img/chai-latte.jpg']);

        Menu::create(['namaMenu' => 'Americano', 'kategori' => 'Coffee', 'harga' => 24000, 'image_url' => 'img/americano.jpg']);
        Menu::create(['namaMenu' => 'Brazil Santos', 'kategori' => 'Coffee', 'harga' => 24000, 'image_url' => 'img/brazil-santos.jpg']);
        Menu::create(['namaMenu' => 'Espresso (Single)', 'kategori' => 'Coffee', 'harga' => 18000, 'image_url' => 'img/espresso.jpg']);
        Menu::create(['namaMenu' => 'Espresso (Double)', 'kategori' => 'Coffee', 'harga' => 20000, 'image_url' => 'img/espresso2.jpg']);
        Menu::create(['namaMenu' => 'Flat White', 'kategori' => 'Coffee', 'harga' => 32000, 'image_url' => 'img/flat-white.jpg']);
        Menu::create(['namaMenu' => 'Cappuccino', 'kategori' => 'Coffee', 'harga' => 28000, 'image_url' => 'img/cappuccino.jpg']);
        Menu::create(['namaMenu' => 'Vanilla Bean Latte', 'kategori' => 'Coffee', 'harga' => 28000, 'image_url' => 'img/vanilla-bean-latte.jpg']);
        Menu::create(['namaMenu' => 'Magic Latte', 'kategori' => 'Coffee', 'harga' => 32000, 'image_url' => 'img/magic-latte.jpg']);
        Menu::create(['namaMenu' => 'Nutty Latte', 'kategori' => 'Coffee', 'harga' => 32000, 'image_url' => 'img/nutty-latte.jpg']);
        Menu::create(['namaMenu' => 'Mont Blanc', 'kategori' => 'Coffee', 'harga' => 32000, 'image_url' => 'img/mont-blanc.jpg']);

        Menu::create(['namaMenu' => 'Espresso Tonic', 'kategori' => 'Mocktails', 'harga' => 42000, 'image_url' => 'img/espresso-tonic.jpg']);
        Menu::create(['namaMenu' => 'Seafoam', 'kategori' => 'Mocktails', 'harga' => 42000, 'image_url' => 'img/seafoam.jpg']);
        Menu::create(['namaMenu' => 'Zero Foreale', 'kategori' => 'Mocktails', 'harga' => 42000, 'image_url' => 'img/zero-foreale.jpg']);
        Menu::create(['namaMenu' => 'Grape Fruit', 'kategori' => 'Mocktails', 'harga' => 38000, 'image_url' => 'img/grape-fruit.jpg']);
        Menu::create(['namaMenu' => 'Zero Sangria', 'kategori' => 'Mocktails', 'harga' => 42000, 'image_url' => 'img/zero-sangria.jpg']);
    }
}