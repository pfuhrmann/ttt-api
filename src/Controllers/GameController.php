<?php declare(strict_types=1);

namespace DH\TttApi\Controllers;

use DH\TttApi\GameEngine\TttBoard;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
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
        $response->getBody()->write("Player X played the move");
        $board = new TttBoard();

        return $response->withJson(['layout' => $board->getLayout()]);
    }

    /**
     * Play the move on the board by the user and return counter-move from the bot
     *
     * HTTP POST /move
     *
     * @param RequestInterface $request
     * @param Response $response
     *
     * @return ResponseInterface
     */
    public function move(RequestInterface $request, Response $response): ResponseInterface
    {
        $response->getBody()->write("Player X played the move");
        $board = new TttBoard();

        return $response->withJson(['layout' => $board->getLayout()]);
    }
}
