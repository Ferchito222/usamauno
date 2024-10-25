<?php

namespace Sibas\Repositories\API;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;
use Sibas\Entities\Elp\Collection;
use Sibas\Entities\Elp\CollectionRecord;
use Sibas\Entities\Elp\Header;
use Sibas\Entities\Retailer\Holiday;
use Sibas\Repositories\BaseRepository;

class ElpCollectionRepository extends BaseRepository
{
    protected $subject;

    protected $message;

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Collections export
     *
     * @return bool
     */
    public function collectionExport()
    {
        // $current_date = $this->carbon->create(2016, 12, 21, 0, 0, 0);
        $current_date = $this->carbon;
        $day_of_week  = $current_date->dayOfWeek;
        $sub_day      = 0;

        /*
         * Verificar si es Lunes
         */
        if ($day_of_week === 1) {
            $sub_day = 3;
        } else {
            $sub_day = 1;
        }

        $date = $current_date->copy()->subDays($sub_day);

        /*
         * Verificar si no es Sabado, Domingo o dia feriado
         */
        $holiday = Holiday::where('holiday_date', $current_date->copy()->toDateString())->first();

        if ($day_of_week !== 6 && $day_of_week !== 7 && is_null($holiday)) {
            $cr = CollectionRecord::orderBy('id', 'desc')->first();

            if ($cr instanceof CollectionRecord) {
                if ($cr->confirmation) {
                    /*
                     * Registrar la exportación
                     */
                    $load_number = CollectionRecord::insertGetId([
                        'date'          => $current_date->copy()->toDateTimeString(),
                        'total_record'  => 0,
                        'total_amount'  => 0,
                        'date_shipping' => 0,
                        'confirmation'  => 0,
                    ]);

                    /*
                     * Obtener las cobranzas no cobradas
                     */
                    $headers = Header::with([
                        'detail.client',
                        'detail.client.accounts' => function ($q) {
                            $q->where('main_account', true);
                        },
                        'collections'            => function ($q) use ($current_date) {
                            $q->where('charged', false)
                                ->whereDate('fee_date', '<=', $current_date->copy()->addDay()->toDateString());
                        },
                    ])->whereHas('detail', function ($q) {
                        $q->where('client_eco', true);
                    })->where([
                        'issued'   => true,
                        'canceled' => false,
                    ])->orderBy('id', 'asc')->get();

                    // $last_day = $current_date->copy()->endOfMonth();

                    $order = 1;
                    $data  = [];

                    foreach ($headers as $header) {
                        $number = $header->collections->count();

                        if ($number >= 3) {
                            /*
                             * Si esta en mora se anula
                             */
                            $header->update([
                                'canceled'    => true,
                                'ad_state_id' => 10,
                            ]);

                            $header->cancellation()->create([
                                'id'         => date('U'),
                                'ad_user_id' => null,
                                'reason'     => 'Sobrepasa los 60 días de pago, por lo tanto esta Póliza no se encuentra vigente',
                            ]);
                        } else {
                            /*
                             * Generación del registro de cobranza
                             */
                            array_push($data, [
                                $load_number,
                                $header->detail->client->code,
                                $header->detail->client->accounts->first()->number,
                                $header->collections->sum('fee_amount'),
                                1,
                                10,
                                $header->id,
                                $order,
                                '',
                                $current_date->format('Ymd'),
                            ]);

                            $order += 1;
                        }
                    }

                    /*
                     * Exportacion
                     */
                    $path = '/home/ecofuturoftp/ecoseguro/IN/';

                    $files = File::allFiles($path);

                    foreach ($files as $file) {
                        $file = (string)$file;

                        if (File::isFile($file)) {
                            File::delete($file);
                        }
                    }

                    $file = $path . $load_number . 'D' . $current_date->format('Ymd') . '101.csv';
                    $fp   = fopen($file, 'w');

                    if ($fp !== false) {
                        foreach ($data as $d) {
                            fputcsv($fp, $d, '|');
                        }

                        fclose($fp);

                        if (File::exists($file)) {
                            $this->subject = 'Ecoproteccion: Exportacion y Generacion de archivo exitosa Ecofuturo';
                            $this->message =
                                'Se genero correctamente el archivo '
                                . $date->format('Y-m-d')
                                . ' y se copio correctamente al FTP.';

                            return true;
                        } else {
                            $this->subject = 'Ecoproteccion: Error en la Generacion del Archivo Ecofuturo';
                            $this->message = 'No se genero correctamente el archivo ' . $file;

                            return true;
                        }
                    }
                } else {
                    $this->subject = 'Ecoproteccion: Error en Confirmacion Ecofuturo';
                    $this->message =
                        'Confirmacion de que no se importo el archivo de fecha '
                        . $date->format('Y-m-d')
                        . ' por lo que no se exporto el nuevo archivo.';

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Collection import confirm
     *
     * @return bool
     */
    public function collectionImportConfirm()
    {
        $current_date = $this->carbon;
        $day_of_week  = $current_date->dayOfWeek;

        /*
         * Verificar si no es Sabado, Domingo o dia feriado
         */
        $holiday = Holiday::where('holiday_date', $current_date->copy()->toDateString())->first();

        if ($day_of_week !== 6 && $day_of_week !== 7 && is_null($holiday)) {
            $path = '/home/ecofuturoftp/ecoseguro/OUT/';

            $cr = CollectionRecord::orderBy('id', 'desc')->first();

            if ($cr instanceof CollectionRecord) {
                $date = $this->carbon->createFromTimestamp(strtotime($cr->date));
                $file = $path . $cr->id . 'C' . $date->copy()->format('Ymd') . '101.csv';

                /*
                 * Verificar si el archivo de confirmacion existe
                 */
                if (File::exists($file)) {
                    $error = false;
                    $fp    = fopen($file, 'r');

                    while (( $row = fgetcsv($fp, 10000, '|') ) != false) {
                        try {
                            $date_shipping = $this->carbon->createFromFormat('Ymd', $row[ 3 ]);

                            $cr->update([
                                'total_record'  => $row[ 1 ],
                                'total_amount'  => $row[ 2 ],
                                'date_shipping' => $date_shipping->toDateTimeString(),
                                'confirmation'  => true,
                            ]);
                        } catch (QueryException $e) {
                            $error = true;
                        }
                    }

                    fclose($fp);

                    if (! $error) {
                        $this->subject = 'Ecoproteccion: Importacion y Actualizacion de Confirmacion exitosa';
                        $this->message =
                            'Se ha importado y escrito el archivo <span style="font-weight: bold;">'
                            . $file
                            . '</span>, tambien se actualizaron los datos correspondientes.';

                        return true;
                    } else {
                        $this->subject = 'Ecoproteccion: Actualizacion de Confirmacion Erronea';
                        $this->message = 'No se pudo actualizar los datos de confirmacion en nuestra base de datos.';

                        return true;
                    }
                } else {
                    $this->subject = 'Ecoproteccion: Importacion de Confirmacion Erronea';
                    $this->message =
                        'No existe el archivo de confirmacion <span style="font-weight: bold;">' . $file . '</span>';

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Collection import production
     *
     * @return bool
     */
    public function collectionImportProduction()
    {
        $current_date = $this->carbon;
        $day_of_week  = $current_date->dayOfWeek;

        /*
         * Verificar si no es Sabado, Domingo o dia feriado
         */
        $holiday = Holiday::where('holiday_date', $current_date->copy()->toDateString())->first();

        if ($day_of_week !== 6 && $day_of_week !== 7 && is_null($holiday)) {
            $path = '/home/ecofuturoftp/ecoseguro/OUT/';

            $cr = CollectionRecord::orderBy('id', 'desc')->first();

            if ($cr instanceof CollectionRecord) {
                $file = $path . $cr->id . 'P' . $current_date->copy()->format('Ymd') . '101.csv';

                /*
                 * Verificar si el archivo de produccion existe
                 */
                if (File::exists($file)) {
                    $error = false;
                    $fp    = fopen($file, 'r');

                    while (( $row = fgetcsv($fp, 10000, '|') ) != false) {
                        $header = Header::with([ 'collections' ])->where('id', $row[ 6 ])->first();

                        if ($header instanceof Header) {
                            $charged            = false;
                            $transaction_date   = null;
                            $transaction_number = 0;
                            $reason             = empty($row[ 9 ]) ? 0 : $row[ 9 ];

                            if ($row[ 8 ] === 'P') {
                                $charged            = true;
                                $transaction_date   = $current_date->copy();
                                $transaction_number = $row[ 7 ];
                            }

                            try {
                                $header->collections()
                                    ->where('charged', false)
                                    ->whereDate('fee_date', '<=', $current_date->copy()->toDateString())->update([
                                        'charged'            => $charged,
                                        'transaction_number' => $transaction_number,
                                        'transaction_date'   => $transaction_date->toDateTimeString(),
                                        'transaction_amount' => 'fee_amount',
                                        'reason'             => $reason,
                                        'balance'            => $row[ 10 ],
                                    ]);
                            } catch (QueryException $e) {
                                $error = true;
                            }
                        }
                    }

                    if (! $error) {
                        $this->subject = 'Ecoproteccion: Importacion y Actualizacion de Produccion exitosa';
                        $this->message =
                            'Se ha importado y escrito el archivo <span style="font-weight: bold;">'
                            . $file
                            . '</span>, tambien se actualizó los datos correspondientes';

                        return true;
                    } else {
                        $this->subject = 'Ecoproteccion: Actualizacion de Produccion Erronea';
                        $this->message = 'No se pudo actualizar los datos de produccion en nuestra base de datos';

                        return true;
                    }
                } else {
                    $this->subject = 'Ecoproteccion: Importacion de Produccion Erronea';
                    $this->message = 'No existe el archivo de produccion ' . $file;

                    return true;
                }
            }
        }

        return false;
    }

}