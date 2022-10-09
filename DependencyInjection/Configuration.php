<?php

declare(strict_types=1);

namespace ElasticApmBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('elastic_apm');
        if (\method_exists(TreeBuilder::class, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            $rootNode = $treeBuilder->root('elastic_apm');
        }

        $rootNode
            ->children()
                ->booleanNode('enabled')->defaultTrue()->end()
                ->scalarNode('interactor')->end()
                ->booleanNode('logging')
                    ->info('Write logs to a PSR3 logger whenever we send data to Elastic APM.')
                    ->defaultFalse()
                ->end()
                ->booleanNode('track_memory_usage')
                    ->info('Should memory usage be tracked?')
                    ->defaultFalse()
                ->end()
                ->scalarNode('memory_usage_label')
                    ->info('The name of the label to write memory usage to.')
                    ->defaultValue('memory_usage')
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('exceptions')
                    ->canBeDisabled()
                    ->children()
                        ->arrayNode('ignored_exceptions')
                            ->scalarPrototype()->end()
                        ->end()
                        ->booleanNode('unwrap_exceptions')
                            ->defaultFalse()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('deprecations')
                    ->canBeDisabled()
                ->end()
                ->arrayNode('warnings')
                    ->canBeDisabled()
                ->end()
                ->arrayNode('custom_labels')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('custom_context')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('http')
                    ->canBeDisabled()
                    ->children()
                        ->scalarNode('transaction_naming')
                            ->defaultValue('route')
                            ->validate()
                                ->ifNotInArray(['uri', 'route', 'controller', 'service'])
                                ->thenInvalid('Invalid transaction naming scheme "%s", must be "uri", "route", "controller" or "service".')
                            ->end()
                        ->end()
                        ->scalarNode('transaction_naming_service')->defaultNull()->end()
                    ->end()
                ->end()
                ->arrayNode('commands')
                    ->canBeDisabled()
                    ->children()
                        ->booleanNode('explicitly_collect_exceptions')
                            ->info('Should exceptions be explicitly collected? This can conflict with the built-in collection in PHP APM')
                            ->defaultTrue()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
