<?php
namespace Multipedidos;

class Repository
{
    protected $modelQuery;

    public function create(Array $record)
    {
        return $this->modelQuery::create($record);
    }

    public function updateFromModel($model, Array $dataToUpdate)
    {
        $model->fill($dataToUpdate)->save();
        return $model;
    }

    public function getByID($recordID)
    {
        return $this->modelQuery::find($recordID);
    }

    public function all()
    {
        return $this->modelQuery::all();
    }

    public function delete($recordID)
    {
        return $this->modelQuery::find($recordID)->delete();
    }

    public function updateOrCreate($query, Array $record)
    {
        return $this->modelQuery::updateOrCreate($query, $record);
    }
}
