<?php

namespace Sibas\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PolicyRateMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'policy:migrate-rates {--force : Ejecuta sin pedir confirmación}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deshabilita la póliza/rates actuales del producto y da de alta la nueva póliza con sus rates, calculando los IDs automáticamente';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $next_policy_id = DB::table('ad_policies')->max('id') + 1;
        $next_rate_id_1 = DB::table('ad_rates')->max('id') + 1;
        $next_rate_id_2 = $next_rate_id_1 + 1;

        $this->info("Nuevo id de ad_policies: {$next_policy_id}");
        $this->info("Nuevos ids de ad_rates: {$next_rate_id_1}, {$next_rate_id_2}");

        if (! $this->option('force') && ! $this->confirm('¿Ejecutar estos cambios en la base de datos?')) {
            $this->info('Cancelado, no se realizó ningún cambio.');

            return;
        }

        DB::transaction(function () use ($next_policy_id, $next_rate_id_1, $next_rate_id_2) {
            // Deshabilitar la póliza actual
            DB::table('ad_policies')->where('id', 45)->update(['active' => 0]);

            // Agregar póliza nueva (si no existe ya)
            $policy_exists = DB::table('ad_policies')
                ->where('number', 'CRS-DE-SG-013')
                ->where('ad_retailer_product_id', 1474321374)
                ->exists();

            if ($policy_exists) {
                $this->info('La póliza CRS-DE-SG-013 ya existe, no se vuelve a registrar.');
            } else {
                DB::table('ad_policies')->insert([
                    'id'                      => $next_policy_id,
                    'ad_retailer_product_id'  => 1474321374,
                    'ad_city_id'              => 1,
                    'ad_agency_id'            => null,
                    'number'                  => 'CRS-DE-SG-013',
                    'end_policy'              => '0',
                    'initial'                 => 2,
                    'date_begin'              => '2026-09-01',
                    'date_end'                => '2027-09-01',
                    'currency'                => null,
                    'modality'                => '',
                    'type'                    => 'VI',
                    'auto_increment'          => 0,
                    'active'                  => 1,
                    'number_renewal'          => '4',
                    'created_at'              => Carbon::now(),
                    'updated_at'              => Carbon::now(),
                ]);
            }

            // Actualizar los parámetros del producto
            DB::table('ad_product_parameters')->where('id', 2)->update(['amount_max' => 50000.0]);
            DB::table('ad_product_parameters')->where('id', 4)->update(['amount_max' => 600000.0]);
            DB::table('ad_product_parameters')->where('id', 31)->update(['amount_min' => 50001.0]);

            // Agregar los rates nuevos (si no existen ya)
            $rate_1_exists = DB::table('ad_rates')
                ->where('ad_retailer_product_id', 1474321374)
                ->where('ad_coverage_id', 1)
                ->whereRaw('ABS(rate_final - ?) < 0.0001', [1.04])
                ->exists();

            if ($rate_1_exists) {
                $this->info('El rate (coverage 1, rate_final 1.04) ya existe, no se vuelve a registrar.');
            } else {
                DB::table('ad_rates')->insert([
                    'id'                     => $next_rate_id_1,
                    'rate_company'           => 0.0,
                    'rate_accidental_death'  => 0.0175,
                    'rate_preference'        => 0.8,
                    'rate_bank'              => 0.0,
                    'rate_damage_insurance'  => 0.0,
                    'rate_final'             => 1.04,
                    'year'                   => 0,
                    'ad_retailer_product_id' => 1474321374,
                    'ad_credit_product_id'   => null,
                    'ad_coverage_id'         => 1,
                    'active'                 => 1,
                    'created_at'             => Carbon::now(),
                    'updated_at'             => Carbon::now(),
                ]);
            }

            $rate_2_exists = DB::table('ad_rates')
                ->where('ad_retailer_product_id', 1474321374)
                ->where('ad_coverage_id', 2)
                ->whereRaw('ABS(rate_final - ?) < 0.0001', [1.872])
                ->exists();

            if ($rate_2_exists) {
                $this->info('El rate (coverage 2, rate_final 1.872) ya existe, no se vuelve a registrar.');
            } else {
                DB::table('ad_rates')->insert([
                    'id'                     => $next_rate_id_2,
                    'rate_company'           => 0.0,
                    'rate_accidental_death'  => 0.0,
                    'rate_preference'        => 0.0,
                    'rate_bank'              => 0.0,
                    'rate_damage_insurance'  => 0.0,
                    'rate_final'             => 1.04,
                    'year'                   => 0,
                    'ad_retailer_product_id' => 1474321374,
                    'ad_credit_product_id'   => null,
                    'ad_coverage_id'         => 2,
                    'active'                 => 1,
                    'created_at'             => Carbon::now(),
                    'updated_at'             => Carbon::now(),
                ]);
            }

            // Deshabilitar los rates anteriores
            DB::table('ad_rates')->where('id', 35)->update(['active' => 0]);
            DB::table('ad_rates')->where('id', 36)->update(['active' => 0]);
        });

        $this->info('Migración completada correctamente.');
    }
}
