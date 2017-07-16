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
     * Winner at this state (if any)
     *  0 => no winner
     *
     * @var int|null
     */
    private $winner = 0;

    public function __construct(Board $board)
    {
        $this->board = $board;
        $this->minWinMoves = (2 * $board->getRows()) - 1;
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

    /**
     * Returns coordinates of the first available move
     * Ex. [0,2]
     *
     * @return array|null
     */
    private function getFirstAvailableMove(): array
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

    public function getBoard(): TttBoard
    {
        return $this->board;
    }

    public function isDraw(): bool
    {
        return $this->isGameFinished() && !$this->hasWinner();
    }

    public function isGameFinished(): bool
    {
        return $this->board->isFullyOccupied();
    }

    public function hasWinner(): bool
    {
        return 0 !== $this->winner;
    }

    public function getWinner(): int
    {
        return $this->winner;
    }
}
