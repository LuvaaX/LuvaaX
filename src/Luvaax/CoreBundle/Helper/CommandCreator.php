<?php

namespace Luvaax\CoreBundle\Helper;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Bundle\FrameworkBundle\Console\Application;

class CommandCreator
{
    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * @param Kernel $kernel
     */
    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Executes a command symfony and returns its result
     *
     * @param  string $command Command to execute
     * @param  array  $options Options to apply (key = option, value = option value)
     *
     * @return string
     */
    public function execute($command, array $options)
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => $command
        ] + $options);

        $output = new BufferedOutput();
        $application->run($input, $output);

        return $output->fetch();
    }
}
