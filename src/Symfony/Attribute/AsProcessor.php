<?php

declare(strict_types = 1);

/*
 * This file is part of the RulesEngine library.
 * (c) Stello <dev@stello.eu>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Easyblue\RulesEngine\Symfony\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
class AsProcessor
{
    public function __construct(
        public readonly string $rulesEngineName,
        public readonly ?int $priority = 0,
    ) {
    }
}
