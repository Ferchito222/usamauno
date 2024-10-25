<?php

namespace Sibas\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Sibas\Entities\De\CreditLine;
use Sibas\Entities\De\Header;

class ReportCoDebtor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:co-debtor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Report co debtor';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('memory_limit', -1);
        set_time_limit(0);

        $date = Carbon::now();

        $headers = Header::with([
            'creditProduct',
            'details.client',
            'details.facultative',
        ])
            ->whereNotNull('file')
            ->where('issued', true)
            ->whereDate('file_date', '=', $date->copy()->startOfDay()->subDay()->toDateString())
            ->orderBy('issue_number', 'asc')
            ->get();

        $content = '';

        foreach ($headers as $header) {
            foreach ($header->details as $detail) {
                $rate       = $detail->rate;
                $rate_total = (! is_null($detail->facultative) ? $detail->facultative->final_rate : $detail->rate);

                if ($header->creditProduct->slug !== 'PCA') {
                    $rate       = $rate * 12;
                    $rate_total = $rate_total * 12;
                }

                $rate       = number_format($rate, 3);
                $rate_total = number_format($rate_total, 3);

                $content .= ($detail->headline === 'D' ? 1 : 0)
                            . '|'
                            . (in_array($header->creditProduct->slug, ['PMO', 'PLT']) ? 1 : 0)
                            . '|'
                            . $header->operation_number
                            . '|'
                            . $detail->client->dni
                            . '|'
                            . $detail->percentage_credit
                            . '|'
                            . $rate_total
                            . '|'
                            . (! is_null($detail->facultative) ? $detail->facultative->percentage : 0)
                            . '|'
                            . $rate
                            . chr(13)
                            . PHP_EOL;
            }
        }

        $credit_lines = CreditLine::with([
            'clients',
            'header'
        ])
            ->whereDate('created_at', '=', $date->copy()->startOfDay()->subDay()->toDateString())
            ->orderBy('number_line', 'asc')
            ->get();

        foreach ($credit_lines as $credit_line) {
            $data    = $credit_line->data_records;
            $clients = $credit_line->clients->pluck('percentage_credit', 'dni')->toArray();

            if (count($data) > 0) {
                foreach ($data as $key => $d) {
                    if (in_array($d['ci'], array_keys($clients))) {
                        $rate_total = ($credit_line->policy_type != 'CA' ? ($d['tasaFinal'] / 12) : $d['tasaFinal']);
                        $rate       = (($rate_total * 100) / (100 + $d['tasaExtraPrima']));

                        if ($credit_line->policy_type !== 'CA') {
                            $rate       = $rate * 12;
                            $rate_total = $rate_total * 12;
                        }

                        $rate       = number_format($rate, 3);
                        $rate_total = number_format($rate_total, 3);

                        $content .= ($key === 0 ? 1 : 0)
                                    . '|'
                                    . ($credit_line->policy_type === 'TE' ? 1 : 0)
                                    . '|'
                                    . $d['nroOperacion']
                                    . '|'
                                    . $d['ci']
                                    . '|'
                                    . $clients[$d['ci']]
                                    . '|'
                                    . $rate_total
                                    . '|'
                                    . $d['tasaExtraPrima']
                                    . '|'
                                    . $rate
                                    . chr(13)
                                    . PHP_EOL;
                    }
                }
            }
        }

        $file    = storage_path('exports/Codeudores.txt');
        $archivo = \File::put($file, $content);

        if ($archivo === false) {
            die("Error writing to file");
        }

        $subject = 'Reporte Codeudores ' . date('Ymd');
        $title   = str_replace(' ', '-', $subject);
        $emails  = '';


        Mail::raw($title, function ($message) use ($file, $subject, $emails) {
            $message->from(env('MAIL_USERNAME'), env('MAIL_NAME'));
            $message->subject($subject);

            $message->to('sezurita@bnb.com.bo', 'sezurita@bnb.com.bo');
            $message->cc('Sistemas-lpz@bnb.com.bo', 'Sistemas-lpz@bnb.com.bo');
            $message->cc('emontano@sudseguros.com', 'Ernesto Montaño');
            $message->cc('mmamani@coboser.com', 'Miguel Mamani');

            $message->attach($file);
        });
    }
}
