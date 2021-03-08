<?php

namespace App\Http\Controllers;

use App\Models\DaysAvailables;
use App\Models\Location;
use App\Models\Positions;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function completeUserProfile(Request $request)
    {
        $validation = $this->validateCompleteProfile($request);
        if (!$validation['success']) {
            return response([
                'success' => $validation['success'],
                'message' => $validation['message'],
            ]);
        }
        $userPositions = $validation['userPositions'];
        $userLocationDetails = $validation['userLocationDetails'];
        $daysAvailables = $validation['daysAvailables'];
        $user = User::find($request->user_id);

        $saveUserPositionsResponse = $this->saveUserPositions($userPositions, $user);
        if (!$saveUserPositionsResponse['success']) {
            return response([
                'success' => $saveUserPositionsResponse['success'],
                'message' => $saveUserPositionsResponse['message'],
                'error' => $saveUserPositionsResponse['error']
            ]);
        }

        $saveUserLocationResponse = $this->saveUserLocation($user, $userLocationDetails);
        if (!$saveUserLocationResponse['success']) {
            return response([
                'success' => $saveUserLocationResponse['success'],
                'message' => $saveUserLocationResponse['message'],
                'error' => $saveUserLocationResponse['error']
            ]);
        }

        $saveUserDaysAvailablesResponse = $this->saveUserDaysAvailables($daysAvailables, $user);
        if (!$saveUserDaysAvailablesResponse['success']) {
            return response([
                'success' => $saveUserDaysAvailablesResponse['success'],
                'message' => $saveUserDaysAvailablesResponse['message'],
                'error' => $saveUserDaysAvailablesResponse['error']
            ]);
        }

        try {
            $genre = 'female';
            if ($request->isMale) {
                $genre = 'male';
            }
            $user->update([
                'isFullySet' => 1,
                'genre' => $genre
            ]);

            $user->positions;
            $user->location;
            $user->daysAvailables;

        } catch (\Exception $exception) {
            Log::info('Error during save of user isFullySet', [$exception->getMessage()]);
            return response([
                'success' => false,
                'message' => 'Error during save of user isFullySet',
                'error' => $exception->getMessage()
            ]);
        }

        return response([
            'success' => true,
            'user' => $user,
            'message' => 'User fully setted',
        ]);

    }

    /**
     * @param Request $request
     * @return array
     */
    protected function validateCompleteProfile(Request $request)
    {
        $userPositions = $request->userPositions;
        if (!$userPositions['goalKeeper'] && !$userPositions['defense'] && !$userPositions['midfielder'] && !$userPositions['forward']) {
            return [
                'success' => false,
                'message' => 'Error during save of user positions',
            ];
        }

        $userLocationDetails = $request->userLocationDetails;
        if (!$userLocationDetails['country'] || !$userLocationDetails['countryCode'] || !$userLocationDetails['province'] || !$userLocationDetails['provinceCode'] || !$userLocationDetails['city']) {
            return [
                'success' => false,
                'message' => 'Error during save of user positions',
            ];
        }

        $daysAvailables = $request->daysAvailable;
        if (!$daysAvailables[0] && !$daysAvailables[1] && !$daysAvailables[2] && !$daysAvailables[3] && !$daysAvailables[4] && !$daysAvailables[5] && !$daysAvailables[6]) {
            return [
                'success' => false,
                'message' => 'Error during save of user days available',
            ];
        }

        return [
            'success' => true,
            'userPositions' => $userPositions,
            'userLocationDetails' => $userLocationDetails,
            'daysAvailables' => $daysAvailables,
        ];
    }

    public function getUserPositions(Request $request)
    {
        $user = User::find($request->user_id);
        $user->positions;

        return response([
            'success' => true,
            'user' => $user,
        ]);
    }

    public function editUserPositions(Request $request)
    {
        $user = User::find($request->user_id);
        $userPositions = $request->userPositions;
        if (!$userPositions['goalKeeper'] && !$userPositions['defense'] && !$userPositions['midfielder'] && !$userPositions['forward']) {
            return response([
                'success' => false,
                'message' => 'Error during save of user positions',
            ]);
        }

        $saveUserPositionsResponse = $this->saveUserPositions($userPositions, $user);
        if (!$saveUserPositionsResponse['success']) {
            return response([
                'success' => $saveUserPositionsResponse['success'],
                'message' => $saveUserPositionsResponse['message'],
                'error' => $saveUserPositionsResponse['error']
            ]);
        }
        $user->positions;

        return response([
            'success' => true,
            'user' => $user,
            'message' => 'User positions saved',
        ]);
    }

    public function getUserDaysAvailable(Request $request)
    {
        $user = User::find($request->user_id);
        $user->daysAvailables;

        return response([
            'success' => true,
            'user' => $user,
        ]);
    }

    public function editUserDaysAvailable(Request $request)
    {
        $user = User::find($request->user_id);
        $daysAvailables = $request->daysAvailable;
        if (!$daysAvailables[0] && !$daysAvailables[1] && !$daysAvailables[2] && !$daysAvailables[3] && !$daysAvailables[4] && !$daysAvailables[5] && !$daysAvailables[6]) {
            return response([
                'success' => false,
                'message' => 'Error during save of user days available',
            ]);
        }

        $saveUserDaysAvailablesResponse = $this->saveUserDaysAvailables($daysAvailables, $user);
        if (!$saveUserDaysAvailablesResponse['success']) {
            return response([
                'success' => $saveUserDaysAvailablesResponse['success'],
                'message' => $saveUserDaysAvailablesResponse['message'],
                'error' => $saveUserDaysAvailablesResponse['error']
            ]);
        }
        $user->daysAvailables;


        return response([
            'success' => true,
            'user' => $user,
            'message' => 'User days available saved',
        ]);
    }

    public function getUserLocation(Request $request)
    {
        $user = User::find($request->user_id);
        $user->location;

        return response([
            'success' => true,
            'user' => $user,
        ]);
    }

    public function editUserLocation(Request $request)
    {
        $user = User::find($request->user_id);
        $userLocationDetails = $request->userLocationDetails;
        if (!$userLocationDetails['country'] || !$userLocationDetails['countryCode'] || !$userLocationDetails['province'] || !$userLocationDetails['provinceCode'] || !$userLocationDetails['city']) {
            return response([
                'success' => false,
                'message' => 'Error during save of user positions',
            ]);
        }

        $saveUserLocationResponse = $this->saveUserLocation($user, $userLocationDetails);
        if (!$saveUserLocationResponse['success']) {
            return response([
                'success' => $saveUserLocationResponse['success'],
                'message' => $saveUserLocationResponse['message'],
                'error' => $saveUserLocationResponse['error']
            ]);
        }
        $user->location;

        return response([
            'success' => true,
            'user' => $user,
            'message' => 'User location saved',
        ]);
    }


    /**
     * @param $userPositions
     * @param $user
     * @return array|bool[]
     */
    protected function saveUserPositions($userPositions, $user): array
    {
        try {
            foreach ($userPositions as $position => $isThisPosition) {
                $positionFromDB = Positions::where('position', $position)->first();
                $user->positions()->detach($positionFromDB->id);
                if ($isThisPosition) {
                    $user->positions()->attach($positionFromDB->id);
                }
            }

            return [
                'success' => true,
            ];
        } catch (\Exception $exception) {
            Log::info('Error during save of user positions', [$exception->getMessage()]);
            return [
                'success' => false,
                'message' => 'Error during save of user positions',
                'error' => $exception->getMessage()
            ];
        }
    }

    /**
     * @param $user
     * @param $userLocationDetails
     * @return array|bool[]
     */
    protected function saveUserLocation($user, $userLocationDetails): array
    {
        try {
            Location::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'country' => $userLocationDetails['country'],
                    'countryCode' => $userLocationDetails['countryCode'],
                    'province' => $userLocationDetails['province'],
                    'provinceCode' => $userLocationDetails['provinceCode'],
                    'city' => $userLocationDetails['city'],
                    'lat' => $userLocationDetails['lat'],
                    'lng' => $userLocationDetails['lng'],
                ]
            );

            return [
                'success' => true,
            ];
        } catch (\Exception $exception) {
            Log::info('Error during save of user location', [$exception->getMessage()]);
            return [
                'success' => false,
                'message' => 'Error during save of user location',
                'error' => $exception->getMessage()
            ];
        }
    }

    /**
     * @param $daysAvailables
     * @param $user
     * @return array|bool[]
     */
    protected function saveUserDaysAvailables($daysAvailables, $user): array
    {
        try {
            foreach ($daysAvailables as $day => $available) {
                DaysAvailables::updateOrCreate(
                    ['user_id' => $user->id, 'dayOfTheWeek' => $day],
                    [
                        'available' => json_encode($available)
                    ]
                );
            }

            return [
                'success' => true,
            ];
        } catch (\Exception $exception) {
            Log::info('Error during save of user days available', [$exception->getMessage()]);
            return [
                'success' => false,
                'message' => 'Error during save of user days available',
                'error' => $exception->getMessage()
            ];
        }
    }

    public function getUserData(Request $request)
    {
        $user = User::find($request->user_id);
        $user->positions;
        $user->location;
        $user->daysAvailables;

        return response([
            'success' => true,
            'user' => $user,
        ]);
    }

    public function getUserOffers(Request $request)
    {
        $user = User::find($request->user_id);
        $genre = $request->isMale ? 'male' : 'female';
        $mix = $request->mix;

        // filtro por genero
        if (!$mix) {
            $users = User::where('id', '!=',$request->user_id)
                ->where('genre', $genre);
        } else {
            $users = User::where('id', '!=',$request->user_id)
                ->where('genre', $genre);
        }
        if ($users->count() === 0) {
            return response([
                'success' => true,
                'users' => [],
            ]);
        }

        $userPositions = $request->positions;
        $positionsSearched = [];
        foreach ($userPositions as $position => $required) {
            if ($required) {
                $positionsSearched[] = $position;
            }
        }
        // filtrar por posiciones
        $users = $users
            ->whereHas('positions', function(Builder $query) use ($positionsSearched, $request) {
                $query->whereIn('position', $positionsSearched);
            })->with('positions');
        if ($users->count() === 0) {
            return response([
                'success' => true,
                'users' => [],
            ]);
        }

        $gr_circle_radius = 6371;
        $max_distance = $request->range;
        $userLat = $user->location->lat;
        $userLng = $user->location->lng;
        $distance_select = sprintf(
            "
                                    ( %d * acos( cos( radians(%s) ) " .
            " * cos( radians( lat ) ) " .
            " * cos( radians( lng ) - radians(%s) ) " .
            " + sin( radians(%s) ) * sin( radians( lat ) ) " .
            " ) " .
            ")
                                     ",
            $gr_circle_radius,
            $userLat,
            $userLng,
            $userLat
        );
        $locations = Location::select('*')
            ->having(DB::raw($distance_select), '<=', $max_distance)
            ->get();
        $users = $users->whereHas('location', function(Builder $query) use ($locations) {
            $query->whereIn('id', $locations->pluck('id'));
        })->with('location')->get();
        if ($users->count() === 0) {
            return response([
                'success' => true,
                'users' => [],
            ]);
        }

        // filtrar por DIA y ordenar por mayor coincidencia horaria
        $searchedDaysAvailable = $request->daysAvailable;
        foreach ($users as $key => $user) {
            $user['coincidence'] = 0;
            foreach ($user->daysAvailables as $daysAvailable) {
                $userHoursAvailableArray = json_decode($daysAvailable->available, true);
                if ($searchedDaysAvailable[$daysAvailable->dayOfTheWeek] && $userHoursAvailableArray) {
                    $user['coincidence'] = $user['coincidence'] + 10000;
                    foreach ($userHoursAvailableArray as $hour => $isAvailable) {
                        if ($isAvailable && $searchedDaysAvailable[$daysAvailable->dayOfTheWeek][$hour]) {
                            $user['coincidence'] = $user['coincidence'] + 250;
                        }
                    }
                }
            }
            if ($user['coincidence'] === 0) {
                $users->forget($key);
            }
        }
        if ($users->count() === 0) {
            return response([
                'success' => true,
                'users' => [],
            ]);
        }

        return response([
            'success' => true,
            'users' => $users->sortByDesc('coincidence')->values(),
        ]);

    }
}
