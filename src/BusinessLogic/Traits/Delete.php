<?php 

namespace Multipedidos\BusinessLogic;

trait Delete
{
    public function delete()
    {
        return $this->deleteModel();
    }
}