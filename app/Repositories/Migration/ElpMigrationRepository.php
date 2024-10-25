<?php

namespace Sibas\Repositories\Migration;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Sibas\Entities\Elp\Beneficiary;
use Sibas\Entities\Elp\Cancellation;
use Sibas\Entities\Elp\Collection;
use Sibas\Entities\Elp\CollectionRecord;
use Sibas\Entities\Elp\Detail;
use Sibas\Entities\Elp\Header;

class ElpMigrationRepository
{
    use MigrationTrait;

    public function dataExport()
    {
        $db    = env('DB_DATABASE');

        $query = DB::connection($this->conn_old)
            ->table('ecs_emision_cabecera')
            ->join('ecs_emision_detalle', 'ecs_emision_cabecera.id_emision', '=', 'ecs_emision_detalle.id_emision')
            ->join($db . '.op_clients as n_clients', 'ecs_emision_detalle.id_client', '=', 'n_clients.client_id_old')
            ->join('ecs_beneficiario', 'ecs_emision_detalle.id_detalle', '=', 'ecs_beneficiario.id_detalle')
            ->leftJoin($db . '.ad_users as n_references', 'ecs_emision_detalle.referido_por', '=', 'n_references.username_old')
            ->join('ecs_tipo_pago', 'ecs_emision_detalle.id_tipo_pago', '=', 'ecs_tipo_pago.id_tipo_pago')
            ->join($db . '.ad_users as n_users', 'ecs_emision_cabecera.creado_por', '=', 'n_users.username_old')
            ->join('tbl_poliza', 'ecs_emision_cabecera.id_poliza', '=', 'tbl_poliza.id_poliza')
            ->join('ecs_tipo_estado', 'ecs_emision_cabecera.id_tipo_estado', '=', 'ecs_tipo_estado.id_tipo_estado')
            ->leftJoin($db . '.ad_agencies as n_agencies', 'ecs_emision_cabecera.id_agencia', '=', 'n_agencies.agency_id_old')
            ->select([
                'n_users.id as ad_user_id',
                DB::raw('"I" as type'),
                'ecs_emision_cabecera.id_emision as issue_number',
                'ecs_emision_cabecera.id_cotizacion as quote_number',
                DB::raw('"ECS" as prefix'),
                'tbl_poliza.no_poliza as policy_number',
                DB::raw('1 as ad_coverage_id'),
                'ecs_emision_cabecera.anulado as canceled',
                'ecs_emision_cabecera.motivo_anulado as canceled_reason',
                'ecs_emision_cabecera.fecha_anulado as canceled_date',
                'ecs_emision_cabecera.emitir as issued',
                DB::raw('IF(unix_timestamp(ecs_emision_cabecera.fecha_emision) = 0, null, 
                        ecs_emision_cabecera.fecha_emision) as date_issue'),
                DB::raw('IF(ecs_emision_cabecera.emitir = 1, 1, 0) as approved'),
                DB::raw('0 as rejected'),
                DB::raw('CASE ecs_tipo_estado.id_tipo_estado
                            WHEN 1 THEN 7
                            WHEN 2 THEN 8
                            WHEN 3 THEN 9
                            WHEN 4 THEN 10
                            ELSE null
                            END as ad_state_id'),
                'ecs_emision_cabecera.renovacion as renewal',
                DB::raw('IFNULL(ecs_emision_cabecera.detalle_renovacion, "") as renewal_detail'),
                DB::raw('CASE ecs_emision_cabecera.departamento
                            WHEN "beni" THEN 8
                            WHEN "chuquisaca" THEN 5
                            WHEN "cochabamba" THEN 4
                            WHEN "elalto" THEN 10
                            WHEN "lapaz" THEN 1
                            WHEN "oruro" THEN 2
                            WHEN "potosi" THEN 3
                            WHEN "santacruz" THEN 7
                            WHEN "sucre" THEN 5
                            WHEN "tarija" THEN 6
                            ELSE null
                            END as ad_city_id'),
                'n_agencies.id as ad_agency_id',
                'ecs_emision_cabecera.no_copia as copy',
                'ecs_emision_cabecera.leido as read',
                DB::raw('6 as ad_certificate_id'),
                'ecs_emision_cabecera.fecha_creacion as created_at',
                'ecs_emision_detalle.cliente as client_eco',
                'n_clients.id as op_client_id',
                'ecs_emision_detalle.edad_actual as age',
                'n_references.id as reference_id',
                DB::raw('IF(n_references.id is null, ecs_emision_detalle.referido_por, "") as reference_other'),
                'ecs_emision_detalle.prima as premium',
                DB::raw('CASE ecs_tipo_pago.tipo_pago
                            WHEN "MENSUAL" THEN "DA"
                            WHEN "ANUAL" THEN "CO"
                            ELSE null
                            END as payment_method'),
                DB::raw('CASE ecs_emision_detalle.tipo_cuenta
                            WHEN "ALTERNA" THEN "AC"
                            WHEN "CONJUNTA" THEN "TO"
                            WHEN "INDIVIDUAL" THEN "IC"
                            ELSE null
                            END as account_type'),
                DB::raw('"BS" as currency'),
                DB::raw('IF(ecs_emision_detalle.respuesta_cargo = "si", 1, 0) as charge'),
                'ecs_emision_detalle.cargo as charge_response',
                'ecs_emision_detalle.periodo_cargo as charge_period',
                'ecs_emision_detalle.no_operacion as operation_number',
                DB::raw('CASE ecs_emision_detalle.moneda_operacion
                            WHEN "boliviano" THEN "BS"
                            WHEN "dolar" THEN "USD"
                            ELSE null
                            END as operation_currency'),
                'ecs_emision_detalle.num_voucher as voucher',
                DB::raw('IF(ecs_emision_detalle.cod_sucursal = 0, "", ecs_emision_detalle.cod_sucursal) as subsidiary_code'),
                DB::raw('"VI" as beneficiary_coverage'),
                'ecs_beneficiario.nombre as beneficiary_first_name',
                'ecs_beneficiario.paterno as beneficiary_last_name',
                'ecs_beneficiario.materno as beneficiary_mother_last_name',
                'ecs_beneficiario.ci as beneficiary_dni',
                DB::raw('trim(ecs_beneficiario.parentesco) as beneficiary_relationship'),
                'ecs_beneficiario.participacion as beneficiary_percentage_credit',
            ])
            ->orderBy('ecs_emision_cabecera.id_emision', 'asc');

        $this->executeQuery($query);

        if ($this->data->count() > 1) {
            return true;
        }

        return false;
    }

    /**
     * @param \Illuminate\Support\Collection $data
     * @param \Illuminate\Support\Collection $data_collections
     * @param \Illuminate\Support\Collection $data_collection_records
     */
    public function dataImport($data, $data_collections, $data_collection_records)
    {
        $time_start             = microtime(true);
        $elp_headers            = collect([]);
        $elp_details            = collect([]);
        $elp_beneficiaries      = collect([]);
        $elp_cancellations      = collect([]);
        $elp_collections        = collect([]);
        $elp_collection_records = collect([]);

        $this->date      = new Carbon();
        $header_id       = (int) $this->date->format('U');
        $detail_id       = $header_id + 100;
        $beneficiary_id  = $detail_id + 100;
        $cancellation_id = $header_id + 100;
        $collection_id   = $header_id + 100;
        $created_at      = $this->date->now();
        $retailer        = $this->getRetailer();

        $rp_id = $retailer->retailerProducts->search(function ($item, $key) {
            return $item->companyProduct->product->code === 'elp';
        });
        $rp_id = $retailer->retailerProducts[$rp_id]->id;

        $collections = $data_collections->groupBy(function ($item, $key) {
            return $item['issue_number'];
        })->toArray();

        foreach ($data as $key => $row) {
            $header_id       += 1;
            $detail_id       += 1;
            $beneficiary_id  += 1;
            $cancellation_id += 1;

            $canceled      = filter_var($row['canceled'], FILTER_VALIDATE_BOOLEAN);
            $date_issue    = is_null($row['date_issue']) ? null :
                $this->date->copy()->createFromFormat('Y-m-d', $row['date_issue']);
            $creation_date = $this->date->copy()->createFromFormat('Y-m-d', $row['created_at']);

            $elp_headers->push([
                'id'                     => $header_id,
                'ad_user_id'             => $row['ad_user_id'],
                'ad_retailer_product_id' => $rp_id,
                'type'                   => $row['type'],
                'issue_number'           => $row['issue_number'],
                'quote_number'           => $row['quote_number'],
                'prefix'                 => $row['prefix'],
                'policy_number'          => $row['policy_number'],
                'ad_coverage_id'         => $row['ad_coverage_id'],
                'canceled'               => $canceled,
                'issued'                 => filter_var($row['issued'], FILTER_VALIDATE_BOOLEAN),
                'date_issue'             => $date_issue,
                'approved'               => filter_var($row['approved'], FILTER_VALIDATE_BOOLEAN),
                'rejected'               => filter_var($row['rejected'], FILTER_VALIDATE_BOOLEAN),
                'ad_state_id'            => $row['ad_state_id'],
                'renewal'                => filter_var($row['renewal'], FILTER_VALIDATE_BOOLEAN),
                'renewal_detail'         => $row['renewal_detail'],
                'ad_city_id'             => $row['ad_city_id'],
                'ad_agency_id'           => $row['ad_agency_id'],
                'copy'                   => $row['copy'],
                'read'                   => $row['read'],
                'ad_certificate_id'      => $row['ad_certificate_id'],
                'created_at'             => $creation_date,
                'updated_at'             => $created_at,
            ]);

            if ($canceled) {
                $elp_cancellations->push([
                    'id'               => $cancellation_id,
                    'op_elp_header_id' => $header_id,
                    'ad_user_id'       => $row['ad_user_id'],
                    'reason'           => mb_strtoupper(utf8_decode($row['canceled_reason'])),
                    'created_at'       => $this->date->copy()->createFromFormat('Y-m-d', $row['canceled_date']),
                    'updated_at'       => $created_at,
                ]);
            }

            $elp_details->push([
                'id'                 => $detail_id,
                'op_elp_header_id'   => $header_id,
                'client_eco'         => $row['client_eco'],
                'op_client_id'       => $row['op_client_id'],
                'age'                => $row['age'],
                'reference_id'       => $row['reference_id'],
                'reference_other'    => mb_strtoupper($row['reference_other']),
                'premium'            => $row['premium'],
                'payment_method'     => $row['payment_method'],
                'account_type'       => $row['account_type'],
                'currency'           => $row['currency'],
                'charge'             => filter_var($row['charge'], FILTER_VALIDATE_BOOLEAN),
                'charge_response'    => mb_strtoupper(utf8_decode($row['charge_response'])),
                'charge_period'      => $row['charge_period'],
                'operation_number'   => $row['operation_number'],
                'operation_currency' => $row['operation_currency'],
                'voucher'            => $row['voucher'],
                'subsidiary_code'    => $row['subsidiary_code'],
                'created_at'         => $creation_date,
                'updated_at'         => $created_at,
            ]);

            $elp_beneficiaries->push([
                'id'                => $beneficiary_id,
                'op_elp_detail_id'  => $detail_id,
                'coverage'          => $row['beneficiary_coverage'],
                'first_name'        => mb_strtoupper($row['beneficiary_first_name']),
                'last_name'         => mb_strtoupper($row['beneficiary_last_name']),
                'mother_last_name'  => mb_strtoupper($row['beneficiary_mother_last_name']),
                'dni'               => $row['beneficiary_dni'],
                'extension'         => '',
                'age'               => 0,
                'relationship'      => mb_strtoupper($row['beneficiary_relationship']),
                'percentage_credit' => $row['beneficiary_percentage_credit'],
                'created_at'        => $creation_date,
                'updated_at'        => $created_at,
            ]);

            if (key_exists($row['issue_number'], $collections)) {
                foreach ($collections[$row['issue_number']] as $collection) {
                    $collection_id += 1;
                    $fee_date = $this->date->copy()->createFromFormat('Y-m-d H:i:s', $collection['fee_date'] . ' 00:00:00');
                    $deadline = $this->date->copy()->createFromFormat('Y-m-d H:i:s', $collection['deadline'] . ' 00:00:00');
                    $transaction_date = null;

                    if (! is_null($collection['transaction_date'])) {
                        $transaction_date = $this->date->copy()
                            ->createFromFormat('Y-m-d H:i:s', $collection['transaction_date'] . ' 00:00:00');
                    }

                    $elp_collections->push([
                        'id'                 => $collection_id,
                        'op_elp_header_id'   => $header_id,
                        'fee_number'         => $collection['fee_number'],
                        'fee_date'           => $fee_date,
                        'deadline'           => $deadline,
                        'fee_amount'         => $collection['fee_amount'],
                        'charged'            => filter_var($collection['charged'], FILTER_VALIDATE_BOOLEAN),
                        'transaction_number' => $collection['transaction_number'],
                        'transaction_date'   => $transaction_date,
                        'transaction_amount' => $collection['transaction_amount'],
                        'reason'             => $collection['reason'],
                        'balance'            => $collection['balance'],
                        'created_at'         => $created_at,
                        'updated_at'         => $created_at,
                    ]);
                }
            }
        }

        $elp_collection_records = $data_collection_records->map(function ($item, $key) use ($created_at) {
            $date_shipping = null;
            if (! is_null($item['date_shipping'])) {
                $date_shipping = $this->date->copy()->createFromFormat('Ymd', $item['date_shipping']);
            }

            $item['id']            = null;
            $item['date_shipping'] = $date_shipping;
            $item['confirmation']  = filter_var($item['confirmation'], FILTER_VALIDATE_BOOLEAN);
            $item['created_at']    = $created_at;
            $item['updated_at']    = $created_at;

            return $item;
        });

        $elp_headers       = $elp_headers->chunk(2000);
        $elp_cancellations = $elp_cancellations->chunk(2000);
        $elp_details       = $elp_details->chunk(2000);
        $elp_beneficiaries = $elp_beneficiaries->chunk(2000);
        $elp_collections   = $elp_collections->chunk(2000);

        foreach ($elp_headers as $header) {
            Header::insert($header->toArray());
        }

        foreach ($elp_cancellations as $cancellation) {
            Cancellation::insert($cancellation->toArray());
        }

        foreach ($elp_details as $detail) {
            Detail::insert($detail->toArray());
        }

        foreach ($elp_beneficiaries as $beneficiary) {
            Beneficiary::insert($beneficiary->toArray());
        }

        foreach ($elp_collections as $collection) {
            Collection::insert($collection->toArray());
        }

        CollectionRecord::insert($elp_collection_records->toArray());

        // var_dump('Headers. Total execution time in seconds: ' . (microtime(true) - $time_start) . '. Total: ' . Collection::count());
    }
}