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
    private $movePosition;

    public function __construct(Board $board, array $movePosition)
    {
        $this->board = $board;
        $this->movePosition = $movePosition;
    }

    public function getBoard(): Board
    {
        return $this->board;
    }

    public function getMovePosition(): array
    {
        return $this->movePosition;
    }

    public function getLayout(): array
    {
        return $this->board->getLayout();
    }

    public function move($computeMove)
    {
    }
}
