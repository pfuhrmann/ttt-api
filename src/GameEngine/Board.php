<?php declare(strict_types=1);

namespace DH\TttApi\GameEngine;

class Board
{
    const CELL_EMPTY = 0;
    const CELL_X = 1;
    const CELL_O = 2;

    /**
     * @var int
     */
    private $rows;

    /**
     * @var int
     */
    private $columns;

    /**
     * @var array
     */
    private $layout;

    /**
     * Create new instance of Board
     *
     * @param int $rows Number of rows on the board
     * @param int $columns Number of columns on the board
     */
    public function __construct($rows = 3, $columns = 3)
    {
        $this->rows = $rows;
        $this->columns = $columns;
        $this->initBoard();
    }

    /**
     * Initialize empty gaming board
     *  - Empty board is 2D array with
     *     all point type set to 0 (int)
     *  Example:  0|0|0
     *            0|0|0
     *            0|0|0
     */
    private function initBoard(): void
    {
        $layout = [];

        for ($row = 0; $row < $this->rows; $row++) {
            for ($column = 0; $column < $this->columns; $column++) {
                $layout[$row][$column]['type'] = self::CELL_EMPTY;
            }
        }

        $this->layout = $layout;
    }

    /**
     * @return int
     */
    public function getRows(): int
    {
        return $this->rows;
    }

    /**
     * @return int
     */
    public function getColumns(): int
    {
        return $this->columns;
    }

    /**
     * Get simple board layout (without edges)
     *
     * @return array
     */
    public function getLayout(): array
    {
        return $this->layout;
    }

    /**
     * @param array $layout
     */
    public function setLayout(array $layout)
    {
        $this->layout = $layout;
    }

    /**
     * Fetch specific point type from the
     *  board cell based on row, col location
     *
     * @param int $row Fetching point row
     * @param int $col Fetching point column
     *
     * @return string|null Fetched value otherwise false if point does not exist
     */
    public function getCellType(int $row, int $col): ?string
    {
        if (isset($this->layout[$row][$col])) {
            return $this->layout[$row][$col]['type'];
        }

        return null;
    }

    /**
     * Set the type of cell on the board
     *  in the specific location
     *
     * @param int $type Type to be set
     * @param int $row Setting point row
     * @param int $col Setting point column
     */
    public function setPointType(int $type, int $row, int $col): void
    {
        $this->layout[$row][$col]['type'] = $type;
    }
}
