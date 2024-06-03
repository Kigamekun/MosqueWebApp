<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrayerTimeController extends Controller
{


    public function index()
    {
        $url = "http://api.aladhan.com/v1/timingsByCity?city=".$_GET['city']."&country=Indonesia&method=8";
        $data = file_get_contents($url);
        $prayerTime = json_decode($data, true);
        return response()->json($prayerTime);
    }
}
