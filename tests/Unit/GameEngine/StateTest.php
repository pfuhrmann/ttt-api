<?php declare(strict_types=1);

namespace Tests\Unit\GameEngine;

use DH\TttApi\GameEngine\State;
use DH\TttApi\GameEngine\TttBoard;
use Tests\BaseTttTest;

class StateTest extends BaseTttTest
{
    /**
     * @var State
     */
    private $state;

    public function setUp()
    {
        $this->state = $this->createNewState([
            [0, 0, 2],
            [0, 1, 2],
            [1, 1, 0],
        ]);
    }

    public function testGetAvailableMoves()
    {
        $this->assertCount(4, $this->state->getAvailableMoves());
        $this->assertEquals([[0, 0], [0, 1], [1, 0], [2, 2]], $this->state->getAvailableMoves());
    }

    public function testMove()
    {
        $newState = $this->state->move([0, 1]);

        $this->assertInstanceOf(State::class, $newState);
        $this->assertEquals($this->buildLayout([
            [0, 2, 2],
            [0, 1, 2],
            [1, 1, 0],
        ]), $newState->getLayout());
    }

    public function testHasLastMove()
    {
        $this->assertNull($this->state->hasLastMove());

        $state = $this->createNewState([
            [2, 1, 2],
            [0, 1, 2],
            [1, 2, 1],
        ]);
        $this->assertEquals([1, 0], $state->hasLastMove());
    }

    public function testIsGameFinished()
    {
        $state = $this->createNewState([
            [0, 0, 2],
            [0, 1, 2],
            [1, 1, 0],
        ]);
        $this->assertFalse($state->isGameFinished());

        $state = $this->createNewState([
            [1, 2, 2],
            [2, 1, 2],
            [1, 2, 1],
        ]);
        $this->assertTrue($state->isGameFinished());
    }

    public function testHasWinner()
    {
        $state = $this->createNewState([
            [0, 0, 2],
            [0, 1, 2],
            [1, 1, 0],
        ]);
        $this->assertFalse($state->hasWinner());

        $state = $this->createNewState([
            [2, 2, 2],
            [1, 1, 1],
            [2, 2, 1],
        ]);
        $this->assertTrue($state->hasWinner());
    }

    public function testGetWinner()
    {
        $state = $this->createNewState([
            [2, 1, 2],
            [1, 1, 1],
            [1, 2, 2],
        ]);
        $this->assertEquals(TttBoard::CELL_X, $state->getWinner());

        $state = $this->createNewState([
            [2, 2, 1],
            [1, 2, 1],
            [2, 1, 2],
        ]);
        $this->assertEquals(TttBoard::CELL_O, $state->getWinner());
    }

    public function testIsDraw()
    {
        $state = $this->createNewState([
            [0, 0, 2],
            [0, 1, 2],
            [1, 1, 0],
        ]);
        $this->assertFalse($state->isDraw());

        $state = $this->createNewState([
            [2, 1, 2],
            [1, 1, 2],
            [2, 2, 1],
        ]);
        $this->assertTrue($state->isDraw());
    }
}
