<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        // Mengambil 4 menu acak untuk ditampilkan di section "Menu Favorit"
        $featuredMenus = Menu::inRandomOrder()->take(4)->get();
        
        // Daftar waktu yang tersedia untuk dropdown (DIUBAH MENJADI PER 1 JAM)
        $timeSlots = [
            '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', 
            '16:00', '17:00', '18:00', '19:00', '20:00', '21:00'
        ];
        
        return view('home', compact('featuredMenus', 'timeSlots'));
    }
}