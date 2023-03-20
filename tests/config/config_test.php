<?php

declare(strict_types = 1);

/*
 * This file is part of the RulesEngine library.
 * (c) Stello <dev@stello.eu>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('Easyblue\\RulesEngine\\Test\\Resources\\', '../Resources/*')
        ->exclude('../Resources/SportDto.php');

    // Needed to ignore container clean
    $services->alias('public.sport.engine', 'rules_engine.sport.engine')->public();

    $containerConfigurator->extension('rules_engine', [
        'engines' => [
            'sport' => [
                'chained' => true,
            ],
        ],
    ]);

    $containerConfigurator->extension('framework', [
        'http_method_override' => false,
        'test'                 => true,
    ]);
};
