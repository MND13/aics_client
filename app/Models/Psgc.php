<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Psgc extends Model
{
    use HasFactory;

    public static function getRegions()
    {
        $instance = new static;
        return $instance->select('region_psgc', 'region_name')->orderBy("region_name")->distinct()->get();
    }

    public static function getProvinces()
    {
        $instance = new static;
        return $instance->select('province_psgc', 'province_name')
            ->where("region_psgc", "110000000")
            ->orderBy("province_name")
            ->distinct()->get();
    }

    public static function getCities($field, $value)
    {
        $instance = new static;
        return $instance->where($field, $value)
            ->select("city_name")
            ->orderBy("city_name")
            ->distinct()
            ->get();
    }

    public static function getBrgys($fields = array())
    {
        /*$instance = new static;

        foreach ($fields as $f) {
            $instance->where($f["field"], $f["value"]);
        }

        $instance->where("region_psgc", "110000000")->orderBy("brgy_name");
        return $instance->get();*/

        $instance = Psgc::select();
        if (sizeof($fields) > 0) {
            foreach ($fields as $f) {
                $instance->where($f["field"], $f["value"]);
            }
        }
        $instance->where("region_psgc", "110000000")
            ->orderBy("province_name")
            ->orderBy("city_name")
            ->orderBy("brgy_name");
        return $instance->get();
    }
}
