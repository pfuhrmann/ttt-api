<?php declare(strict_types=1);

namespace Tests\Unit;

use DH\TttApi\GameEngine\TttBoard;
use Tests\BaseTttTest;

class TttBoardTest extends BaseTttTest
{
    /**
     * @var TttBoard
     */
    private $board;

    public function setUp()
    {
        $this->board = new TttBoard();
    }

    public function testGetBlankCount()
    {
        $this->assertEquals(9, $this->board->getBlankCount());

        $this->board->setLayout($this->buildLayout([
            [0, 0, 2],
            [0, 1, 0],
            [0, 1, 0],
        ]));

        $this->assertEquals(6, $this->board->getBlankCount());
    }

    public function testGetBlanks()
    {
        $this->assertEquals(9, $this->board->getBlankCount());

        $this->board->setLayout($this->buildLayout([
            [0, 0, 2],
            [0, 1, 0],
            [0, 1, 0],
        ]));

        $this->assertCount(6, $this->board->getBlanks());
        $this->assertEquals([[0, 0], [0, 1], [1, 0], [1, 2], [2, 0], [2, 2]], $this->board->getBlanks());
    }

    public function testIsFullyOccupied()
    {
        $this->assertFalse($this->board->isFullyOccupied());

        $this->board->setLayout($this->buildLayout([
            [1, 2, 2],
            [1, 1, 2],
            [2, 1, 1],
        ]));

        $this->assertTrue($this->board->isFullyOccupied());
    }
}
