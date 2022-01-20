<?php 

namespace Multipedidos;

trait Update
{
    public function update($data)
    {
        return $this->updateFromModel($data);
    }
}