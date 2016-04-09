<?php

namespace Luvaax\CoreBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class LuvaaxCoreExtension extends ConfigurableExtension
{
    const PREFIX = 'luvaax_core';

    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config/services')
        );

        $loader->load('services.yml');
        $loader->load('form_types.yml');
        $loader->load('subscribers.yml');

        $this->processSecurity($mergedConfig, $container);
    }

    /**
     * Process security options from luvaax_core configuration
     *
     * @param  array            $config    Config options
     * @param  ContainerBuilder $container Symfony's container
     */
    private function processSecurity(array $config, ContainerBuilder $container)
    {
        $container->setParameter(self::PREFIX . '.role_manager', $config['security']['role_manager']);
    }
}
