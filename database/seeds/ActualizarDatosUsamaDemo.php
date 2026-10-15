<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActualizarDatosUsamaDemo extends Seeder
{
    public function run()
    {
        DB::beginTransaction();

        try {
            $ahora = date('Y-m-d H:i:s');

            /*
             * 1. CERTIFICADOS
             * Si ya existe el certificado equivalente, no duplicarlo.
             */
            $this->insertarSiNoExiste(
                'ad_certificates',
                array(
                    'ad_retailer_product_id' => 1474321374,
                    'version' => 1.0,
                    'template' => 'comercial-v2',
                    'active' => 1,
                ),
                array(
                    'css' => '',
                ),
                $ahora
            );

            // Desactivar certificado ID 62 solamente si es necesario.
            $this->actualizarSiCambio(
                'ad_certificates',
                62,
                array('active' => 0),
                $ahora
            );

            /*
             * 2. PARÁMETROS DEL PRODUCTO
             */
            $this->actualizarSiCambio(
                'ad_product_parameters',
                2,
                array('amount_max' => 50000.0),
                $ahora
            );

            $this->actualizarSiCambio(
                'ad_product_parameters',
                4,
                array('amount_max' => 600000.0),
                $ahora
            );

            $this->actualizarSiCambio(
                'ad_product_parameters',
                31,
                array('amount_min' => 50001.0),
                $ahora
            );

            /*
             * 3. TASAS
             * Se identifica cada tasa por producto, cobertura y año.
             */
            $this->insertarSiNoExiste(
                'ad_rates',
                array(
                    'ad_retailer_product_id' => 1474321374,
                    'ad_coverage_id' => 1,
                    'year' => 0,
                    'active' => 1,
                ),
                array(
                    'rate_company' => 0.0,
                    'rate_accidental_death' => 0.0175,
                    'rate_preference' => 0.8,
                    'rate_bank' => 0.0,
                    'rate_damage_insurance' => 0.0,
                    'rate_final' => 1.04,
                ),
                $ahora
            );

            $this->insertarSiNoExiste(
                'ad_rates',
                array(
                    'ad_retailer_product_id' => 1474321374,
                    'ad_coverage_id' => 2,
                    'year' => 0,
                    'active' => 1,
                ),
                array(
                    'rate_company' => 0.0,
                    'rate_accidental_death' => 0.0,
                    'rate_preference' => 0.0,
                    'rate_bank' => 0.0,
                    'rate_damage_insurance' => 0.0,
                    'rate_final' => 1.86,
                ),
                $ahora
            );

            $this->actualizarSiCambio(
                'ad_rates',
                35,
                array('active' => 0),
                $ahora
            );

            $this->actualizarSiCambio(
                'ad_rates',
                36,
                array('active' => 0),
                $ahora
            );

            /*
             * 4. PÓLIZAS
             */
            $this->insertarSiNoExiste(
                'ad_policies',
                array(
                    'ad_retailer_product_id' => 1474321374,
                    'number' => 'POL-DHB-LP-000048-2023-01',
                    'active' => 1,
                ),
                array(
                    'ad_city_id' => 1,
                    'end_policy' => '0',
                    'initial' => 2,
                    'date_begin' => '2026-11-01',
                    'date_end' => '2029-11-01',
                    'modality' => '',
                    'type' => 'VI',
                    'auto_increment' => 0,
                    'number_renewal' => '4',
                ),
                $ahora
            );

            $this->actualizarSiCambio(
                'ad_policies',
                45,
                array('active' => 0),
                $ahora
            );

            DB::commit();

            echo "Actualizacion completada correctamente: ActulizarDatosUsamaDemo.\n";

        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Inserta solo si no existe un registro activo con las claves indicadas.
     * El ID se genera automáticamente si la tabla usa AUTO_INCREMENT.
     */
    private function insertarSiNoExiste(
        $tabla,
        $claves,
        $datos,
        $ahora
    ) {
        $consulta = DB::table($tabla);

        foreach ($claves as $campo => $valor) {
            $consulta->where($campo, '=', $valor);
        }

        // Solo considerar como existente si está activo (para evitar duplicados)
        $consulta->where('active', '=', 1);

        $existe = $consulta->first();

        if ($existe) {
            // Si ya existe y está activo, no duplicar ni sobrescribir sus datos.
            return false;
        }

        $registro = array_merge($claves, $datos);

        $registro['created_at'] = $ahora;
        $registro['updated_at'] = $ahora;

        DB::table($tabla)->insert($registro);

        return true;
    }

    /**
     * Actualiza por ID únicamente si uno o más valores cambiaron.
     */
    private function actualizarSiCambio(
        $tabla,
        $id,
        $datos,
        $ahora
    ) {
        $registro = DB::table($tabla)
            ->where('id', '=', $id)
            ->first();

        if (!$registro) {
            return false;
        }

        $cambios = array();

        foreach ($datos as $campo => $nuevoValor) {
            $valorActual = $registro->$campo;

            // Comparación compatible con valores numéricos de SQL.
            if ($valorActual === null || $nuevoValor === null) {
                $iguales = ($valorActual === $nuevoValor);
            } elseif (
                is_numeric($valorActual) &&
                is_numeric($nuevoValor)
            ) {
                $iguales = ((string) $valorActual === (string) $nuevoValor)
                    || ((float) $valorActual === (float) $nuevoValor);
            } else {
                $iguales = ((string) $valorActual === (string) $nuevoValor);
            }

            if (!$iguales) {
                $cambios[$campo] = $nuevoValor;
            }
        }

        if (empty($cambios)) {
            return false;
        }

        $cambios['updated_at'] = $ahora;

        DB::table($tabla)
            ->where('id', '=', $id)
            ->update($cambios);

        return true;
    }
}
