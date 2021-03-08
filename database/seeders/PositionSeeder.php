<?php

namespace Database\Seeders;

use App\Models\Positions;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('positions')->insert(
            [
                [
                    'sport' => 'futbol',
                    'position' => 'goalKeeper',
                ],
                [
                    'sport' => 'futbol',
                    'position' => 'defense',
                ],
                [
                    'sport' => 'futbol',
                    'position' => 'midfielder',
                ],
                [
                    'sport' => 'futbol',
                    'position' => 'forward',
                ],
            ]
        );
    }
}
