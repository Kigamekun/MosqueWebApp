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

    public function bayar(Request $request)
    {
        $midtrans = new CreateSnapTokenService($request->all());
        $snapToken = $midtrans->getSnapToken();
        return response()->json(['message' => 'Token berhasil di load', 'status' => 'success','data' => $snapToken, 'statusCode' => 200], 200);
    }

    public function store(Request $request)
    {
        // try {
        //     $request->validate([
        //         'title' => 'required',
        //         'description' => 'required',
        //         'start_date' => 'required',
        //         'end_date' => 'required',

        //     ]);
        // } catch (\Throwable $th) {
        //     return response()->json(['message' => 'Data tidak lengkap', 'status' => 'error', 'statusCode' => 400,'errors' => $th->validator->errors()], 400);
        // }

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
        // $zakat->approver_id = auth()->user()->id;
        $zakat->save();

        if ($request->status == 'disalurkan') {
            Mail::to($zakat->email)->send(new ZakatMail());
        }

        return response()->json(['message' => 'Zakat berhasil di update', 'status' => 'success','data' => $zakat, 'statusCode' => 200], 200);
    }



}
