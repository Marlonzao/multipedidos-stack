<?php

namespace Multipedidos;

abstract class BusinessLogic
{
    protected $model, $repository, $collection;

    public function create(array $data): self
    {   
        $this->model = $this->repository->create($data);
        return $this;
    }

    public function update(array $data): self
    {
        if (key_exists('id', $data)) 
            unset($data['id']);

        $this->model = $this->repository->model($this->model)->update($data);
        return $this;
    }

    public function delete(int $domainID): self
    {
        $this->repository->delete($domainID);
        return $this;
    }

    public function find(int $domainID): self
    {
        $this->model = $this->repository->find($domainID);
        return $this;
    }

    public function all(): self
    {
        $this->model = $this->repository->all();
        return $this;
    }

    public function model($model): self
    {
        $this->model = $model;
        return $this;
    }

    public function toCollection()
    {
        if($this->model === null) return;

        if(is_null($this->collection)) return $this->model;

        if(str_contains(get_class($this->model), 'Model')) 
            return new $this->collection($this->model);
            
        if(str_contains(get_class($this->model), 'Collection')) 
            return $this->collection::collection($this->model);
    }
}
