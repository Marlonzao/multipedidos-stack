<?php

namespace Multipedidos;

abstract class BusinessLogic
{
    protected $model, $modelQuery, $repository, $input;

    public function __construct()
    {
        if(is_null($this->repository)){
            $this->initRepository();
            return;
        }

        $this->repository = new $this->repository($this->modelQuery);
    }

    private function initRepository()
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

    protected function insert($data): self
    {   
        $data = $this->constructorParser($data, 'create');
        $this->model = $this->repository->create($data);
        return $this;
    }

    protected function updateFromModel($data): self
    {
        if (key_exists('id', $data)) unset($data['id']);
        $data = $this->constructorParser($data, 'update');
        $this->model = $this->repository->updateFromModel($this->model, $data);
        return $this;
    }

    protected function deleteByID($domainID): self
    {
        $this->repository->delete($domainID);
        return $this;
    }

    protected function getByID($domainID): self
    {
        $this->model = $this->repository->getByID($domainID);
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
