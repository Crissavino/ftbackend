<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GamesTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('games_types')->insert(
            [
                [
                    'sport' => 'futbol',
                    'type' => 'f5',
                    'type_text' => 'Futbol 5',
                ],
                [
                    'sport' => 'futbol',
                    'type' => 'f7',
                    'type_text' => 'Futbol 7',
                ],
                [
                    'sport' => 'futbol',
                    'type' => 'f9',
                    'type_text' => 'Futbol 9',
                ],
                [
                    'sport' => 'futbol',
                    'type' => 'f11',
                    'type_text' => 'Futbol 11',
                ],
            ]
        );
    }
}
