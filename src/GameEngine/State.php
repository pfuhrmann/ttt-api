<?php declare(strict_types=1);

namespace DH\TttApi\GameEngine;

class State
{
    /**
     * @var TttBoard
     */
    private $board;

    /**
     * Winner at this state (if any)
     *  0 => no winner
     *  1 => Player X
     *  2 => Player O
     *
     * @var int|null
     */
    private $winner = 0;

    public function __construct(Board $board)
    {
        $this->board = $board;
        $this->checkForWin();
    }

    public function getLayout(): array
    {
        return $this->board->getLayout();
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
        $newBoard->setCellType(TttBoard::CELL_O, $movePosition[0], $movePosition[1]);

        return new State($newBoard);
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

    public function isGameFinished(): bool
    {
        return $this->hasWinner() || $this->isDraw();
    }

    public function hasWinner(): bool
    {
        return 0 !== $this->winner;
    }

    public function isDraw(): bool
    {
        return $this->board->isFullyOccupied() && !$this->hasWinner();
    }

    public function getWinner(): int
    {
        return $this->winner;
    }

    private function checkForWin(): void
    {
        $this->winner = $this->checkWin() ?: 0;
    }

    /**
     * Check whether this state is winning state
     * @todo: refactor
     *
     * @return int|null winner
     */
    private function checkWin(): ?int
    {
        $board = $this->board;
        $layout = $this->getLayout();

        // Check rows
        $boardSize = count($layout);
        // Check row win
        for ($row = 0; $row < $boardSize; $row++) {
            $possibleWinner = $board->getCellType($row, 0);
            if (Board::CELL_BLANK === $possibleWinner) {
                continue;
            }

            $isWinning = true;
            for ($column = 1; $column < $boardSize; $column++) {
                if ($board->getCellType($row, $column) !== $possibleWinner) {
                    $isWinning = false;
                    continue;
                }
            }

            if ($isWinning) {
                return $possibleWinner;
            }
        }

        // Check column win
        for ($column = 0; $column < $boardSize; $column++) {
            $possibleWinner = $board->getCellType(0, $column);
            if (Board::CELL_BLANK === $possibleWinner) {
                continue;
            }

            $isWinning = true;
            for ($row = 1; $row < $boardSize; $row++) {
                if ($board->getCellType($row, $column) !== $possibleWinner) {
                    $isWinning = false;
                    continue;
                }
            }

            if ($isWinning) {
                return $possibleWinner;
            }
        }

        // Check top-left-to-bottom-right diagonal win
        $possibleWinner = $board->getCellType(0, 0);
        if (Board::CELL_BLANK !== $possibleWinner) {
            $isWinning = true;
            for ($row = 1; $row < $boardSize; $row++) {
                if ($board->getCellType($row, $row) !== $possibleWinner) {
                    $isWinning = false;
                    continue;
                }
            }

            if ($isWinning) {
                return $possibleWinner;
            }
        }

        // Check top-right-to-bottom-left diagonal win
        $maxIndex = $boardSize - 1;
        $possibleWinner = $board->getCellType(0, $maxIndex);
        if (Board::CELL_BLANK !== $possibleWinner) {
            $isWinning = true;
            for ($row = 1; $row < $boardSize; $row++) {
                if ($board->getCellType($row, $maxIndex - $row) !== $possibleWinner) {
                    $isWinning = false;
                    continue;
                }
            }

            if ($isWinning) {
                return $possibleWinner;
            }
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
}
