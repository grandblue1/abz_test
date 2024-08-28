<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PositionResource;
use App\Models\Position;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PositionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $positions = Position::all();
        $validator = Validator::make($request->all(), []);
        if ($positions->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Positions not found'
            ], Response::HTTP_NOT_FOUND);
        }
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Positions not found',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return PositionResource::collection($positions);
    }
}
