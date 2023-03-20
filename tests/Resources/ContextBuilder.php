<?php

declare(strict_types = 1);

/*
 * This file is part of the RulesEngine library.
 * (c) Stello <dev@stello.eu>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Easyblue\RulesEngine\Test\Resources;

use Easyblue\RulesEngine\Core\ContextBuilderInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('rules_engine.sport.context_builder')]
final class ContextBuilder implements ContextBuilderInterface
{
    public function buildContext(object $subject, array $context): array
    {
        if (10 > $this->getUserAge($context)) {
            $context['ballSize'] = 3;
        }

        return $context;
    }

    private function getUserAge(array $context): ?int
    {
        // Could also get user from security and check age
        return $context['age'] ?? null;
    }
}
