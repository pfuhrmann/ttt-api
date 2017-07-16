<?php

namespace DH\TttApi\GameEngine;

class State
{
    /**
     * @var Board
     */
    private $board;

    /**
     * Cell coordinates of the move
     * Ex. [0,2]
     *
     * @var array
     */
    private $move;

    public function __construct(Board $board, array $move)
    {
        $this->board = $board;
        $this->move = $move;
    }

    public function getBoard(): Board
    {
        return $this->board;
    }

    public function getMove(): array
    {
        return $this->move;
    }

    public function getLayout(): array
    {
        return $this->board->getLayout();
    }
}
