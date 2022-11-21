<?php

declare(strict_types = 1);

/*
 * This file is part of the RulesEngine library.
 * (c) Stello <dev@stello.eu>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Easyblue\RulesEngine\Symfony\DependencyInjection;

use Easyblue\RulesEngine\Core\ProcessorInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\String\UnicodeString;

final class RulesEnginePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        foreach (array_keys($container->findTaggedServiceIds('rules_engine.engines')) as $ruleEngineId) {
            $definition = $container->getDefinition($ruleEngineId);

            /** @var string $name */
            $name = $definition->getArgument(0);

            $definition->addArgument($this->getProcessorsReferences($container, sprintf(
                'rules_engine.%s.processor',
                (new UnicodeString($name))->snake()->toString(),
            )));
        }
    }

    private function getProcessorsReferences(ContainerBuilder $container, string $tag): array
    {
        $processorsByPriority = [];
        foreach (array_keys($container->findTaggedServiceIds($tag)) as $processorId) {
            $definition                         = $container->getDefinition($processorId);
            /** @var class-string<ProcessorInterface> $processorClass */
            $processorClass                     = $definition->getClass();
            $processorsByPriority[$processorId] = $processorClass::getPriority();
        }

        array_multisort($processorsByPriority, SORT_DESC);

        return array_map(static fn (string $processorId) => new Reference($processorId), array_keys($processorsByPriority));
    }
}
