<?php

declare(strict_types = 1);

/*
 * This file is part of the RulesEngine library.
 * (c) Stello <dev@stello.eu>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Easyblue\RulesEngine\Symfony\DependencyInjection;

use Symfony\Component\String\UnicodeString;

class NamingUtil
{
    public static function getProcessorTagName(string $rulesEngineName): string
    {
        return sprintf(
            'rules_engine.%s.processor',
            (new UnicodeString($rulesEngineName))->snake()->toString(),
        );
    }

    public static function getContextBuilderTagName(string $rulesEngineName): string
    {
        return sprintf(
            'rules_engine.%s.context_builder',
            (new UnicodeString($rulesEngineName))->snake()->toString(),
        );
    }

    public static function getRulesEngineAliasName(string $rulesEngineName): string
    {
        return sprintf(
            'rules_engine.%s.engine',
            (new UnicodeString($rulesEngineName))->snake()->toString(),
        );
    }

    public static function getRulesEngineArgumentName(string $rulesEngineName): string
    {
        return sprintf(
            '%sRulesEngine',
            (new UnicodeString($rulesEngineName))->camel()->toString()
        );
    }
}
