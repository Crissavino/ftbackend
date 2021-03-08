<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GamesLocation;
use App\Models\GamesType;
use App\Models\GamesWantedPosition;
use App\Models\Positions;
use Illuminate\Support\Facades\Log;

class GameController extends Controller
{
    public function createMatch()
    {
//        Log::info(json_encode(\request()->all()));
//        return [
//            'success' => false,
//        ];

        // "user_id":12
        // "gender":{"men":true,"women":false,"mix":false}
        // "matchType":{"f5":false,"f7":false,"f9":true,"f11":false}
        // "whatPositions":{"goalKeeper":0,"defense":4,"midfielder":2,"forward":6}
        // "userLocationDetails":{"country":"Argentina","countryCode":"AR","province":"Buenos Aires Province","provinceCode":"Buenos Aires Province","city":"La Plata Partido","lat":-34.9204948,"lng":-57.95356570000001}
        // "cost":10

        $data = [
            'user_id' => \request()->user_id,
            'cost' => \request()->cost,
        ];

        try {
            $data['when_play'] = (new \DateTime(\request()->whenPlay));
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

        foreach (\request()->gender as $gender => $value) {
            if ($value) {
                $data['gender'] = $gender;
            }
        }

        foreach (\request()->matchType as $type => $value) {
            try {
                if ($value) {
                    $matchType = GamesType::all()->firstWhere('type', $type);
                    $data['games_type_id'] = $matchType->id;
                }
            } catch (\Exception $e) {
                Log::info($e->getMessage());
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        }

        $game = Game::create($data);

        foreach (\request()->whatPositions as $position => $amount) {
            try {
                $positionDB = Positions::all()->firstWhere('position', $position);
                GamesWantedPosition::create(
                    [
                        'game_id' => $game->id,
                        'position_id' => $positionDB->id,
                        'amount' => $amount,
                    ]
                );
            } catch (\Exception $e) {
                Log::info($e->getMessage());
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        }
        $matchLocation = \request()->matchLocation;
        $matchLocation['game_id'] = $game->id;

        GamesLocation::create($matchLocation);

        return [
            'success' => true,
            'message' => 'Game created'
        ];
    }
}
