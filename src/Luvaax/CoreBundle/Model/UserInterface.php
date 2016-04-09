<?php

namespace Luvaax\CoreBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

interface UserInterface extends UserInterface
{
    /**
     * Returns the plain text password of the user (not registered in database)
     * @return string
     */
    public function getPlainPassword();
}
