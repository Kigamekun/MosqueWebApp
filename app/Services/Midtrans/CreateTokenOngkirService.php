<?php

namespace App\Services\Midtrans;

use Midtrans\Snap;
use Illuminate\Support\Facades\Auth;
use App\Models\Tanaman;
use App\Models\SubPengajuan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateTokenOngkirService extends Midtrans
{
    protected $pengajuan;

    public function __construct($pengajuan)
    {
        parent::__construct();

        $this->pengajuan = $pengajuan;
    }

    public function getSnapToken()
    {
        $item = [];

            $item[] = [
                'id' => 1,
                'price' =>  $this->pengajuan->ongkir,
                'quantity' => 1,
                'name' => "Biaya Pengiriman",
            ];

            //  $item[] = [
            //     'id' => 2,
            //     'price' =>  $this->pengajuan->biaya_karantina,
            //     'quantity' => 1,
            //     'name' => "BIAYA KARANTINA",
            // ];

        $params = [
            'transaction_details' => [
                'order_id' => $this->pengajuan->pengajuan_id.'-ONGKRN'.Carbon::now()->timestamp,
                'gross_amount' => 1,
            ],
            'item_details' => $item,
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone,
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        return $snapToken;
    }
}
