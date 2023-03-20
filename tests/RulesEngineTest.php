<?php

declare(strict_types = 1);

/*
 * This file is part of the RulesEngine library.
 * (c) Stello <dev@stello.eu>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Easyblue\RulesEngine\Test;

use Easyblue\RulesEngine\Core\ContextBuilderInterface;
use Easyblue\RulesEngine\Core\RulesEngine;
use Easyblue\RulesEngine\Test\Resources\ContextBuilder;
use Easyblue\RulesEngine\Test\Resources\Processors\FootballProcessor;
use Easyblue\RulesEngine\Test\Resources\Processors\FutsalProcessor;
use Easyblue\RulesEngine\Test\Resources\Processors\SquashProcessor;
use Easyblue\RulesEngine\Test\Resources\SportDto;
use PHPUnit\Framework\TestCase;

class RulesEngineTest extends TestCase
{
    // <editor-fold desc="Chained Rules Engine">
    public function providesContextBuilder(): \Generator
    {
        yield 'no context builder' => ['ballSize' => 5, 'contextBuilder' => null, 'context' => []];
        yield '   context builder age > 10' => ['ballSize' => 5, 'contextBuilder' => new ContextBuilder(), 'context' => ['age' => 11]];
        yield '   context builder age < 10' => ['ballSize' => 3, 'contextBuilder' => new ContextBuilder(), 'context' => ['age' => 8]];
    }

    /**
     * @dataProvider providesContextBuilder
     */
    public function testChainedCaseFutsal(int $ballSize, ?ContextBuilderInterface $contextBuilder, array $context): void
    {
        $rulesEngine = new RulesEngine('chained-case', ['chained' => true], $this->getProcessors(), $contextBuilder);

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
        $rulesEngine = new RulesEngine('chained-case', ['chained' => true], $this->getProcessors());

        $subject        = new SportDto();
        $subject->sport = 'squash';

        $rulesEngine->process($subject);

        self::assertTrue($subject->needsBall ?? null, sprintf('"needsBall" is wrong, see "%s"', FootballProcessor::class));
        self::assertTrue($subject->needsRacket ?? null, sprintf('"needsRacket" is wrong, see "%s"', FootballProcessor::class));
        self::assertNull($subject->withLateralWalls ?? null, sprintf('"withLateralWalls" is wrong, expected to not support "%s"', FutsalProcessor::class));
        self::assertNull($subject->ballSize ?? null, sprintf('"ballSize" is wrong, expected to not support "%s"', FutsalProcessor::class));
    }
    // </editor-fold>

    // <editor-fold desc="Not Chained Rules Engine">
    public function testNotChainedCaseFutsal(): void
    {
        $rulesEngine = new RulesEngine('not-chained-case', ['chained' => false], $this->getProcessors());

        $subject               = new SportDto();
        $subject->sport        = 'football';
        $subject->sportVariant = 'futsal';

        $rulesEngine->process($subject);

        self::assertTrue($subject->needsBall ?? null, sprintf('"needsBall" is wrong, see "%s"', FootballProcessor::class));
        self::assertFalse($subject->needsRacket ?? null, sprintf('"needsRacket" is wrong, see "%s"', FootballProcessor::class));
        self::assertNull($subject->withLateralWalls ?? null, sprintf('"withLateralWalls" is wrong, expected to not support "%s"', FutsalProcessor::class));
        self::assertNull($subject->ballSize ?? null, sprintf('"ballSize" is wrong, expected to not support "%s"', FutsalProcessor::class));
    }

    public function testNotChainedCaseSquash(): void
    {
        $rulesEngine = new RulesEngine('not-chained-case', ['chained' => false], $this->getProcessors());

        $subject        = new SportDto();
        $subject->sport = 'squash';

        $rulesEngine->process($subject);

        self::assertTrue($subject->needsBall ?? null, sprintf('"needsBall" is wrong, see "%s"', FootballProcessor::class));
        self::assertTrue($subject->needsRacket ?? null, sprintf('"needsRacket" is wrong, see "%s"', FootballProcessor::class));
        self::assertNull($subject->withLateralWalls ?? null, sprintf('"withLateralWalls" is wrong, expected to not support "%s"', FutsalProcessor::class));
        self::assertNull($subject->ballSize ?? null, sprintf('"ballSize" is wrong, expected to not support "%s"', FutsalProcessor::class));
    }

    // </editor-fold>

    private function getProcessors(): array
    {
        return [
            new SquashProcessor(),
            new FootballProcessor(),
            new FutsalProcessor(),
        ];
    }
}
