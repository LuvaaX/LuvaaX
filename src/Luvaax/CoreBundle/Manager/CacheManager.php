<?php

namespace Luvaax\CoreBundle\Manager;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

/**
 * @author Valentin Merlet (merlet.valentin@gmail.com)
 */
class CacheManager
{
    /**
     * @var AppKernel
     */
    private $kernel;

    /**
     * Constructor
     *
     * @param AppKernel $kernel
     */
    public function __construct($kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Clear the cache
     */
    public function clearCache()
    {
        // @todo Peekmo
    }
}
