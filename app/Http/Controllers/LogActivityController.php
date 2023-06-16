<?php

namespace App\Http\Controllers;

use App\Models\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LogActivityController extends Controller
{
    /**
     * @OA\Info(
     *     title="Log Activity API",
     *     version="1.0.0",
     *     description="API for managing log activities",
     *     @OA\Contact(
     *         email="admin@example.com"
     *     )
     * )
     * 
     * @OA\Post(
     *     path="/api/log-activity/create",
     *     tags={"Log Activity"},
     *     summary="Create a new log activity",
     *     operationId="createLogActivity",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Log activity data",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="integer",
     *                     minimum=1,
     *                     nullable=true,
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="location_id",
     *                     type="integer",
     *                     minimum=1,
     *                     nullable=true,
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     minLength=3,
     *                     maxLength=255,
     *                     example="Log activity name"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     minLength=3,
     *                     maxLength=255,
     *                     example="Log activity description"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Log activity created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="object"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="object"
     *             )
     *         )
     *     )
     * )
     */
    public function create(Request $request)
    {
        $v = Validator::make($request->all(), [
            'user_id' => 'nullable|numeric|min:1|exists:users,id',
            'location_id' => 'nullable|numeric|min:1|exists:user_locations,id',
            'name' => 'required|string|max:255|min:3',
            'description' => 'required|string|max:255|min:3',
        ]);

        if ($v->fails()) {
            return response()->json(['message' => $v->errors()], 400);
        }

        try {
            $logActivity = new LogActivity();
            $logActivity->user_id = $request->user_id;
            $logActivity->location_id = $request->location_id;
            $logActivity->name = $request->name;
            $logActivity->description = $request->description;
            $logActivity->created_at = now();
            $logActivity->updated_at = now();
            $logActivity->save();

            return response()->json(['message' => 'Log activity created successfully'], 201);
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
            $logActivity = LogActivity::find($id);

            if (!$logActivity) {
                return response()->json(['message' => 'Log activity not found'], 404);
            }

            return response()->json(['data' => $logActivity], 200);
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
            $logActs = LogActivity::paginate($size, ['*'], 'page', $page);

            if ($logActs->isEmpty()) {
                return response()->json(['message' => 'No user locations found'], 404);
            }

            return response()->json(['data' => $logActs], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th], 500);
        }
    }
}
