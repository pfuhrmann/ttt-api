<?php declare(strict_types=1);

namespace Tests;

use DH\TttApi\GameEngine\State;
use DH\TttApi\GameEngine\TttBoard;
use PHPUnit\Framework\TestCase;

abstract class BaseTttTest extends TestCase
{
    protected function buildLayout(array $layoutTypes): array
    {
        $rows = count($layoutTypes);
        $columns = count($layoutTypes[0]);
        $layout = [];
        for ($row = 0; $row < $rows; $row++) {
            for ($column = 0; $column < $columns; $column++) {
                $layout[$row][$column]['type'] = $layoutTypes[$row][$column];
            }
        }

        return $layout;
    }

    protected function createNewState(array $layoutTypes, int $player = TttBoard::CELL_X): State
    {
        $board = new TttBoard();
        $board->setLayout($this->buildLayout($layoutTypes));

        return new State($board, $player);
    }

    protected function getTypeAtPosition(array $layout, array $position): int
    {
        return $layout[$position[0]][$position[1]]['type'];
    }
}
