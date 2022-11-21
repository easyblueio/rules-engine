<?php

declare(strict_types = 1);

/*
 * This file is part of the RulesEngine library.
 * (c) Stello <dev@stello.eu>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Easyblue\RulesEngine\Symfony\DependencyInjection\Configuration;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class RulesEngineConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('rules_engine');
        $root        = $treeBuilder->getRootNode();

        $root
            ->children()
                ->arrayNode('engines')
                    ->useAttributeAsKey('rules_engine_name')
                    ->arrayPrototype()
                        ->children()
                            ->booleanNode('chained')
                                ->defaultTrue()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
