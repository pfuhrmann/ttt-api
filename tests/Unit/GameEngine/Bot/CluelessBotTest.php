<?php


namespace Tests\Unit\GameEngine\Bot;

use DH\TttApi\GameEngine\Board;
use DH\TttApi\GameEngine\Bot\CluelessBot;
use DH\TttApi\GameEngine\TttBoard;
use Tests\BaseTttTest;

class CluelessBotTest extends BaseTttTest
{
    public function testGetName()
    {
        $bot = new CluelessBot();
        $this->assertEquals(CluelessBot::NAME, $bot->getName());
    }

    public function testTakeTurn()
    {
        // Check that bot takes turn randomly
        $bot = new CluelessBot();
        $newState = $bot->takeTurn($this->createNewState([
            [1, 2, 0],
            [2, 1, 0],
            [2, 1, 0],
        ], TttBoard::CELL_O));


        // We need to check all empty positions for the random counter-move
        $emptyPositions = [[0, 2], [1, 2], [2, 2]];
        foreach ($emptyPositions as $position) {
            $type = $this->getTypeAtPosition($newState->getLayout(), $position);
            if (Board::CELL_BLANK !== $type) {
                $this->assertEquals(TttBoard::CELL_O, $type);
            }
        }
    }
}
