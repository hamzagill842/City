<?php

namespace App\Http\Controllers;

use App\Http\Requests\Favorite\AddFavortyCityRequest;
use App\Http\Requests\Favorite\RemoveFavortyCityRequest;
use App\Models\UserHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserHistoryController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        // Retrieve user's favorite cities with cities relationship
        $favoritesCities = UserHistory::with('city')->where('user_id', $user->id)->get();

        // Check if there are favorite cities
        if ($favoritesCities->isEmpty()) {
            // If no favorite cities, return null or an empty array based on your requirement

            return Response::success('No favorite cities found', null, 200);
        }

        $cities = [];

        // Iterate through favorite cities and extract city data
        foreach ($favoritesCities as $history) {

                // Extract desired fields from the city and add to the $cities array
                $cities[] = [
                    'id' => $history->id,
                    'name' => $history->city->name,
                    'city' => $history->city->city,
                    'image' => $history->city->image,
                    'destination' => $history->city->destination,
                    'location' => $history->city->location,
                    'description' => $history->city->description,
                ];
        }

        return Response::success('Favorite cities retrieved successfully', $cities,200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(AddFavortyCityRequest $request)
    {
        $user = auth()->user();

        // Get the city ID from the request
        $cityId = $request->input('city_id');

        // Check if the city already exists in the user's favorites
        $existingFavorite = UserHistory::where('user_id', $user->id)->where('province_id' , $cityId)
            ->exists();
        if ($existingFavorite) {
            // If the city is already a favorite, return a response indicating that it's already a favorite
            return Response::success('City is already a favorite', 200);
        }

        // If the city is not a favorite, add it to the user's favorites
        $userHistory = UserHistory::Create([
            'user_id' => $user->id,
            'province_id' => $cityId,
        ]);

        return Response::success('City added to favorites successfully', $userHistory->city, 200);

    }


    public function removeFavoriteCity(RemoveFavortyCityRequest $request)
    {

        $user = auth()->user();

        // Get the city ID from the request
        $cityId = $request->input('favorite_id');

        // Check if the city exists in the user's favorites
        $userHistory = UserHistory::where('user_id', $user->id)->where('id',$cityId)->first();

        if (!$userHistory) {
            // If user history not found, return an error response
            return Response::success('User history not found', null,200);
        }

        $userHistory->delete();

        return Response::success('City removed from favorites successfully', null, 200);
    }
}
