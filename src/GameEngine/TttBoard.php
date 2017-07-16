<?php declare(strict_types=1);

namespace DH\TttApi\GameEngine;

class TttBoard extends Board
{
    const CELL_X = 1;
    const CELL_O = 2;

    /**
     * @var int
     */
    private $blankCount = 0;

    /**
     * @var int
     */
    private $takenCount = 0;

    /**
     * Blank points on the board
     *
     * @var array
     */
    private $blanks = [];

    public function __construct(int $size = 3)
    {
        parent::__construct($size, $size);
        $this->blankCount = pow($size, 2);

    }

    /**
     * @return int
     */
    public function getBlankCount(): int
    {
        return $this->blankCount;
    }

    /**
     * {@inheritdoc}
     */
    public function setLayout(array $layout): void
    {
        $this->blanks = [];

        for ($row = 0; $row < count($layout); $row++) {
            for ($column = 0; $column < count($layout[$row]); $column++) {
                $this->setPointType($layout[$row][$column]['type'], $row, $column);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setPointType(int $type, int $row, int $col): void
    {
        parent::setPointType($type, $row, $col);

        if (self::CELL_BLANK !== $type) {
            $this->takenCount++;
            $this->removeBlank($row, $col);
        } else {
            array_push($this->blanks, [$row, $col]);
        }
    }

    private function removeBlank(int $row, int $col): void
    {
        $this->blankCount--;
        if (($key = array_search([$row, $col], $this->blanks)) !== false) {
            unset($this->blanks[$key]);
        }
    }

    /**
     * @return array
     */
    public function getBlanks(): array
    {
        return $this->blanks;
    }

    /**
     * Checks whether board is blank
     *
     * @return bool
     */
    /*public function isBlank(): bool
    {
        return pow($this->rows, 2) === $this->blankCount;
    }*/

    public function isFullyOccupied(): bool
    {
        return 0 === $this->blankCount;
    }
}
