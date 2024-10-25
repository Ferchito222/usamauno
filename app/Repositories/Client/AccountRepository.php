<?php

namespace Sibas\Repositories\Client;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Sibas\Entities\Account;
use Sibas\Repositories\BaseRepository;

class AccountRepository extends BaseRepository
{
    public function storeAccount(Request $request)
    {
        $this->data = $request->all();

        if ($this->data['payment_method'] === 'CO') {
            return true;
        }

        $detail   = $this->data['detail'];

        $accounts = [
            [
                'id'           => date('U'),
                'op_client_id' => $detail->client->id,
                'number'       => $this->data['account_number'],
                'type'         => 'AC',
                'active'       => true,
            ]
        ];

        if (! empty($this->data['credit_card'])) {
            $accounts[] = [
                'id'           => date('U') + rand(10, 20),
                'op_client_id' => $detail->client->id,
                'number'       => $this->data['credit_card'],
                'type'         => 'CC',
                'active'       => false,
            ];
        }

        try {
            if (Account::insert($accounts)) {
                return true;
            }
        } catch(QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }
}