<?php 

namespace Multipedidos;

trait Delete
{
    public function delete()
    {
        return $this->deleteModel();
    }
}