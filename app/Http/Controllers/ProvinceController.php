<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProvinceRequest;
use App\Models\Province;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ProvinceController extends Controller
{
    public function index()
    {
        $nameFilter = request('search');

// Start with all provinces and apply the name filter if provided
        $query = Province::query()->select('id','name','city','image','destination','location','description');

        if ($nameFilter) {
            $query->where('name', 'like', '%' . $nameFilter . '%')
                ->orWhere('city', 'like', '%' . $nameFilter . '%')
                ->orWhere('destination', 'like', '%' . $nameFilter . '%')
                ->orWhere('location', 'like', '%' . $nameFilter . '%')
                ->orWhere('description', 'like', '%' . $nameFilter . '%');
        }

// Get filtered provinces
        $filteredProvinces = $query->get();

        return Response::success('Operation succeeded', $filteredProvinces,200);
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

