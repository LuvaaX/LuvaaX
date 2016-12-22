<?php

namespace Luvaax\CoreBundle\Manager;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Luvaax\CoreBundle\Helper\CommandCreator;

/**
 *
 */
class CacheManager
{
    /**
     * @var AppKernel
     */
    private $kernel;

    /**
     * @var CommandCreator
     */
    private $commandCreator;

    /**
     * Constructor
     *
     * @param AppKernel      $kernel
     * @param CommandCreator $commandCreator
     */
    public function __construct($kernel, CommandCreator $commandCreator)
    {
        $this->kernel = $kernel;
        $this->commandCreator = $commandCreator;
    }

    /**
     * Clear the cache
     */
    public function clearCache()
    {
        $env = $this->kernel->getEnvironment();

        $options = ['--env' => $env];

        if ($env === 'prod') {
            $options[] = ['--no-debug' => true];
        }

        $this->commandCreator->execute('cache:clear', $options);

        if ($env === 'prod') {
            $this->commandCreator->execute('cache:warmup', $options);
        }
    }
}
