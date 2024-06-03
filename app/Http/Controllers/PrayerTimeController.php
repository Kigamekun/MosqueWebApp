<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrayerTimeController extends Controller
{


    public function index()
    {
        if (isset($_GET['city'])) {
        $url = "http://api.aladhan.com/v1/timingsByCity?city=".$_GET['city']."&country=Indonesia&method=8";

        } else {
        $url = "http://api.aladhan.com/v1/timingsByCity?city=Bogor&country=Indonesia&method=8";

        }
        $data = file_get_contents($url);
        $prayerTime = json_decode($data, true);
        return response()->json($prayerTime);
    }
}
