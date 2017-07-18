<?php declare(strict_types=1);

namespace Tests\Unit;

use DH\TttApi\GameEngine\Board;
use Tests\BaseTttTest;

class BoardTest extends BaseTttTest
{
    /**
     * @var Board
     */
    private $board;

    public function setUp()
    {
        $this->board = new Board(2, 3);
    }

    public function testGetRows()
    {
        $this->assertEquals(2, $this->board->getRows());
    }

    public function testGetColumns()
    {
        $this->assertEquals(3, $this->board->getColumns());
    }

    public function testGetLayout()
    {
        $this->assertEquals($this->buildLayout([
            [0, 0, 0],
            [0, 0, 0],
        ]), $this->board->getLayout());
    }

    public function testGetCellType()
    {
        $this->assertEquals(0, $this->board->getCellType(0, 0));

        $this->board->setLayout($this->buildLayout([
            [0, 0, 2],
            [0, 1, 0],
        ]));

        $this->assertEquals(1, $this->board->getCellType(1, 1));
        $this->assertEquals(2, $this->board->getCellType(0, 2));
    }

    public function testSetCellType()
    {
        $this->board->setCellType(2, 1, 0);

        $this->assertEquals($this->buildLayout([
            [0, 0, 0],
            [2, 0, 0],
        ]), $this->board->getLayout());
    }
}
