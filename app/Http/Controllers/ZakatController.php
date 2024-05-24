<?php

namespace App\Http\Controllers;

use App\Models\Zakat;
use Illuminate\Http\Request;

class ZakatController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MateriPengetahuan::whereNull('deleted_at')->select('id', 'judul_materi', 'tahun_materi', 'file_materi')->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn = '
                        <div>
                            <form id="deleteForm" action="'.route('materi-pengetahuan.delete', ['id' => $row->id]).'" method="POST">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                                <button type="button" title="DELETE" class="btn btn-sm btn-biru btn-delete" onclick="confirmDelete(event)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>';
                        return $btn;
                    })
                    ->addColumn('priview-pdf', function ($row) {
                        $btn = '
                        <div class="d-flex">
                            <a class="btn btn-sm btn-biru" title="Lihat Materi Pengetahuan"
                                href="' . asset('storage/materiPengetahuan/'.$row->file_materi) . '"  onclick="window.open(this.href, \'_blank\', \'width=800,height=600\'); return false;"> <i class="bi bi-eye"></i>
                            </a>
                        </div>
                        ';
                        return $btn;
                    })
                    ->rawColumns(['action', 'priview-pdf'])
                    ->make(true);
        }
        return view('materi-pengetahuan.index');
    }

    public function guest(Request $request)
    {
        if ($request->ajax()) {
            $data = MateriPengetahuan::whereNull('deleted_at')->select('id', 'judul_materi', 'tahun_materi', 'file_materi')->get();
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('priview-pdf', function ($row) {
                $btn = '
                <div class="d-flex">
                    <a class="btn btn-sm btn-biru"
                        href="' . asset('storage/materiPengetahuan/'.$row->file_materi) . '" target="_blank"> <i class="bi bi-trash"></i> Lihat
                    </a>
                </div>
                ';
                return $btn;
            })
            ->rawColumns(['priview-pdf'])
            ->make(true);
        }
        return view('materi-pengetahuan.guest');
    }

    public function store(CreateMateriPengetahuanRequest $request)
    {
        DB::beginTransaction();

        try {
            $this->validate($request, [
                'file_materi' => 'required|file|mimes:pdf|max:30720',
            ]);

            $file = $request->file('file_materi');
            $filename = time() . '-' . $file->getClientOriginalName();
            Storage::disk('public')->put('materiPengetahuan/'.$filename, file_get_contents($file));

            $data = $request->validated();
            $data['file_materi'] = $filename;

            MateriPengetahuan::create($data);

            DB::commit();

            return redirect()->back()->with(['message' => 'Materi berhasil ditambahkan','status' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with(['message' => $th->getMessage(),'status' => 'error']);
        }
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
