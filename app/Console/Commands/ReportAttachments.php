<?php

namespace Sibas\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Sibas\Entities\De\Header;
use Sibas\Repositories\Report\ReportGeneralRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;
use Sibas\Repositories\UserRepository;

class ReportAttachments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:attachments';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Report attachments';
    /**
     * @var ReportGeneralRepository
     */
    protected $reportGeneralRepository;
    /**
     * @var UserRepository
     */
    protected $userRepository;
    /**
     * @var RetailerProductRepository
     */
    protected $retailerProductRepository;

    /**
     * Create a new command instance.
     *
     * @param ReportGeneralRepository   $reportGeneralRepository
     * @param UserRepository            $userRepository
     * @param RetailerProductRepository $retailerProductRepository
     */
    public function __construct(
        ReportGeneralRepository $reportGeneralRepository,
        UserRepository $userRepository,
        RetailerProductRepository $retailerProductRepository
    )
    {
        $this->reportGeneralRepository   = $reportGeneralRepository;
        $this->userRepository            = $userRepository;
        $this->retailerProductRepository = $retailerProductRepository;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $relations = $this->reportGeneralRepository->getRelations('de', true);
        $headers   = Header::with($relations);
        $this->reportGeneralRepository->dataReportAttachments($headers);
        $headers   = $headers->get();
        $view      = 'report.rows.rows-de';
        $subject   = 'Reporte Adjuntos ' . date('Ymd');
        $title     = str_replace(' ' , '-', $subject);
        $users     = $this->userRepository->getUsersByProfile([ 'COP' ]);
        $users     = $users->pluck('full_name', 'email')->all();
        $emails    = [];

        if ($this->retailerProductRepository->getRetailerProductByCode('de')) {
            $rp     = $this->retailerProductRepository->getModel();
            $emails = $rp->emails()->where('active', true)->get()->pluck('name', 'email')->all();
        }

        $file_xls = Excel::create($title, function($excel) use ($headers, $view) {
            $excel->sheet('Reporte', function($sheet) use ($headers, $view) {
                $sheet->loadView($view, [
                    'headers' => $headers,
                    'format'  => 'xls',
                    'type'    => 'BW',
                ]);
            });
        })->store('xls', storage_path('exports/attachments'), true);

        Mail::raw($title, function ($message) use ($file_xls, $subject, $users, $emails) {
            $message->from(env('MAIL_USERNAME'), env('MAIL_NAME'));
            $message->subject($subject);

            foreach ($users as $email => $full_name) {
                $message->to($email, $full_name);
            }

            foreach ($emails as $email => $name) {
                $message->cc($email, $name);
            }

            $message->attach($file_xls['full']);
        });

        return $file_xls;
    }
}
