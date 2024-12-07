<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Helpers\TrashCanHelper;

class TrashCanController extends Controller
{
    public function index()
    {
        $trash_cans = \App\Models\TrashCan::all();
        return response()->json([
            'message' => 'Success',
            'data' => $trash_cans
        ], 200);
    }

    public function create(Request $request) #TODO:trash_typeに不正な値が入りこまないようにバリデーションを追加
    {
        $trash_can = new \App\Models\TrashCan();

        if ($request->latitude == null || $request->longitude == null || $request->trashType == null) {
            return response()->json([
                "success" => false,
                "message" => "Invalid parameter"
            ], 400);
        }

        $trash_can->latitude = $request->latitude;
        $trash_can->longitude = $request->longitude;
        $trash_can->nearest_building = $request->nearestBuilding;
        $trash_can->image_path = $request->imagePath;
        $trash_can->trash_type = $request->trashType;
        $trash_can->save();

        return response()->json([
            "success" => true,  
        ], 201);
    }

    public function show($id)
    {
        $trash_can = \App\Models\TrashCan::find($id);
        if ($trash_can == null) {
            return response()->json([
                "success" => false,
                "message" => "Not found"
            ], 404);
        }

        return response()->json([
            "success" => true,
            "data" => $trash_can
        ], 200);
    }

    public function delete($id)
    {
        $trash_can = \App\Models\TrashCan::find($id);
        if ($trash_can == null) {
            return response()->json([
                "success" => false,
                "message" => "Not found"
            ], 404);
        }

        $trash_can->delete();

        return response()->json([
            "success" => true
        ], 200);
    }

    public function serach_nearby_trash_can(Request $request)
    {
        $range_km = 5;// ゴミ箱の取得範囲(km)
        $now_latitude = isset($_GET['latitude']) ? floatval($_GET['latitude']) : null;
        $now_longitude = isset($_GET['longitude']) ? floatval($_GET['longitude']) : null;

        if ($now_latitude == null || $now_longitude == null) {
            return response()->json([
                "success" => false,
                "message" => "Invalid parameter"
            ], 400);
        }

        $trash_cans = \App\Models\TrashCan::all();
        $nearby_trash_cans = [];

        foreach ($trash_cans as $trash_can) {
            $trash_can_latitude = $trash_can->latitude;
            $trash_can_longitude = $trash_can->longitude;
            $distance = TrashCanHelper::calculate_distance($now_latitude, $now_longitude, $trash_can_latitude, $trash_can_longitude);
            if ($distance <= $range_km) {
                $nearby_trash_cans[] = $trash_can;
            }
        }

        return response()->json([
            "success" => true,
            "data" => $nearby_trash_cans
        ], 200);
    }   
}