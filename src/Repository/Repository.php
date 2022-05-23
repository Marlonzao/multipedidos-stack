<?php
namespace Multipedidos;

class Repository
{
    protected $modelQuery;

    public function create(array $record)
    {
        return $this->modelQuery::create($record);
    }

    public function model($model): self
    {
        $this->model = $model;
        return $this;
    }

    public function update(Array $dataToUpdate)
    {
        $this->model->fill($dataToUpdate)->save();
        return $this->model;
    }

    public function find(int $recordID)
    {
        return $this->modelQuery::find($recordID);
    }

    public function all()
    {
        return $this->modelQuery::all();
    }

    public function delete(int $recordID)
    {
        return $this->modelQuery::find($recordID)->delete();
    }

    public function updateOrCreate(array $query, array $record)
    {
        return $this->modelQuery::updateOrCreate($query, $record);
    }
}
