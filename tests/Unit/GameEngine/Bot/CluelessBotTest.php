<?php


namespace Tests\Unit\GameEngine\Bot;

use DH\TttApi\GameEngine\Bot\CluelessBot;
use Tests\BaseTttTest;

class CluelessBotTest extends BaseTttTest
{
    public function testGetName()
    {
        $bot = new CluelessBot();
        $this->assertEquals(CluelessBot::NAME, $bot->getName());
    }
}
