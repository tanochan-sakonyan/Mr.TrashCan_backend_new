<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // 追加

class TrashCansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // テーブルのクリア
        DB::table('trash_cans')->truncate();

        // 初期データ用意（列名をキーとする連想配列）
        $trash_cans = [
                ['latitude' => 35.681236, 'longitude' => 139.767125, 'nearest_building' => '東京駅', 'image_path' => 'tokyo_station.jpg', 'trash_type' => ['burnable', 'unburnable', 'recyclable']],
                ['latitude' => 35.658034, 'longitude' => 139.701636, 'nearest_building' => '新宿駅', 'image_path' => 'shinjuku_station.jpg', 'trash_type' => ['burnable', 'unburnable', 'recyclable']],
            ];

        // 登録
        foreach ($trash_cans as $trash_can) {
            \App\Models\TrashCan::create($trash_can);
        }

    }
}
