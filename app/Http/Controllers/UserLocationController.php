<?php

namespace App\Http\Controllers;

use App\Models\UserLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserLocationController extends Controller
{
    public function create(Request $request)
    {
        $v = Validator::make($request->all(), [
            'coordinat' => 'required|max:255',
            'provence' => 'required|max:255',
            'city' => 'required|max:255',
            'address' => 'required|max:255',
        ]);

        if ($v->fails()) {
            return response()->json(['message' => $v->errors()], 400);
        }

        try {
            $userLocation = new UserLocation();
            $userLocation->coordinat = $request->input('coordinat');
            $userLocation->provence = $request->input('provence');
            $userLocation->city = $request->input('city');
            $userLocation->address = $request->input('address');
            $userLocation->created_at = now();
            $userLocation->updated_at = now();
            $userLocation->save();

            return response()->json(['message' => 'User location created successfully'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th], 500);
        }
    }

    public function get($id)
    {
        $v = Validator::make(['id' => $id], [
            'id' => 'required|numeric|min:1',
        ]);

        if ($v->fails()) {
            return response()->json(['message' => $v->errors()], 400);
        }

        try {
            $userLocation = UserLocation::find($id);

            if (!$userLocation) {
                return response()->json(['message' => 'User location not found'], 404);
            }

            return response()->json(['data' => $userLocation], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th], 500);
        }
    }

    public function getAll()
    {
        if (!isset($_GET['page']) || !isset($_GET['size'])) {
            return response()->json(['message' => 'page and size can`t be blank or zero'], 400);
        }

        $page = $_GET['page'];
        $size = $_GET['size'];
        $v = Validator::make(['page' => $page, 'size' => $size], [
            'page' => 'required|numeric|min:1',
            'size' => 'required|numeric|min:1',
        ]);

        if ($v->fails()) {
            return response()->json(['message' => $v->errors()], 400);
        }

        try {
            $userLocations = UserLocation::paginate($size, ['*'], 'page', $page);

            if ($userLocations->isEmpty()) {
                return response()->json(['message' => 'No user locations found'], 404);
            }

            return response()->json(['data' => $userLocations], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th], 500);
        }
    }
}
