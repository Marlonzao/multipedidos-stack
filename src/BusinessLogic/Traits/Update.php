<?php 

namespace Multipedidos\BusinessLogic;

trait Update
{
    public function update($data)
    {
        return $this->updateFromModel($data);
    }
}