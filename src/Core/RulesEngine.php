<?php

declare(strict_types = 1);

/*
 * This file is part of the RulesEngine library.
 * (c) Stello <dev@stello.eu>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Easyblue\RulesEngine\Core;

class RulesEngine
{
    public function __construct(
        public readonly string $name,
        public readonly array $options,
        public readonly array $processors,
    ) {
    }

    public function process(object $subject, ?array $context = []): void
    {
        foreach ($this->processors as $processor) {
            if (!$processor instanceof ProcessorInterface) {
                throw new \LogicException();
            }

            if ($processor->supports($subject, $context)) {
                $processor->process($subject, $context);

                if (!$this->isChained()) {
                    break;
                }
            }
        }
    }

    private function isChained(): bool
    {
        return $this->options['chained'] ?? true;
    }
}
