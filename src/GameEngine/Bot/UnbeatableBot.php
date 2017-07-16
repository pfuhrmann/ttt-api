<?php declare(strict_types=1);

namespace DH\TttApi\GameEngine\Bot;

use DH\TttApi\GameEngine\State;

class UnbeatableBot implements BotInterface
{
    /**
     * {@inheritdoc}
     */
    public function takeTurn(State $state): State
    {
        $state->move($this->computeMove());

        return new State($state->getBoard(), $state->getMovePosition());
    }

    public function computeMove()
    {

    }
}
