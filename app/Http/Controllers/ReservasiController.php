<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    public function index(Request $request)
    {
        if (isset($_GET['search'])) {
            $search = $_GET['search'];
            $reservasi = Reservasi::where('title', 'like', '%' . $search . '%')->paginate(10);
        } else {
            $reservasi = Reservasi::paginate(10);
        }
        return response()->json(['message' => 'Data berhasil di load', 'status' => 'success','data' => $reservasi, 'statusCode' => 200], 200);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required',
                'description' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Data tidak lengkap', 'status' => 'error', 'statusCode' => 400,'errors' => $th->validator->errors()], 400);
        }

        $reservasi = new Reservasi();
        $reservasi->title = $request->title;
        $reservasi->description = $request->description;
        $reservasi->start_date = $request->start_date;
        $reservasi->end_date = $request->end_date;

        $reservasi->save();

        return response()->json(['message' => 'Reservasi berhasil di tambahkan', 'status' => 'success','data' => $reservasi, 'statusCode' => 200], 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required',
                'description' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ]);

        } catch (\Throwable $th) {
            return response()->json(['message' => 'Data tidak lengkap', 'status' => 'error', 'statusCode' => 400,'errors' => $th->validator->errors()], 400);
        }

        $reservasi = Reservasi::findOrFail($id);
        $reservasi->title = $request->title;
        $reservasi->description = $request->description;
        $reservasi->start_date = $request->start_date;
        $reservasi->end_date = $request->end_date;

        $reservasi->save();

        return response()->json(['message' => 'Reservasi berhasil di update', 'status' => 'success','data' => $reservasi, 'statusCode' => 200], 200);
    }

    public function destroy($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $reservasi->delete();

        return response()->json(['message' => 'Reservasi berhasil di hapus', 'status' => 'success', 'statusCode' => 200], 200);
    }


}
