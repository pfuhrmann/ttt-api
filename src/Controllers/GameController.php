<?php declare(strict_types=1);

namespace DH\TttApi\Controllers;

use DH\TttApi\GameEngine\Bot\BotFactory;
use DH\TttApi\GameEngine\State;
use DH\TttApi\GameEngine\TttBoard;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class GameController
{
    /**
     * Get new board
     *
     * HTTP GET /board
     *
     * @param RequestInterface $request
     * @param Response         $response
     *
     * @return ResponseInterface
     */
    public function init(RequestInterface $request, Response $response): ResponseInterface
    {
        $board = new TttBoard();

        return $response->withJson(['layout' => $board->getLayout()]);
    }

    /**
     * Play the move on the board by the user and return counter-move from the bot
     *
     * HTTP POST /move
     *
     * @param RequestInterface|Request $request
     * @param Response                 $response
     *
     * @return ResponseInterface
     */
    public function move(Request $request, Response $response): ResponseInterface
    {
        $data = $request->getParsedBody();

        $board = new TttBoard();
        $board = $this->registerPlayerMove($board, $data['layout'], $data['position']);
        $bot = (new BotFactory())->createBot($data['botName']);
        $state = new State($board);
        $state = $bot->takeTurn($state);

        return $response->withJson([
            'layout' => $state->getLayout(),
            'status' => $this->getGameStatus($state),
        ]);
    }

    /**
     * Place the players move on the board
     *
     * @param TttBoard $board
     * @param array    $layout
     * @param array    $movePosition
     *
     * @return TttBoard
     */
    private function registerPlayerMove(TttBoard $board, array $layout, array $movePosition): TttBoard
    {
        $board->setLayout($layout);
        $board->setCellType(TttBoard::CELL_X, $movePosition[0], $movePosition[1]);

        return $board;
    }

    private function getGameStatus(State $state): array
    {
        $winner = $state->getWinner();
        if ($state->hasWinner()) {
            return $this->buildStatus('win', $winner);
        }

        if ($state->isDraw()) {
            return $this->buildStatus('draw', $winner);
        }

        return $this->buildStatus('ongoing', $winner);
    }

    private function buildStatus(string $stateStr, int $winner)
    {
        return ['state' => $stateStr, 'winner' => $winner];
    }
}
