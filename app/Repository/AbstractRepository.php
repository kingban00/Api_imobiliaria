<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
class AbstractRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectConditions($conditions){
        $conditions = explode(';', $conditions);

        foreach ($conditions as $expressions) {
            $expression = explode(':', $expressions);
            $this->model = $this->model->where($expression[0], $expression[1], $expression[2]);
        }
    }

    public function selectFilters($filters){
        $this->model = $this->model->selectRaw($filters);
    }

    public function getResults(){
        return $this->model;
    }
}
