<?php

declare(strict_types = 1);

/*
 * This file is part of the RulesEngine library.
 * (c) Stello <dev@stello.eu>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Easyblue\RulesEngine\Test\Resources\Processors;

use Easyblue\RulesEngine\Core\ProcessorInterface;
use Easyblue\RulesEngine\Symfony\Attribute\AsProcessor;
use Easyblue\RulesEngine\Test\Resources\SportDto;

#[AsProcessor('sport', 10)]
final class SquashProcessor implements ProcessorInterface
{
    /**
     * @param SportDto $subject
     */
    public function supports(object $subject, array $context): bool
    {
        return 'squash' === ($subject->sport ?? null);
    }

    /**
     * @param SportDto $subject
     */
    public function process(object $subject, array &$context): void
    {
        $subject->needsBall   = true;
        $subject->needsRacket = true;

        // Do some logic, dispatch events ou messages...
    }
}
