<?php declare(strict_types=1);

namespace DH\TttApi\GameEngine\Bot;

use DH\TttApi\GameEngine\Exceptions\BotNotSupportedException;

class BotFactory
{
    /**
     * Create new bot based on the bot type
     *
     * @param string $name
     *
     * @return BotInterface
     */
    public function createBot(string $name): BotInterface
    {
        switch ($name) {
            case CluelessBot::NAME:
                return new CluelessBot();
                break;
            case UnbeatableBot::NAME:
                return new UnbeatableBot();
                break;
        }

        throw new BotNotSupportedException('Unsupported type of the bot "' . $name . '"');
    }
}
