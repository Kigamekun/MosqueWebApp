<?php

namespace App\Http\Controllers;

use App\Models\{Infaq,User};
use Illuminate\Http\Request;
use DataTables;

class InfaqController extends Controller
{
    public function index(Request $request)
    {
        if (isset($_GET['search'])) {
            $search = $_GET['search'];
            $infaq = Infaq::where('title', 'like', '%' . $search . '%')->paginate(100);
        } elseif (isset($_GET['start_date']) && isset($_GET['end_date'])) {
            $start_date = $_GET['start_date'];
            $end_date = $_GET['end_date'];
            $infaq = Infaq::whereBetween('date', [$start_date, $end_date])->paginate(100);
        } elseif (isset($_GET['start_date'])) {
            $start_date = $_GET['start_date'];
            $infaq = Infaq::where('date', '>=', $start_date)->paginate(100);
        } elseif (isset($_GET['end_date'])) {
            $end_date = $_GET['end_date'];
            $infaq = Infaq::where('date', '<=', $end_date)->paginate(100);
        } elseif (isset($_GET['verifier_id'])) {
            $verifier_id = $_GET['verifier_id'];
            $infaq = Infaq::where('verifier_id', $verifier_id)->paginate(100);
        } elseif (isset($_GET['amount'])) {
            $amount = $_GET['amount'];
            $infaq = Infaq::where('amount', $amount)->paginate(100);
        } elseif (isset($_GET['amount_min']) && isset($_GET['amount_max'])) {
            $amount_min = $_GET['amount_min'];
            $amount_max = $_GET['amount_max'];
            $infaq = Infaq::whereBetween('amount', [$amount_min, $amount_max])->paginate(100);
        } elseif (isset($_GET['amount_min'])) {
            $amount_min = $_GET['amount_min'];
            $infaq = Infaq::where('amount', '>=', $amount_min)->paginate(100);
        } elseif (isset($_GET['amount_max'])) {
            $amount_max = $_GET['amount_max'];
            $infaq = Infaq::where('amount', '<=', $amount_max)->paginate(100);
        } elseif (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
            $infaq = Infaq::orderBy('amount', $sort)->paginate(100);
        } elseif (isset($_GET['sort_date'])) {
            $sort_date = $_GET['sort_date'];
            $infaq = Infaq::orderBy('date', $sort_date)->paginate(100);
        } elseif (isset($_GET['sort_verifier'])) {
            $sort_verifier = $_GET['sort_verifier'];
            $infaq = Infaq::orderBy('verifier_id', $sort_verifier)->paginate(100);
        } else {
            $infaq = Infaq::paginate(100);
            $infaq->getCollection()->transform(function ($in) {
                $in->user = User::find($in->verifier_id);
                return $in;
            });
        }
        return response()->json(['message' => 'Data berhasil di load', 'status' => 'success','data' => $infaq, 'statusCode' => 200], 200);
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
