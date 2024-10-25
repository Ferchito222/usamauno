<?php

namespace Sibas\Repositories\Adm;

use Sibas\Entities\De\Header;
use Sibas\Repositories\BaseRepository;

class AttachedCertificateRepository extends BaseRepository
{
    public function getApi()
    {
        $data = Header::with(['details.client', 'user'])
            ->where('issued', true)
            ->whereNotNull('file')
            ->orderBy('id', 'desc')
            ->get();

        $api = [
            'headers' => collect([]),
            'details' => collect([]),
        ];

        $data->each(function ($h) use ($api) {
            $api['headers']->push([
                'idx'          => $h->idc,
                'issueNumber'  => $h->issue_number,
                'prefix'       => $h->prefix,
                'details'      => $h->details->pluck('id'),
                'dateIssue'    => $h->date_issue->toDateTimeString(),
                'file'         => $h->file,
                'userName'     => $h->user->username,
                'userFullName' => $h->user->full_name,
            ]);

            $h->details->each(function ($d) use ($api) {
                $api['details'][$d->id] = [
                    'clientFullName' => $d->client->full_name,
                ];
            });
        });

        return $api;
    }


    public function getHeaderById($id)
    {
        $header = Header::where('id', $id)->first();

        if ($header instanceof Header) {
            $this->model = $header;

            return true;
        }

        return false;
    }


    public function deleteCertificate()
    {
        $this->model->file = null;
        $this->model->is_new = false;

        return $this->saveModel();
    }
}