<?php 

namespace Multipedidos;

trait Create
{
    public function create($data)
    {
        return $this->insert($data);
    }
}