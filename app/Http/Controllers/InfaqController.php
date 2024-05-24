<?php

namespace App\Http\Controllers;

use App\Models\Infaq;
use Illuminate\Http\Request;

class InfaqController extends Controller
{
    public function index(Request $request)
    {
    }

    public function store(Request $request)
    {

    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $materiPengetahuan = MateriPengetahuan::findOrFail($id);
            if ($materiPengetahuan->file_materi) {
                $filePath = Storage::disk('public')->path('materiPengetahuan/'.$materiPengetahuan->file_materi);
                File::delete($filePath);
            }
            $materiPengetahuan->delete();

            DB::commit();

            return redirect()->back()->with(['message' => 'Materi berhasil di Hapus','status' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with(['message' => $th->getMessage(),'status' => 'error']);
        }
    }
}
