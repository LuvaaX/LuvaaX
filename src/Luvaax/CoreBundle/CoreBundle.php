<?php

namespace Luvaax\CoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Luvaax\CoreBundle\DependencyInjection\LuvaaxCoreExtension;

class CoreBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new LuvaaxCoreExtension();
    }
}
