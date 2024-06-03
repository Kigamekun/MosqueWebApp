<?php

namespace App\Http\Controllers;

use App\Models\{Infaq,User};
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;


class InfaqController extends Controller
{
    public function index(Request $request)
    {
        $infaq = DB::select(' SELECT infaqs.* ,users.name  FROM public.infaqs RIGHT JOIN public.users ON infaqs.verifier_id = users.id ORDER BY infaqs.amount DESC');
        return response()->json(['message' => 'Data berhasil di load', 'status' => 'success','data' => ['data'=>$infaq],'totalInfaq'=>'Rp. '.number_format(Infaq::sum('amount') , 0, ',', '.'), 'statusCode' => 200], 200);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required',
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Data tidak lengkap', 'status' => 'error', 'statusCode' => 400,'errors' => $th->validator->errors()], 400);
        }

        $infaq = new Infaq();
        $infaq->amount = $request->amount;
        $infaq->title = $request->title;

        $infaq->date = now();
        $infaq->verifier_id  = auth()->user()->id;

        $infaq->save();

        $newInfaqId = $infaq->id;

        $infaq = Infaq::findOrFail($newInfaqId);
        $infaq->user = User::find($infaq->verifier_id);

        return response()->json(['message' => 'Infaq berhasil di tambahkan', 'status' => 'success','data' => $infaq, 'statusCode' => 200], 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required',
                'description' => 'required',
                'amount' => 'required',
            ]);

        } catch (\Throwable $th) {
            return response()->json(['message' => 'Data tidak lengkap', 'status' => 'error', 'statusCode' => 400,'errors' => $th->validator->errors()], 400);
        }

        $infaq = Infaq::findOrFail($id);
        $infaq->title = $request->title;
        $infaq->description = $request->description;
        $infaq->amount = $request->amount;
        $infaq->verifier_id  = auth()->user()->id;

        $infaq->save();

        return response()->json(['message' => 'Infaq berhasil di update', 'status' => 'success','data' => $infaq, 'statusCode' => 200 ], 200);
    }

    public function destroy($id)
    {
        $infaq = Infaq::findOrFail($id);
        $infaq->delete();
        return response()->json(['message' => 'Infaq berhasil di hapus', 'status' => 'success','data' => $infaq, 'statusCode' => 200], 200);
    }

    public function show($id)
    {
        $infaq = Infaq::findOrFail($id);
        return response()->json(['message' => 'Data berhasil di load', 'status' => 'success','data' => $infaq, 'statusCode' => 200], 200);
    }

}
