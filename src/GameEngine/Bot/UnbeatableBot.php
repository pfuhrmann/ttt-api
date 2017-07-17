<?php declare(strict_types=1);

namespace DH\TttApi\GameEngine\Bot;

class UnbeatableBot extends BaseBot
{
    const NAME = 'unbeatable';

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
        /*@base_score = game_state.available_moves.count + 1
        bound = @base_score + 1
        minmax(game_state, INITIAL_DEPTH, -bound, bound)
        @current_move_choice*/
        $availableMoves = $this->state->getAvailableMoves();

        return $availableMoves[array_rand($this->state->getAvailableMoves())];
    }
}
