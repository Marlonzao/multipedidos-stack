<?php

namespace Multipedidos;

abstract class BusinessLogic
{
    protected $model, $modelQuery, $repository, $input;

    public function __construct()
    {
        if(isset($this->repository)){
            $this->repository = new $this->repository($this->modelQuery);
        }else{
            $this->initRepository();
        }
    }

    protected function initRepository()
    {
        $this->repository = new RepositoryV2($this->modelQuery);
    }

    private function constructorParser($data, $operation = 'always')
    {
        if($this->input)
            return (new $this->input($data))
                             ->parse($operation)
                             ->toArray();

        if(is_array($data))
            return $data;

        if(property_exists($data, 'model') && !is_null($data->model))
            return $data->model->toArray();

        if(method_exists($data, 'toArray'))
            return $data->toArray();
        
        return (array) $data;
    }

    protected function insert($data)
    {   
        $data = $this->constructorParser($data, 'create');
        $this->model = $this->repository->create($data);
        return $this;
    }

    protected function updateFromModel($data)
    {
        if (key_exists('id', $data)) unset($data['id']);
        $data = $this->constructorParser($data, 'update');
        $this->model = $this->repository->updateFromModel($this->model, $data);
        return $this;
    }

    protected function deleteModel()
    {
        $this->repository->delete($this->model->id);
        return $this;
    }

    protected function getByID($domainID)
    {
        $this->model = $this->repository->getByID($domainID);
        return $this;
    }

    public function restaurantID($restaurantID)
    {
        $this->restaurantID = $restaurantID;
        return $this;
    }

    public function getByRestaurantID($restaurantID)
    {
        $this->restaurantID($restaurantID);

        $this->model = $this->repository->getByRestaurantID($this->restaurantID);
        return $this;
    }

    public function getAllByRestaurantID($restaurantID)
    {
        $this->restaurantID($restaurantID);

        $this->model = $this->repository->getAllByRestaurantID($this->restaurantID);
        return $this;
    }

    public function cashierID($cashierID)
    {
        $this->cashierID = $cashierID;
        return $this;
    }

    public function getByCashierID($cashierID)
    {
        $this->cashierID($cashierID);

        $this->model = $this->repository->getByCashierID($this->cashierID);
        return $this;
    }

    public function model($model)
    {
        $this->model = $model;
        return $this;
    }

    public function getModel()
    {
        return $this->model;
    }
}
