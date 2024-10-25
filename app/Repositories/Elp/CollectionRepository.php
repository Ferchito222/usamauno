<?php

namespace Sibas\Repositories\Elp;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Sibas\Entities\Elp\Collection;
use Sibas\Entities\Elp\Header;
use Sibas\Repositories\BaseRepository;

class CollectionRepository extends BaseRepository
{

    /**
     * @param Model|Header $header
     *
     * @return bool
     */
    public function createCollections($header)
    {
        $payment_method     = $header->detail->payment_method;
        $premium            = $header->detail->premium;
        $fee_numbers        = 1;
        $date               = $this->carbon->now();
        $fee_date           = $date->copy()->format('Y-m-d');
        $deadline           = $date->copy()->addYear()->format('Y-m-d');
        $charged            = false;
        $transaction_number = 0;
        $transaction_date   = null;
        $transaction_amount = 0;
        $id                 = $date->copy()->subYear()->subSeconds(60)->timestamp;
        $collections        = [];

        $find = Collection::where([
            'op_elp_header_id' => $header->id,
        ])->whereBetween('fee_date',
            [ $date->copy()->format('Y-m-d'), $date->copy()->addYear()->format('Y-m-d') ])->get();

        if ($find->count() === 0) {
            if ($header->detail->client_eco) {
                if ($payment_method === 'DA') {             // Mensual
                    $premium     = $premium / 11;
                    $fee_numbers = 12;
                }
            }

            for ($n = 1; $n <= $fee_numbers; $n++) {
                $fee_amount = $premium;

                if ($header->detail->client_eco) {
                    if ($payment_method === 'DA') {
                        if ($n === 1) {
                            $days           = $date->daysInMonth;
                            $days_remaining = $days - $date->day;
                            $deadline       = $date->copy()->format('Y-m-' . $days);

                            if ($days_remaining > 0) {
                                $prorate    = ( $premium / $days ) * $days_remaining;
                                $fee_amount = $prorate;
                            }
                        } else {
                            $fee_date = $date->copy()->addMonths($n - 1)->format('Y-m-01');
                            $deadline = $date->copy()->addMonths($n - 1)->format('Y-m-t');
                        }
                    }
                } else {
                    $charged            = true;
                    $transaction_number = $header->detail->voucher;
                    $transaction_date   = $date->copy()->format('Y-m-d');
                    $transaction_amount = $fee_amount;
                }

                array_push($collections, [
                    'id'                 => $id + $n,
                    'fee_number'         => $n,
                    'fee_date'           => $fee_date,
                    'deadline'           => $deadline,
                    'fee_amount'         => $fee_amount,
                    'charged'            => $charged,
                    'transaction_number' => $transaction_number,
                    'transaction_date'   => $transaction_date,
                    'transaction_amount' => $transaction_amount,
                ]);
            }

            if (count($collections) > 0) {
                try {
                    $header->collections()->createMany($collections);

                    return true;
                } catch (QueryException $e) {
                    $this->errors = $e->getMessage();
                }
            }
        }

        return false;
    }
}