<?php 

namespace Multipedidos\BusinessLogic;

trait Find
{
    public function find($id)
    {
        return $this->getByID($id);
    }
}