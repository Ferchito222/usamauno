<?php

namespace Sibas\Repositories\Report;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PDO;
use Sibas\Entities\City;
use Sibas\Entities\De\CreditLine;
use Sibas\Entities\De\Header;
use Sibas\Entities\User;
use Sibas\Http\Controllers\ReportTrait;
use Sibas\Repositories\BaseRepository;

class ReportCollectionRepository extends BaseRepository
{

    protected $types = [
        'sale'         => [
            'title' => 'Ventas por Sucursal',
            'yAxis' => 'Seguros vendidos',
        ],
        'unemployment' => [
            'title' => 'Cesantía',
            'yAxis' => 'Seguros vendidos',
        ],
    ];


    use ReportTrait;


    /**
     * @param Request $request
     * @param string  $code
     * @param string  $type
     * @param string  $format
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function getDataReport(Request $request, $code, $type, $format)
    {
        $data = null;
        $this->setModelCollection($code);
        $this->getRelationsCollection($code);

        if ($this->model instanceof Model) {
            try {
                $data = $this->model->with($this->relations);

                switch ($type) {
                    case 'GEN':
                        //$data->where('type','I')->orderBy('issue_number', 'desc');
                        break;
                }

                $this->filtersByCollection($request, $data, $code, $type);
                
                if ($format === 'xls') {
                    $data = $data->get();
                } else {
                    $data = $data->paginate(50);
                }
            } catch (QueryException $e) {
                $this->errors = $e->getMessage();
            } catch (\BadMethodCallException $e) {
                $this->errors = $e->getMessage();
            }
        }

        return $data;
    }
}