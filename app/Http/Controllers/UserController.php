<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{

    public function getUser()
    {
        $user = auth()->user();

        return Response::success('Operation succeeded', $user,200);
    }

    public function updateUser(UpdateUserRequest $request)
    {
        $user = auth()->user();

        // Update the user's information
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'city' => $request->input('city'),
            'date_of_birth' => $request->input('date_of_birth'),
            // Update other fields here
        ]);

        return Response::success('Operation succeeded', $user,200);
    }
}
