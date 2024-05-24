<?php

namespace App\Services\Midtrans;

use Midtrans\Snap;
use Illuminate\Support\Facades\Auth;
use App\Models\Tanaman;
use App\Models\SubPengajuan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateSnapTokenService extends Midtrans
{
    protected $data;

    public function __construct($data)
    {
        parent::__construct();

        $this->data = $data;
    }

    public function getSnapToken()
    {
        $params = [
            'transaction_details' => [
                'order_id' => 'ZAKAT-' . uniqid(),
                'gross_amount' => $this->data['amount'],
            ],
            'customer_details' => [
                'first_name' => $this->data['name'],
                'email' => $this->data['email'],
                'phone' => $this->data['phone'],
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        return $snapToken;
    }
}
