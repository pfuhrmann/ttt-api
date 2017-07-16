<?php

namespace DH\TttApi\GameEngine;

class TttBoard extends Board
{
    const CELL_X = 1;
    const CELL_O = 2;

    public function __construct(int $size = 3)
    {
        parent::__construct($size, $size);
    }
}
