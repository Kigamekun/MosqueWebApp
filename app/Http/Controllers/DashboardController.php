<?php

namespace App\Http\Controllers;
use App\Models\{Zakat, Infaq, Blog, Activity};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $result = DB::select('SELECT count_unique_names_in_zakat() as total');
        $jumlahZakat = $result[0]->total;
        $zakatTotal = Zakat::sum('amount');
        $infaqTotal = DB::select('SELECT SUM(amount) AS total_infaq FROM public.infaqs;')[0]->total_infaq;

        $blog = Blog::orderBy('created_at', 'desc')->limit(6)->get();
        $activity = Activity::orderBy('created_at', 'desc')->limit(6)->get();

        $blog_per_user = DB::select('SELECT u.name, COUNT(b.id) AS total_blogs FROM public.users u INNER JOIN public.blogs b ON u.id = b.user_id GROUP BY u.name');

        $jumlahUser = DB::select('SELECT COUNT(*) AS total_users FROM public.users;');

        $rata_rata_zakat = DB::select('SELECT AVG(amount) AS average_zakat FROM public.zakats;')[0]->average_zakat;

        $min_infaq = DB::select('SELECT MIN(amount) AS min_infaq FROM public.infaqs;')[0]->min_infaq;
        $max_infaq = DB::select('SELECT MIN(amount) AS max_infaq FROM public.infaqs;')[0]->max_infaq;

        $user_lebih_dari_satu_blog = DB::select(' SELECT u.name, COUNT(b.id) AS total_blogs FROM public.users u INNER JOIN public.blogs b ON u.id = b.user_id GROUP BY u.name HAVING COUNT(b.id) > 1;');

        $zakat_lebih_besar_dari_infaq = DB::select(' SELECT id, amount FROM public.zakats WHERE amount > SOME (SELECT amount FROM public.infaqs)');

        $user_memverifikasi_data_infaq = DB::select(' SELECT * FROM public.users WHERE id IN (SELECT verifier_id FROM public.infaqs);');

        $data = [
            'jumlahZakat' => $jumlahZakat,
            'zakatTotal' => $zakatTotal,
            'jumlahUser' => $jumlahUser,
            'infaqTotal' => $infaqTotal,
            'blog' => $blog,
            'blog_per_user' => $blog_per_user,
            'rata_rata_zakat' => $rata_rata_zakat,
            'user_lebih_dari_satu_blog' => $user_lebih_dari_satu_blog,
            'activity' => $activity,
            'zakat_lebih_besar_dari_infaq' => $zakat_lebih_besar_dari_infaq,
            'user_memverifikasi_data_infaq' => $user_memverifikasi_data_infaq
        ];
        return response()->json(['message' => 'Data berhasil di load', 'status' => 'success', 'data' => $data, 'statusCode' => 200], 200);
    }
}
