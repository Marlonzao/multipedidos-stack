<?php 

namespace Multipedidos\BusinessLogic;

trait Delete
{
    public function delete($domainID)
    {
        return $this->deleteByID($domainID);
    }
}