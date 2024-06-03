<?php

namespace App\Http\Controllers;

use App\Models\{Zakat,User};
use Illuminate\Http\Request;
use App\Services\Midtrans\CreateSnapTokenService;
use App\Mail\ZakatMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class ZakatController extends Controller
{
    public function index(Request $request)
    {
        if (isset($_GET['amount_min']) && isset($_GET['amount_max'])) {
            $amount_min = $_GET['amount_min'];
            $amount_max = $_GET['amount_max'];

            if ($amount_min == '' or $amount_max == '') {
                $zakat = DB::select(' SELECT *  FROM public.zakats');

            } else {
                $zakat = DB::select('SELECT *  FROM public.zakats WHERE amount BETWEEN '.$amount_min.' AND '.$amount_max.';');

            }

        } else {
            $zakat = DB::select(' SELECT *  FROM public.zakats');
        }

        $zakat_unik = DB::select('SELECT DISTINCT type,count(zakats.type) FROM public.zakats GROUP BY zakats.type;');

        $zakat = collect($zakat);

        $zakat->transform(function ($in) {
            $in->amount = 'Rp. ' . number_format($in->amount, 0, ',', '.');
            $us = User::find($in->approver_id);
            if ($us != null) {
                $in->amil = $us->name;
            } else {
                $in->amil = '-';
            }
            if ($in->midtrans_token != '-') {
                $in->payment = [
                    'transaction_id' => $in->transaction_id,
                    'order_id' => $in->order_id,
                    'payment_type' => $in->payment_type,
                ];
            } else {
                $in->payment = [
                    'transaction_id' => '-',
                    'order_id' => '-',
                    'payment_type' => 'Cash',
                ];
            }
            return $in;
        });
        return response()->json(['message' => 'Data berhasil di load', 'status' => 'success','data' => ['data' => $zakat],'zakat_unik' => $zakat_unik,'total' =>  number_format(Zakat::sum('amount'), 0, ',', '.'), 'statusCode' => 200], 200);
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


        if ($request->midtrans_token != '-') {
            $zakat->midtrans_token = $request->midtrans_token;
            $zakat->transaction_id = $request->transaction_id;
            $zakat->order_id = $request->order_id;
            $zakat->payment_type = $request->payment_type;
        } else {
            $zakat->midtrans_token = $request->midtrans_token;
        }
        $zakat->save();
        return response()->json(['message' => 'Zakat berhasil di tambahkan', 'status' => 'success','data' => $zakat, 'statusCode' => 200], 200);
    }

    public function changeStatus(Request $request, $id)
    {
        $zakat = Zakat::findOrFail($id);
        $zakat->status = $request->status;
        if ($request->status == 'disalurkan') {
            $zakat->penerima = $request->penerima;
        }
        $zakat->approver_id = auth()->user()->id;
        $zakat->save();


        if ($request->status == 'disalurkan') {
            $zakat = Zakat::findOrFail($id);
            Mail::to($zakat->email)->send(new ZakatMail($zakat));
        }
        return response()->json(['message' => 'Zakat berhasil di update', 'status' => 'success','data' => $zakat, 'statusCode' => 200], 200);
    }


    public function destroy($id)
    {
        $zakat = Zakat::findOrFail($id);
        $zakat->delete();
        return response()->json(['message' => 'Zakat berhasil di hapus', 'status' => 'success','data' => $zakat, 'statusCode' => 200], 200);
    }


}
