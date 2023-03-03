<?php

declare(strict_types = 1);

/*
 * This file is part of the RulesEngine library.
 * (c) Stello <dev@stello.eu>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Easyblue\RulesEngine\Test\Processors;

use Easyblue\RulesEngine\Core\ProcessorInterface;
use Easyblue\RulesEngine\Test\SportDto;

final class FootballProcessor implements ProcessorInterface
{
    public static function getPriority(): int
    {
        return 10;
    }

    /**
     * @param SportDto $subject
     */
    public function supports(object $subject, array $context): bool
    {
        return 'football' === ($subject->sport ?? null);
    }

    /**
     * @param SportDto $subject
     */
    public function process(object $subject, array &$context): void
    {
        $subject->needsBall   = true;
        $subject->needsRacket = false;

        // Do some logic, dispatch events ou messages...
    }
}
