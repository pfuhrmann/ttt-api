<?php declare(strict_types=1);

namespace DH\TttApi\GameEngine\Bot;

use DH\TttApi\GameEngine\State;
use DH\TttApi\GameEngine\TttBoard;

class UnbeatableBot extends BaseBot
{
    const NAME = 'unbeatable';

    /**
     * Cell coordinates of the best move ex. [0,2]
     *
     * @var array
     */
    private $bestMove;

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     */
    protected function computeBestMove(): array
    {
        $this->minimax($this->state);

        return $this->bestMove;
    }

    /**
     * Minimax algorithm to calculate best possible move
     *
     * @param State $state
     *
     * @return int
     */
    private function minimax(State $state): int
    {
        if ($state->isGameFinished()) {
            return $this->calculateScore($state);
        }

        $scores = [];
        $moves = [];
        foreach ($state->getAvailableMoves() as $movePosition) {
            $newState = $state->move($movePosition);
            $scores[] = $this->minimax($newState);
            $moves[] = $movePosition;
        }

        // Do the min or the max (minimax)
        // Min score calculation
        if (TttBoard::CELL_X === $state->getPlayer()) {
            $maxScoreIndex = $this->getMaxScore($scores);
            $this->bestMove = $moves[$maxScoreIndex];

            return $scores[$maxScoreIndex];
        }

        // Max score calculation
        $minScoreIndex = $this->getMinScore($scores);
        $this->bestMove = $moves[$minScoreIndex];

        return $scores[$minScoreIndex];

    }

    private function calculateScore(State $state): int
    {
        if ($this->xWon($state)) {
            return 10;
        } else if ($this->oWon($state)) {
            return -10;
        }

        return 0;
    }

    private function xWon(State $state): bool
    {
        return TttBoard::CELL_X === $state->getWinner();
    }

    private function oWon(State $state): bool
    {
        return TttBoard::CELL_O === $state->getWinner();
    }

    private function getMaxScore($scores)
    {
        return array_keys($scores, max($scores))[0];
    }

    private function getMinScore($scores)
    {
        return array_keys($scores, min($scores))[0];
    }
}
