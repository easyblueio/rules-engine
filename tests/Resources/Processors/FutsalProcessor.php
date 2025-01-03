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

#[AsProcessor('sport')]
final class FutsalProcessor implements ProcessorInterface
{
    /**
     * @param SportDto $subject
     */
    public function supports(object $subject, array $context): bool
    {
        return 'football' === ($subject->sport ?? null) && 'futsal' === ($subject->sportVariant ?? null);
    }

    /**
     * @param SportDto $subject
     */
    public function process(object $subject, array &$context): void
    {
        $subject->withLateralWalls = true;
        $subject->ballSize         = $context['ballSize'] ?? 5;

        // Do some logic, dispatch events ou messages...
    }
}
