<?php declare(strict_types=1);


namespace DH\TttApi\GameEngine\Bot;

/**
 * Does the moves randomly, no knowledge of the game
 */
class CluelessBot extends BaseBot
{
    const NAME = 'clueless';

    /**
     * Computes the move randomly from the available moves
     *
     * {@inheritdoc}
     */
    protected function computeBestMove(): array
    {
        $availableMoves = $this->state->getAvailableMoves();

        return $availableMoves[array_rand($availableMoves)];
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
