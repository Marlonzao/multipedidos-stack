<?php

namespace Multipedidos;

abstract class BusinessLogic
{
    protected $model, $repository;

    public function create(array $data): self
    {   
        $this->model = $this->repository->create($data);
        return $this;
    }

    public function update(array $data): self
    {
        if (key_exists('id', $data)) 
            unset($data['id']);

        $this->model = $this->repository->updateFromModel($this->model, $data);
        return $this;
    }

    public function delete(int $domainID): self
    {
        $this->repository->delete($domainID);
        return $this;
    }

    public function find(int $domainID): self
    {
        $this->model = $this->repository->getByID($domainID);
        return $this;
    }

    public function all(): self
    {
        $this->model = $this->repository->all();
        return $this;
    }

    public function model($model)
    {
        $this->model = $model;
        return $this;
    }

    public function toArray()
    {
        return $this->model->toArray;
    }
}
