<?php declare(strict_types=1);
/*
 * This file is part of sebastian/lines-of-code.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\LinesOfCode;

use function file_get_contents;
use PhpParser\Lexer;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \SebastianBergmann\LinesOfCode\Counter
 * @covers \SebastianBergmann\LinesOfCode\LineCountingVisitor
 *
 * @uses \SebastianBergmann\LinesOfCode\LinesOfCode
 *
 * @medium
 */
final class CounterTest extends TestCase
{
    public function testCountsLinesOfCodeInSourceFile(): void
    {
        $count = (new Counter)->countInSourceFile(__DIR__ . '/../_fixture/ExampleClass.php');

        $this->assertSame(51, $count->linesOfCode());
        $this->assertSame(13, $count->commentLinesOfCode());
        $this->assertSame(38, $count->nonCommentLinesOfCode());
        $this->assertSame(23, $count->logicalLinesOfCode());
    }

    public function testCountsLinesOfCodeInSourceString(): void
    {
        $count = (new Counter)->countInSourceString(file_get_contents(__DIR__ . '/../_fixture/ExampleClass.php'));

        $this->assertSame(51, $count->linesOfCode());
        $this->assertSame(13, $count->commentLinesOfCode());
        $this->assertSame(38, $count->nonCommentLinesOfCode());
        $this->assertSame(23, $count->logicalLinesOfCode());
    }

    public function testCountsLinesOfCodeInAbstractSyntaxTree(): void
    {
        $nodes = $this->parser()->parse(
            file_get_contents(__DIR__ . '/../_fixture/ExampleClass.php')
        );

        assert($nodes !== null);

        $count = (new Counter)->countInAbstractSyntaxTree(51, $nodes);

        $this->assertSame(51, $count->linesOfCode());
        $this->assertSame(13, $count->commentLinesOfCode());
        $this->assertSame(38, $count->nonCommentLinesOfCode());
        $this->assertSame(23, $count->logicalLinesOfCode());
    }

    private function parser(): Parser
    {
        return (new ParserFactory)->create(ParserFactory::PREFER_PHP7, new Lexer);
    }
}
