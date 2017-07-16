<?php declare(strict_types=1);

namespace DH\TttApi\Controllers;

use DH\TttApi\GameEngine\Bot\UnbeatableBot;
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
     * @param Response $response
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
     * @param Response $response
     *
     * @return ResponseInterface
     */
    public function move(Request $request, Response $response): ResponseInterface
    {
        $data = $request->getParsedBody();
        $board = new TttBoard();
        $board->setLayout($data['layout']);
        $board->setPointType(TttBoard::CELL_X, $data['position'][0], $data['position'][1]);

        $state = new State($board, $data['position']);
        $bot = new UnbeatableBot();
        $state = $bot->takeTurn($state);

        return $response->withJson(['layout' => $state->getLayout()]);
    }
}
