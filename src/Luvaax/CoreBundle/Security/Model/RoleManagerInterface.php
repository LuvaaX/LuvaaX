<?php

namespace Luvaax\CoreBundle\Security\Model;

interface RoleManagerInterface
{
    /**
     * Returns all roles available in the application
     * 
     * @return array
     */
    public function getRoles();
}
