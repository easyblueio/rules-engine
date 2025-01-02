<?php

declare(strict_types = 1);

/*
 * This file is part of the RulesEngine library.
 * (c) Stello <dev@stello.eu>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Easyblue\RulesEngine\Symfony\DependencyInjection;

use Easyblue\RulesEngine\Core\RulesEngine;
use Easyblue\RulesEngine\Symfony\Attribute\AsProcessor;
use Easyblue\RulesEngine\Symfony\DependencyInjection\Configuration\RulesEngineConfiguration;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;

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

            $ruleEngineId = NamingUtil::getRulesEngineAliasName($engineName);

            $container->setDefinition($ruleEngineId, $definition);
            $container->registerAliasForArgument($ruleEngineId, RulesEngine::class, NamingUtil::getRulesEngineArgumentName($engineName));
        }

        $container->registerAttributeForAutoconfiguration(AsProcessor::class, static function (ChildDefinition $definition, AsProcessor $attribute) {
            $definition->addTag(NamingUtil::getProcessorTagName($attribute->rulesEngineName), ['priority' => $attribute->priority]);
        });
    }
}
