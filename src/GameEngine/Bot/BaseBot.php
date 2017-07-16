<?php declare(strict_types=1);

namespace DH\TttApi\GameEngine\Bot;

use DH\TttApi\GameEngine\State;

abstract class BaseBot implements BotInterface
{
    /**
     * @var State
     */
    protected $state;

    /**
     * {@inheritdoc}
     */
    public function takeTurn(State $state): State
    {
        $this->state = $state;
        $newState = $state->move($this->computeMove());

        return $newState;
    }

    /**
     * Cell coordinates of the next move
     * Ex. [0,2]
     *
     * @return array
     */
    private function computeMove(): array
    {
        if ($this->state->isLastMove()) {
            return $this->state->isLastMove();
        }

        return $this->computeBestMove();
    }

    /**
     * Cell coordinates of the best move
     * Ex. [0,2]
     *
     * @var array
     *
     * @return array
     */
     abstract protected function computeBestMove(): array;

    /**
     * Get bot name
     *
     * @return string
     */
     abstract public function getName(): string;
}
