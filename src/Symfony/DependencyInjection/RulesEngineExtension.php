<?php

declare(strict_types = 1);

/*
 * This file is part of the RulesEngine library.
 * (c) Stello <dev@stello.eu>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Easyblueio\RulesEngine\Symfony\DependencyInjection;

use Easyblueio\RulesEngine\Core\RulesEngine;
use Easyblueio\RulesEngine\Symfony\DependencyInjection\Configuration\RulesEngineConfiguration;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\String\UnicodeString;

final class RulesEngineExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(
            new RulesEngineConfiguration(),
            $configs
        );

        foreach ($config['engines'] ?? [] as $engineName => $options) {
            $definition = new Definition(RulesEngine::class, [
                $engineName,
                $options,
            ]);
            $definition->addTag('rules_engine.engines');

            $ruleEngineId = sprintf('rules_engine.%s.engine', (new UnicodeString($engineName))->snake()->toString());

            $container->setDefinition($ruleEngineId, $definition);
            $container->registerAliasForArgument($ruleEngineId, RulesEngine::class, sprintf(
                '%sRulesEngine',
                (new UnicodeString($engineName))->camel()->toString()
            ));
        }
    }
}
