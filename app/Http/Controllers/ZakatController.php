<?php

namespace App\Http\Controllers;

use App\Models\Zakat;
use Illuminate\Http\Request;
use App\Services\Midtrans\CreateSnapTokenService;
use App\Mail\ZakatMail;
use Illuminate\Support\Facades\Mail;

class ZakatController extends Controller
{
    public function index(Request $request)
    {
        if (isset($_GET['search'])) {
            $search = $_GET['search'];
            $zakat = Zakat::where('name', 'like', '%' . $search . '%')->paginate(10);
        } else if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
            $start_date = $_GET['start_date'];
            $end_date = $_GET['end_date'];
            $zakat = Zakat::whereBetween('date', [$start_date, $end_date])->paginate(10);
        } else if (isset($_GET['start_date'])) {
            $start_date = $_GET['start_date'];
            $zakat = Zakat::where('date', '>=', $start_date)->paginate(10);
        } else if (isset($_GET['end_date'])) {
            $end_date = $_GET['end_date'];
            $zakat = Zakat::where('date', '<=', $end_date)->paginate(10);
        } else if (isset($_GET['status'])) {
            $status = $_GET['status'];
            $zakat = Zakat::where('status', $status)->paginate(10);
        } else if (isset($_GET['amount'])) {
            $amount = $_GET['amount'];
            $zakat = Zakat::where('amount', $amount)->paginate(10);
        } else if (isset($_GET['amount_min']) && isset($_GET['amount_max'])) {
            $amount_min = $_GET['amount_min'];
            $amount_max = $_GET['amount_max'];
            $zakat = Zakat::whereBetween('amount', [$amount_min, $amount_max])->paginate(10);
        } else if (isset($_GET['amount_min'])) {
            $amount_min = $_GET['amount_min'];
            $zakat = Zakat::where('amount', '>=', $amount_min)->paginate(10);
        } else if (isset($_GET['amount_max'])) {
            $amount_max = $_GET['amount_max'];
            $zakat = Zakat::where('amount', '<=', $amount_max)->paginate(10);
        } else if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
            $zakat = Zakat::orderBy('amount', $sort)->paginate(10);
        } else {
            $zakat = Zakat::paginate(10);
        }
        return response()->json(['message' => 'Data berhasil di load', 'status' => 'success','data' => $zakat, 'statusCode' => 200], 200);
    }

    public function bayar(Request $request)
    {
        $midtrans = new CreateSnapTokenService($request->all());
        $snapToken = $midtrans->getSnapToken();
        return response()->json(['message' => 'Token berhasil di load', 'status' => 'success','data' => $snapToken, 'statusCode' => 200], 200);
    }

    public function store(Request $request)
    {
        $zakat = new Zakat();
        $zakat->name = $request->name;
        $zakat->gender = $request->gender;
        $zakat->email = $request->email;
        $zakat->phone = $request->phone;
        $zakat->amount = $request->amount;
        $zakat->status = 'pending';
        $zakat->type = $request->type;

        $zakat->date = now();
        $zakat->midtrans_token = $request->midtrans_token;

        $zakat->save();

        return response()->json(['message' => 'Zakat berhasil di tambahkan', 'status' => 'success','data' => $zakat, 'statusCode' => 200], 200);
    }

    public function changeStatus(Request $request, $id)
    {
        $zakat = Zakat::findOrFail($id);
        $zakat->status = $request->status;

        if ($request->status == 'disalurkan') {
            Mail::to($zakat->email)->send(new ZakatMail());

            $zakat->penerima = $request->penerima;
        }

        $zakat->save();

        return response()->json(['message' => 'Zakat berhasil di update', 'status' => 'success','data' => $zakat, 'statusCode' => 200], 200);
    }



}
