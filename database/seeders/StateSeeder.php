<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        State::create([
            'name_ar' => 'البصرة',
            'name_en' => 'Basra',
        ]);

        State::create([
            'name_ar' => 'بغداد',
            'name_en' => 'Baghdad',
        ]);

        State::create([
            'name_ar' => 'كربلاء',
            'name_en' => 'Karbala',
        ]);

        State::create([
            'name_ar' => 'كركوك',
            'name_en' => 'Karkouk',
        ]);
    }
}
