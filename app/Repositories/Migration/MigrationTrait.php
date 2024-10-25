<?php

namespace Sibas\Repositories\Migration;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PDO;
use Sibas\Entities\Retailer;

trait MigrationTrait
{
    /**
     * @var string
     */
    protected $conn_old = 'mysql_old';
    /**
     * @var Collection
     */
    protected $data;
    /**
     * @var Carbon
     */
    protected $date;

    /**
     * @return Collection
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return Model|Retailer|mixed
     */
    protected function getRetailer()
    {
        return Retailer::with(['retailerProducts' => function($q) {
            $q->with([ 'companyProduct.product', ])
                ->where('type', 'MP');
        }])->first();
    }

    /**
     * @param Builder $query
     */
    protected function executeQuery($query)
    {
        DB::connection($this->conn_old)->setFetchMode(PDO::FETCH_ASSOC);

        $query      = $query->get();
        $this->data = Collection::make($query);

        DB::connection($this->conn_old)->setFetchMode(config('database.fetch'));
    }
}