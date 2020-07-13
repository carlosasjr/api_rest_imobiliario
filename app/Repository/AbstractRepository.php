<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * .class [ TIPO ]
 *
 * @copyright (c) 2018, Carlos Junior
 */
class AbstractRepository
{
    /**
     * @var Model
     */
    private $model;


    /**
     * ProductRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    /*     * ************************************************ */
    /*     * ************* METODOS PRIVADOS ***************** */
    /*     * ************************************************ */


    /*     * ************************************************ */
    /*     * ************* METODOS PUBLICOS ***************** */
    /*     * ************************************************ */

    /**
     * @param $conditions
     */
    public function selectConditions($conditions)
    {
        $expressions = explode(';', $conditions);
        foreach ($expressions as $e) {
            $exp = explode(':', $e);
            $this->model = $this->model->where($exp[0], $exp[1], $exp[2]);
        }
    }

    /**
     * @param $filters
     */
    public function selectFilter($filters)
    {
        $this->model = $this->model->selectRaw($filters);
    }

    public function getResult()
    {
        return $this->model;
    }
}
