<?php

namespace DH\TttApi\GameEngine;

class TttBoard extends Board
{
    const CELL_X = 1;
    const CELL_O = 2;

    /**
     * Create new instance of Tic-tac-toe board
     *
     * @param int $size Number of rows/columns on the board
     */
    public function __construct($size = 3)
    {
        parent::__construct($size, $size);
    }
}
