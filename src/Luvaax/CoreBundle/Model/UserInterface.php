<?php

namespace Luvaax\CoreBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface UserInterface extends BaseUserInterface
{
    /**
     * Returns the plain text password of the user (not registered in database)
     * @return string
     */
    public function getPlainPassword();
}
