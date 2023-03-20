<?php

declare(strict_types = 1);

/*
 * This file is part of the RulesEngine library.
 * (c) Stello <dev@stello.eu>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Easyblue\RulesEngine\Test;

use Easyblue\RulesEngine\Core\RulesEngine;
use Easyblue\RulesEngine\Test\Resources\Processors\FootballProcessor;
use Easyblue\RulesEngine\Test\Resources\Processors\FutsalProcessor;
use Easyblue\RulesEngine\Test\Resources\SportDto;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SymfonyAppRulesEngineTest extends KernelTestCase
{
    public function providesContextBuilder(): \Generator
    {
        yield 'age > 10' => ['ballSize' => 5, 'context' => ['age' => 11]];
        yield 'age < 10' => ['ballSize' => 3, 'context' => ['age' => 8]];
    }

    /**
     * @dataProvider providesContextBuilder
     */
    public function testChainedCaseFutsal(int $ballSize, array $context): void
    {
        $rulesEngine = static::getContainer()->get('rules_engine.sport.engine');
        self::assertInstanceOf(RulesEngine::class, $rulesEngine);

        $subject               = new SportDto();
        $subject->sport        = 'football';
        $subject->sportVariant = 'futsal';

        $rulesEngine->process($subject, $context);

        self::assertTrue($subject->needsBall ?? null, sprintf('"needsBall" is wrong, see "%s"', FootballProcessor::class));
        self::assertFalse($subject->needsRacket ?? null, sprintf('"needsRacket" is wrong, see "%s"', FootballProcessor::class));
        self::assertTrue($subject->withLateralWalls ?? null, sprintf('"withLateralWalls" is wrong, see "%s"', FutsalProcessor::class));
        self::assertSame($ballSize, $subject->ballSize ?? null, sprintf('"ballSize" is wrong, see "%s"', FutsalProcessor::class));
    }

    public function testChainedCaseSquash(): void
    {
        $rulesEngine = static::getContainer()->get('rules_engine.sport.engine');
        self::assertInstanceOf(RulesEngine::class, $rulesEngine);

        $subject        = new SportDto();
        $subject->sport = 'squash';

        $rulesEngine->process($subject);

        self::assertTrue($subject->needsBall ?? null, sprintf('"needsBall" is wrong, see "%s"', FootballProcessor::class));
        self::assertTrue($subject->needsRacket ?? null, sprintf('"needsRacket" is wrong, see "%s"', FootballProcessor::class));
        self::assertNull($subject->withLateralWalls ?? null, sprintf('"withLateralWalls" is wrong, expected to not support "%s"', FutsalProcessor::class));
        self::assertNull($subject->ballSize ?? null, sprintf('"ballSize" is wrong, expected to not support "%s"', FutsalProcessor::class));
    }
}
