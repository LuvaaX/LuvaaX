<?php

namespace Luvaax\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('luvaax_core');

        $rootNode
            ->children()
                ->arrayNode('security')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('role_manager')
                            ->info('Class that implements Luvaax\CoreBundle\Security\Model\RoleManagerInterface')
                            ->isRequired()
                            ->defaultValue('Luvaax\CoreBundle\Security\RoleManager')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
