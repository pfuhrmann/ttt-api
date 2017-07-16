<?php declare(strict_types=1);

namespace DH\TttApi\GameEngine\Bot;

use DH\TttApi\GameEngine\State;

interface BotInterface
{
    /**
     * Advance the game by changing current state to the new one
     *
     * @param State $state
     *
     * @return State
     */
    public function takeTurn(State $state): State;
}
