<?php
namespace Multipedidos;

class RepositoryV2
{
    protected $modelQuery;

    public function __construct($modelQuery = null)
    {
        if(!is_null($modelQuery))
            $this->modelQuery = $modelQuery;

        if(is_null($this->modelQuery)) 
            throw new \MultipedidosException('No modelQuery provided', 500);
    }

    public function create(Array $record)
    {
        return $this->modelQuery::create($record);
    }

    public function updateFromModel($model, Array $dataToUpdate)
    {
        $model->fill($dataToUpdate)->save();
        return $model;
    }    

    public function getByRestaurantID($restaurantID)
    {
        return $this->modelQuery::where('restaurant_id', $restaurantID)->first();
    }

    public function getAllByRestaurantID($restaurantID)
    {
        return $this->modelQuery::where('restaurant_id', $restaurantID)->get();
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
    
    public function getBy($index, $value)
    {
        return $this->modelQuery::where($index, $value)->first();
    }
}
