<?php declare(strict_types=1);

namespace Tests\Unit\GameEngine\Bot;

use DH\TttApi\GameEngine\Bot\BaseBot;
use DH\TttApi\GameEngine\State;
use Tests\BaseTttTest;

class BaseBotTest extends BaseTttTest
{
    public function testTakeTurn()
    {
        /** @var BaseBot|\PHPUnit_Framework_MockObject_MockObject $baseBot */
        $baseBot = $this->getMockForAbstractClass(BaseBot::class);
        $baseBot->expects($this->any())
            ->method('computeBestMove')
            ->will($this->returnValue([2, 2]));

        $state = $this->createNewState([
            [0, 0, 2],
            [0, 1, 2],
            [1, 1, 0],
        ]);
        $newState = $baseBot->takeTurn($state);

        $this->assertInstanceOf(State::class, $newState);
        $this->assertEquals($this->createNewState([
            [0, 0, 2],
            [0, 1, 2],
            [1, 1, 2],
        ]), $newState);
    }
}
