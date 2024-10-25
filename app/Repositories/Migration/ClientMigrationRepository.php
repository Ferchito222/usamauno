<?php

namespace Sibas\Repositories\Migration;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Sibas\Entities\Activity;
use Sibas\Entities\AdRetailerProductActivities;
use Sibas\Entities\Client;

class ClientMigrationRepository
{
    use MigrationTrait;

    public function dataExport()
    {
        $query = DB::connection($this->conn_old)
            ->table('tbl_clients')
            ->leftJoin('ecs_emision_detalle', 'tbl_clients.id_client', '=', 'ecs_emision_detalle.id_client')
            ->leftJoin('tblocupacionde', 'tbl_clients.ocupacion', '=', 'tblocupacionde.idocupacion')
            ->select([
                'tbl_clients.id_client as client_id',
                DB::raw('IFNULL(ecs_emision_detalle.cod_agenda, "") as code'),
                DB::raw('"N" as type'),
                'tbl_clients.nombres as first_name',
                'tbl_clients.ap_paterno as last_name',
                'tbl_clients.ap_materno as mother_last_name',
                'tbl_clients.ap_casada as married_name',
                'tbl_clients.fecha_nac as birthdate',
                DB::raw('IFNULL(tbl_clients.lugar_nacimiento, "") as birth_place'),
                'tbl_clients.ci_persona as dni',
                DB::raw('CASE tbl_clients.ci_ext
                            WHEN "beni" THEN "BE"
                            WHEN "chuquisaca" THEN "CH"
                            WHEN "cochabamba" THEN "CB"
                            WHEN "lapaz" THEN "LP"
                            WHEN "oruro" THEN "OR"
                            WHEN "pando" THEN "PA"
                            WHEN "pe" THEN "PE"
                            WHEN "potosi" THEN "PO"
                            WHEN "santacruz" THEN "SC"
                            WHEN "sucre" THEN "CH"
                            WHEN "tarija" THEN "TJ"
                            ELSE ""
                            END as extension'),
                'tbl_clients.complemento as complement',
                DB::raw('CASE tbl_clients.tipodocumento
                            WHEN "ci" THEN "CI"
                            WHEN "otro" THEN "PE"
                            WHEN "pasaporte" THEN "PA"
                            ELSE "CI"
                            END as document_type'),
                DB::raw('CASE tbl_clients.estado_civil
                            WHEN "CAS" THEN "C"
                            WHEN "CON" THEN "F"
                            WHEN "DIV" THEN "D"
                            WHEN "MUJER" THEN "S"
                            WHEN "SOL" THEN "S"
                            WHEN "VARON" THEN "S"
                            WHEN "VIU" THEN "V"
                            ELSE "S"
                            END as civil_status'),
                DB::raw('CASE tbl_clients.sexo
                            WHEN "varon" THEN "M"
                            WHEN "mujer" THEN "F"
                            ELSE "M"
                            END as gender'),
                DB::raw('CASE tbl_clients.ciudad_domicilio
                            WHEN "beni" THEN "beni"
                            WHEN "chuquisaca" THEN "chuquisaca"
                            WHEN "cochabamba" THEN "cochabamba"
                            WHEN "LA PAZ" THEN "la-paz"
                            WHEN "lapaz" THEN "la-paz"
                            WHEN "oruro" THEN "oruro"
                            WHEN "pando" THEN "pando"
                            WHEN "potosi" THEN "potosi"
                            WHEN "santacruz" THEN "santa-cruz"
                            WHEN "sucre" THEN "chuquisaca"
                            WHEN "tarija" THEN "tarija"
                            ELSE ""
                            END as place_residence'),
                DB::raw('IFNULL(tbl_clients.ciudad_localidad, "") as locality'),
                DB::raw('IFNULL(tbl_clients.dir_domicilio, "") as home_address'),
                DB::raw('IFNULL(tbl_clients.num_domicilio, "") as home_number'),
                DB::raw('CASE tbl_clients.avenida_calle
                            WHEN "avenida" THEN "A"
                            WHEN "calle" THEN "S"
                            ELSE null
                            END as avenue_street'),
                DB::raw('IFNULL(tbl_clients.dir_laboral, "") as business_address'),
                DB::raw('trim(tbl_clients.pais) as country'),
                DB::raw('IFNULL(tblocupacionde.idocupacion, null) as activity_id'),
                DB::raw('IF(tblocupacionde.idocupacion is null, "", tblocupacionde.ocupacion) as activity_occupation'),
                DB::raw('IF(tbl_clients.desc_ocupacion is null, "", trim(tbl_clients.desc_ocupacion)) as occupation_description'),
                DB::raw('IF(tbl_clients.tel_domicilio = 0, "", tbl_clients.tel_domicilio) as phone_number_home'),
                DB::raw('IF(tbl_clients.tel_oficina = 0, "", tbl_clients.tel_oficina) as phone_number_office'),
                DB::raw('IF(tbl_clients.tel_movil = 0, "", tbl_clients.tel_movil) as phone_number_mobile'),
                DB::raw('IFNULL(tbl_clients.email, "") as email'),

            ])
            ->where('tbl_clients.id_client', '!=', 0)
            ->groupBy('tbl_clients.id_client');

        $this->executeQuery($query);

        if ($this->data->count() > 1) {
            return true;
        }

        return false;
    }

    /**
     * @param Collection $data
     * @param Collection $activities
     */
    public function dataImport($data, $activities)
    {
        $time_start    = microtime(true);
        $clients       = collect([]);
        $rp_activities = collect([]);

        $this->date = new Carbon();
        $id         = (int) $this->date->format('U');
        $rpa_id     = 0;
        $created_at = $this->date->now();
        $retailer   = $this->getRetailer();

        $activities = $activities->map(function ($item, $key) use (&$rp_activities, $retailer, &$rpa_id, $created_at) {
            $item['occupation'] = mb_strtoupper($item['occupation']);
            $item['category']   = '';
            $item['created_at'] = $created_at;
            $item['updated_at'] = $created_at;

            foreach ($retailer->retailerProducts as $retailerProduct) {
                $rpa_id += 1;

                $rp_activities->push([
                    'id'                     => $rpa_id,
                    'ad_retailer_product_id' => $retailerProduct->id,
                    'ad_activity_id'         => $item['id'],
                    'active'                 => true,
                    'created_at'             => $created_at,
                    'updated_at'             => $created_at,
                ]);
            }

            return $item;
        });

        $act_woo_id = $activities->search(function ($item, $key) {
            return $item['occupation'] === 'SIN OCUPACIÓN';
        });
        $act_woo_id = ($act_woo_id === false) ? 1 : $activities[$act_woo_id]['id'];

        foreach ($data as $key => $row) {
            $id += 1;
            $birthdate = $this->date->copy()->createFromFormat('Y-m-d', $row['birthdate']);

            $clients->push([
                'id'                     => $id,
                'ad_retailer_id'         => $retailer->id,
                'client_id_old'          => $row['client_id'],
                'code'                   => $row['code'],
                'type'                   => $row['type'],
                'business_name'          => '',
                'first_name'             => mb_strtoupper($row['first_name']),
                'last_name'              => mb_strtoupper($row['last_name']),
                'mother_last_name'       => mb_strtoupper($row['mother_last_name']),
                'married_name'           => mb_strtoupper($row['married_name']),
                'birthdate'              => $birthdate->toDateString(),
                'age'                    => $birthdate->age,
                'birth_place'            => $row['birth_place'],
                'dni'                    => $row['dni'],
                'extension'              => $row['extension'],
                'complement'             => $row['complement'],
                'document_type'          => $row['document_type'],
                'dni_file'               => '',
                'civil_status'           => $row['civil_status'],
                'gender'                 => $row['gender'],
                'place_residence'        => $row['place_residence'],
                'locality'               => $row['locality'],
                'home_address'           => $row['home_address'],
                'home_number'            => $row['home_number'],
                'avenue_street'          => empty($row['avenue_street']) ? null : $row['avenue_street'],
                'business_address'       => $row['business_address'],
                'country'                => $row['country'],
                'ad_activity_id'         => empty($row['activity_id']) ? $act_woo_id : $row['activity_id'],
                'occupation_description' => $row['occupation_description'],
                'phone_number_home'      => $row['phone_number_home'],
                'phone_number_office'    => $row['phone_number_office'],
                'phone_number_mobile'    => $row['phone_number_mobile'],
                'email'                  => $row['email'],
                'weight'                 => 0,
                'height'                 => 0,
                'hand'                   => null,
                'debit_balance'          => 0,
                'created_at'             => $created_at,
                'updated_at'             => $created_at,
            ]);
        }

        Activity::insert($activities->toArray());
        AdRetailerProductActivities::insert($rp_activities->toArray());

        $clients = $clients->chunk(1500);
        foreach ($clients as $client) {
            Client::insert($client->toArray());
        }

        // var_dump('Clients. Total execution time in seconds: ' . (microtime(true) - $time_start) . '. Total: ' . Client::count());
    }

    public function dataActivityExport()
    {
        $query = DB::connection($this->conn_old)
            ->table('tblocupacionde')
            ->select([
                'idocupacion as id',
                'ocupacion as occupation',
                'idocupacion as code',
            ]);

        $this->executeQuery($query);

        if ($this->data->count() > 1) {
            return true;
        }

        return false;
    }
}