<?php

declare(strict_types = 1);

/*
 * This file is part of the RulesEngine library.
 * (c) Stello <dev@stello.eu>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Easyblue\RulesEngine\Test\Resources;

final class SportDto
{
    public ?string $sport            = null;
    public ?string $sportVariant     = null;
    public ?bool $needsBall          = null;
    public ?bool $needsRacket        = null;
    public ?bool $withLateralWalls   = null;
    public ?int $ballSize            = null;
}
