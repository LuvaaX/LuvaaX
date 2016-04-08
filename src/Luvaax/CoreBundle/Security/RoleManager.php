<?php

namespace Luvaax\CoreBundle\Security;

use Luvaax\CoreBundle\Security\Model\RoleManagerInterface;

class RoleManager implements RoleManagerInterface
{
    /**
     * Roles in the security.yml
     * @var array
     */
    private $roles;

    /**
     * @param array $roles
     */
    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * Returns all roles available
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }
}
