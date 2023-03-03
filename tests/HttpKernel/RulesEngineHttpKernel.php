<?php

declare(strict_types = 1);

/*
 * This file is part of the RulesEngine library.
 * (c) Stello <dev@stello.eu>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Easyblue\RulesEngine\Test\HttpKernel;

use Easyblue\RulesEngine\Symfony\RulesEngineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;

final class RulesEngineHttpKernel extends Kernel
{
    /**
     * @return BundleInterface[]
     */
    public function registerBundles(): array
    {
        return [new RulesEngineBundle(), new FrameworkBundle()];
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir().'/rules_engine_test';
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir().'/rules_engine_test_log';
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__.'/../config/config_test.php');
    }
}
