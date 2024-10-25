<?php

namespace Sibas\Repositories\Migration;

use Illuminate\Support\Facades\DB;

class ElpCollectionMigrationRepository
{
    use MigrationTrait;

    public function dataCollectionExport()
    {
        $query = DB::connection($this->conn_old)
            ->table('ecs_cobranza')
            ->join('ecs_emision_cabecera', 'ecs_cobranza.id_emision', '=', 'ecs_emision_cabecera.id_emision')
            ->select([
                'ecs_cobranza.id_emision as issue_number',
                'ecs_cobranza.numero_cuota as fee_number',
                'ecs_cobranza.fecha_cuota as fee_date',
                'ecs_cobranza.fecha_limite as deadline',
                'ecs_cobranza.monto_cuota as fee_amount',
                'ecs_cobranza.cobrado as charged',
                'ecs_cobranza.numero_transaccion as transaction_number',
                DB::raw('IF(unix_timestamp(ecs_cobranza.fecha_transaccion) = 0, null, 
                        ecs_cobranza.fecha_transaccion) as transaction_date'),
                'ecs_cobranza.monto_transaccion as transaction_amount',
                'ecs_cobranza.motivo as reason',
                DB::raw('IFNULL(ecs_cobranza.saldo, 0) as balance'),
            ])
            ->orderBy('ecs_cobranza.id_emision', 'asc');

        $this->executeQuery($query);

        if ($this->data->count() > 1) {
            return true;
        }

        return false;
    }

    public function dataCollectionRecordExport()
    {
        $query = DB::connection($this->conn_old)
            ->table('ecs_cob_historico')
            ->select([
                DB::raw('timestamp(ecs_cob_historico.fecha_creacion, ecs_cob_historico.hora_creacion) as date'),
                'ecs_cob_historico.total_registro as total_record',
                'ecs_cob_historico.total_monto as total_amount',
                DB::raw('IF(ecs_cob_historico.fecha_envio = 0, null, ecs_cob_historico.fecha_envio) as date_shipping'),
                'ecs_cob_historico.confirmar as confirmation',
            ])
            ->orderBy('ecs_cob_historico.id_historico', 'asc');

        $this->executeQuery($query);

        if ($this->data->count() > 1) {
            return true;
        }

        return false;
    }
}