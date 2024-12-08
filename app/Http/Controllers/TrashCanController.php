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

    public function create(Request $request) #TODO:add validation not to accept null values
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
        $trash_can->trash_type = $request->trashType;

        // uploading image
        if ($request->hasFile('imagePath')) {
            $image = $request->file('imagePath');
            $extension = $image->getClientOriginalExtension();

            // get unique name for image
            $uniqueName = now()->format('YmdHis') . '_' . Str::random(10) . '.' . $extension;

            // store image in public/images
            $path = $image->storeAs('images', $uniqueName, 'public');

            // save image path in database
            $trash_can->image_path = $path;
        }

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
        $range_km = 5; // ゴミ箱の取得範囲(km)
        $now_latitude = $request->input('latitude', null);
        $now_longitude = $request->input('longitude', null);
    
        if ($now_latitude === null || $now_longitude === null) {
            return response()->json([
                "success" => false,
                "message" => "無効なパラメータ"
            ], 400);
        }
    
        // 全ゴミ箱を取得
        $trash_cans = \App\Models\TrashCan::all();
        $nearby_trash_cans = [];
    
        foreach ($trash_cans as $trash_can) {
            $distance = TrashCanHelper::calculate_distance(
                $now_latitude,
                $now_longitude,
                $trash_can->latitude,
                $trash_can->longitude
            );
            if ($distance <= $range_km) {
                // 画像URLを生成
                $image_url = $trash_can->image_path ? asset('public/images/' . $trash_can->image_path) : null;
    
                // ゴミ箱データを整形
                $nearby_trash_cans[] = [
                    'id' => $trash_can->id,
                    'latitude' => $trash_can->latitude,
                    'longitude' => $trash_can->longitude,
                    'nearest_building' => $trash_can->nearest_building,
                    'images' => $image_url, // image_path を images に置き換え
                    'trash_type' => $trash_can->trash_type,
                    'created_at' => $trash_can->created_at,
                    'updated_at' => $trash_can->updated_at,
                ];
            }
        }
    
        return response()->json([
            "success" => true,
            "data" => $nearby_trash_cans
        ], 200);
    }
}