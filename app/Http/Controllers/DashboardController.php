<?php

namespace App\Http\Controllers;
use App\Models\{Zakat, Infaq, Blog, Activity};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $result = DB::select('SELECT count_unique_names_in_zakat() as total');
        $jumlahZakat = $result[0]->total;
        $zakatTotal = Zakat::sum('amount');
        $infaqTotal = Infaq::sum('amount');

        $blog = Blog::orderBy('created_at', 'desc')->limit(6)->get();
        $activity = Activity::orderBy('created_at', 'desc')->limit(6)->get();

        $data = [
            'jumlahZakat' => $jumlahZakat,
            'zakatTotal' => $zakatTotal,
            'infaqTotal' => $infaqTotal,
            'blog' => $blog,
            'activity' => $activity
        ];
        return response()->json(['message' => 'Data berhasil di load', 'status' => 'success', 'data' => $data, 'statusCode' => 200], 200);
    }
}
