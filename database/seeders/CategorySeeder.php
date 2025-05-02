<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Ikonik Kota',
            'Museum & Edukasi',
            'Kuliner Legendaris',
            'Tempat Ibadah Bersejarah',
            'Ruang Publik & Taman',
            'Pusat Belanja & Pasar',
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category]);
        }
    }
}
