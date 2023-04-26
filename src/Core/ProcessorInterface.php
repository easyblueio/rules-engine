<?php

declare(strict_types = 1);

/*
 * This file is part of the RulesEngine library.
 * (c) Stello <dev@stello.eu>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Easyblue\RulesEngine\Core;

interface ProcessorInterface
{
    /**
     * @deprecated Priority is now a tag attribute
     */
    public static function getPriority(): ?int;

    public function supports(object $subject, array $context): bool;

    public function process(object $subject, array &$context): void;
}
