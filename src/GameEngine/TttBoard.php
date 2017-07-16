<?php

namespace DH\TttApi\GameEngine;

class TttBoard extends Board
{
    const CELL_X = 1;
    const CELL_O = 2;

    /**
     * @var int
     */
    private $blankCount;

    /**
     * @var int
     */
    private $takenCount;

    /**
     * @var array
     */
    private $blanks = [];

    public function __construct(int $size = 3)
    {
        parent::__construct($size, $size);
        $this->blankCount = $this->takenCount = pow($size, 2);

    }

    /**
     * {@inheritdoc}
     */
    public function setPointType(int $type, int $row, int $col): void
    {
        parent::setPointType($type, $row, $col);

        if (self::CELL_EMPTY !== $type) {
            $this->blankCount--;
            $this->takenCount++;
        } else {
            $this->blanks = array_push($this->blanks, [$row, $col]);
        }
    }
}
