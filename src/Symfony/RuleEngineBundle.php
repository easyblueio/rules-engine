<?php

declare(strict_types = 1);

/*
 * This file is part of the RulesEngine library.
 * (c) Stello <dev@stello.eu>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Easyblueio\RulesEngine\Symfony;

use Easyblueio\RulesEngine\Symfony\DependencyInjection\RulesEngineExtension;
use Easyblueio\RulesEngine\Symfony\DependencyInjection\RulesEnginePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class RuleEngineBundle extends AbstractBundle
{
    public function getPath(): string
    {
        return __DIR__;
    }

    public function getContainerExtension(): ExtensionInterface
    {
        return new RulesEngineExtension();
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RulesEnginePass());
    }
}
