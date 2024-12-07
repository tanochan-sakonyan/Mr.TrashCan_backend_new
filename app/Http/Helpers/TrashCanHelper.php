<?php

namespace App\Http\Helpers;

class TrashCanHelper
{
    const EARTH_RAD = 6378.137; // 地球の半径 [km]

    /**
     * 度をラジアンに変換する関数
     *
     * @param float $deg
     * @return float
     */
    public static function deg2rad_custom(float $deg): float
    {
        return $deg * pi() / 180.0;
    }

    /**
     * 2点間の距離を計算する関数
     *
     * @param float $now_lat
     * @param float $now_lon
     * @param float $target_lat
     * @param float $target_lon
     * @return float
     */
    public static function calculate_distance(float $now_lat, float $now_lon, float $target_lat, float $target_lon): float
    {
        $rad_lat1 = self::deg2rad_custom($now_lat);
        $rad_lon1 = self::deg2rad_custom($now_lon);
        $rad_lat2 = self::deg2rad_custom($target_lat);
        $rad_lon2 = self::deg2rad_custom($target_lon);

        return self::EARTH_RAD * acos(
            sin($rad_lat1) * sin($rad_lat2) +
            cos($rad_lat1) * cos($rad_lat2) * cos($rad_lon2 - $rad_lon1)
        );
    }
}
