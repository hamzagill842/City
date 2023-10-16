<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProvinceRequest;
use App\Models\Province;
use App\Models\UserHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ProvinceController extends Controller
{
    public function index()
    {
        $nameFilter = request('search');
        $user = auth()->user();

// Retrieve user's favorite city IDs
        $filteredProvinces = Province::select(
            'provinces.id',
            'provinces.name',
            'provinces.city',
            'provinces.image',
            'provinces.destination',
            'provinces.location',
            'provinces.description',
            \DB::raw('CASE WHEN user_histories.province_id IS NOT NULL THEN true ELSE false END as isFavorite')
        )
            ->leftJoin('user_histories', function ($join) use ($user) {
                $join->on('provinces.id', '=', 'user_histories.province_id')
                    ->where('user_histories.user_id', '=', $user->id);
            })
            ->where(function ($query) use ($nameFilter) {
                $query->where('provinces.name', 'like', '%' . $nameFilter . '%')
                    ->orWhere('provinces.city', 'like', '%' . $nameFilter . '%')
                    ->orWhere('provinces.destination', 'like', '%' . $nameFilter . '%')
                    ->orWhere('provinces.location', 'like', '%' . $nameFilter . '%')
                    ->orWhere('provinces.description', 'like', '%' . $nameFilter . '%');
            })
            ->get();
        return Response::success('Operation succeeded', $filteredProvinces, 200);
    }

    public function cities()
    {

        $query = Province::select('city')->get()->toArray();

        return Response::success('Operation succeeded', $query,200);
    }

    public function store(StoreProvinceRequest $request)
    {
        if ($request->hasFile('image')) {
            // Store the uploaded file in the public/uploads directory with a custom name
            $fileName = time() .'.'. $request->file('image')->getClientOriginalExtension();

            $imagePath = $request->file('image')->move(public_path('uploads'), $fileName);
        } else {
            $fileName = null;
        }

        $province = Province::create([
            'name' => $request->input('name'),
            'city' => $request->input('city'),
            'destination' => $request->input('destination'),
            'location' => $request->input('location'),
            'description' => $request->input('description'),
            'image' => $fileName, // Save the image path in the 'image' column
        ]);
        return Response::success('Operation succeeded', $province,200);
    }

    public function show(Province $province)
    {
        return $province;
    }

    public function update(Request $request, Province $province)
    {
        if ($request->hasFile('image')) {
            // Delete the previous image (if it exists)
            if ($province->image) {
                Storage::disk('public')->delete($province->image);
            }

            // Store the uploaded file in the public storage disk
            $imagePath = $request->file('image')->store('uploads', 'public');
        } else {
            // If no new image is uploaded, keep the existing image path
            $imagePath = $province->image;
        }

        // Update the province record
        $province->update([
            'name' => $request->input('name'),
            'destination' => $request->input('destination'),
            'city' => $request->input('city'),
            'location' => $request->input('location'),
            'description' => $request->input('description'),
            'image' => $imagePath,
        ]);
        return Response::success('Operation succeeded', $province,200);
    }

    public function destroy(Province $province)
    {
        $province->delete();
        return response()->json(null, 204);
    }
}

