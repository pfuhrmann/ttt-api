<?php declare(strict_types=1);

namespace Tests\Unit\GameEngine\Bot;

use DH\TttApi\GameEngine\Bot\BotFactory;
use DH\TttApi\GameEngine\Bot\CluelessBot;
use DH\TttApi\GameEngine\Bot\UnbeatableBot;
use Tests\BaseTttTest;

class BotFactoryTest extends BaseTttTest
{
    public function testCreateBot()
    {
        $factory = new BotFactory();

        $this->assertInstanceOf(CluelessBot::class, $factory->createBot(CluelessBot::NAME));
        $this->assertInstanceOf(UnbeatableBot::class, $factory->createBot(UnbeatableBot::NAME));
    }

    /**
     * @expectedException \DH\TttApi\GameEngine\Exceptions\BotNotSupportedException
     */
    public function testCreateBotUnsupported()
    {
        $factory = new BotFactory();
        $factory->createBot('notexistentbot');
    }
}
