<?php


namespace Tests\Unit\GameEngine\Bot;

use DH\TttApi\GameEngine\Bot\UnbeatableBot;
use DH\TttApi\GameEngine\TttBoard;
use Tests\BaseTttTest;

class UnbeatableBotTest extends BaseTttTest
{
    public function testGetName()
    {
        $bot = new UnbeatableBot();
        $this->assertEquals(UnbeatableBot::NAME, $bot->getName());
    }

    public function testTakeTurn()
    {
        // Check that bot makes perfect choices
        $bot = new UnbeatableBot();
        $newState = $bot->takeTurn($this->createNewState([
            [1, 0, 0],
            [0, 0, 0],
            [0, 0, 0],
        ], TttBoard::CELL_O));

        $this->assertEquals(2, $this->getTypeAtPosition($newState->getLayout(), [1, 1]));

        $bot = new UnbeatableBot();
        $newState = $bot->takeTurn($this->createNewState([
            [0, 0, 0],
            [0, 1, 0],
            [0, 0, 0],
        ], TttBoard::CELL_O));

        $this->assertEquals(2, $this->getTypeAtPosition($newState->getLayout(), [0, 0]));
    }
}
