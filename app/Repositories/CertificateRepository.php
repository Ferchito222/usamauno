<?php

namespace Sibas\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Sibas\Entities\Certificate;

class CertificateRepository extends BaseRepository
{

    /**
     * @param string $code
     * @param int    $id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getDataCertificate($code, $id)
    {
        $header = null;
        $this->setModel($code);
        $this->getRelations($code);

        if ($this->model instanceof Model) {
            try {
                $this->model = $this->model->with($this->relations)
                    ->where([
                        'id' => $id,
                    ])->first();
            } catch (QueryException $e) {
                $this->errors = $e->getMessage();
            } catch (\BadMethodCallException $e) {
                $this->errors = $e->getMessage();
            }
        }

        return $this->model;
    }


    /**
     * @param Model $header
     *
     * @return string
     */
    public function getTemplateCertificate($header)
    {
        return ( $header->certificate instanceof Certificate ) ? $header->certificate->template : '';
    }


    /**
     * @return Model
     */
    public function setCopyNumber()
    {
        if ($this->model->issued) {
            $copies = $this->model->copy === 0 ? 2 : 3;

            $this->model->update([
                'copy' => ( $this->model->copy + $copies ),
                'read' => true,
            ]);
        }

        return $this->model->fresh($this->relations);
    }
}