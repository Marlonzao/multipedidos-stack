<?php 

namespace Multipedidos\BusinessLogic;

trait Create
{
    public function create($data)
    {
        return $this->insert($data);
    }
}