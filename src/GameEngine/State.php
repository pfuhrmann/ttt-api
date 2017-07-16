<?php declare(strict_types=1);

namespace DH\TttApi\GameEngine;

class State
{
    /**
     * @var TttBoard
     */
    private $board;

    /**
     * @var int
     */
    private $minWinMoves;

    /**
     * Cell coordinates of the winning point
     * Ex. [0,2]
     *
     * @var array
     */
    private $winner;

    public function __construct(Board $board)
    {
        $this->board = $board;
        $this->minWinMoves = (2 * $board->getRows()) - 1;
    }

    public function getBoard(): TttBoard
    {
        return $this->board;
    }

    /**
     * Advance the position on the board
     *
     * @param array $movePosition Cell coordinates of the new board move ex. [0,2]
     *
     * @return State new state after the move
     */
    public function move(array $movePosition): State
    {
        $newBoard = clone $this->board;
        $newBoard->setPointType(TttBoard::CELL_O, $movePosition[0], $movePosition[1]);

        return new State($newBoard);
    }

    public function checkWin()
    {
        $layout = $this->getLayout();
    }

    public function getLayout(): array
    {
        return $this->board->getLayout();
    }

    /**
     * Check if this is last move and returns corresponding coordinates
     * Ex. [0,2]
     *
     * @return array|null
     */
    public function isLastMove(): ?array
    {
        if ($this->board->getBlankCount() === 1) {
            return $this->getFirstAvailableMove();
        }

        return null;
    }

    private function getFirstAvailableMove()
    {
        return array_pop(array_reverse($this->getAvailableMoves()));
    }

    /**
     * Get array of available moves in the current state
     *
     * @return array
     */
    public function getAvailableMoves(): array
    {
        return $this->getBoard()->getBlanks();
    }
}
