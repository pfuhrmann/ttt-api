<?php

use DH\TttApi\GameEngine\Board;
use DH\TttApi\GameEngine\TttBoard;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class GameControllerTest extends TestCase
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->client = new Client(['base_uri' => 'http://localhost:8888/']);
    }

    public function testInit200()
    {
        $response = $this->client->request('GET', 'api/init');

        $this->assertEquals($response->getStatusCode(), 200);
    }

    public function testInitContainsEmptyLayout()
    {
        $response = $this->getParsedContents($this->client->request('GET', 'api/init'));

        $this->assertCount(3, $response['layout']);

        // Check that initial layout is all empty
        $layout = $response['layout'];
        $rows = count($layout);
        for ($row = 0; $row < $rows; $row++) {
            for ($column = 0; $column < $rows; $column++) {
                $this->assertEquals(Board::CELL_BLANK, $layout[$row][$column]['type']);
            }
        }
    }

    public function testMove200()
    {
        $response = $this->requestInitialMove();

        $this->assertEquals($response->getStatusCode(), 200);
    }

    public function testMoveContainsBody()
    {
        $response = $this->getParsedContents($this->requestInitialMove());

        $this->assertArrayHasKey('layout', $response);
        $this->assertCount(3, $response['layout']);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('state', $response['status']);
        $this->assertArrayHasKey('winner', $response['status']);
        $this->assertInternalType('string', $response['status']['state']);
        $this->assertInternalType('int', $response['status']['winner']);
    }

    public function testMoveRegistersPlayersPosition()
    {
        $position = [0, 1];
        $response = $this->getParsedContents($this->requestMove([
            [1, 0, 0],
            [2, 1, 0],
            [2, 0, 0],
        ], $position));

        $this->assertEquals(TttBoard::CELL_X, $response['layout'][$position[0]][$position[1]]['type']);
    }

    public function testMoveStateOngoing()
    {
        $this->doTestMove([1, 2], 'ongoing', 0, [
            [1, 0, 0],
            [2, 1, 0],
            [2, 0, 0],
        ]);
    }

    public function testMoveStateDraw()
    {
        $this->doTestMove([2, 2], 'draw', 0, [
            [2, 1, 2],
            [2, 1, 1],
            [1, 2, 0],
        ]);
    }

    public function testMoveStateBotWin()
    {
        $this->doTestMove([2, 2], 'win', TttBoard::CELL_O, [
            [2, 1, 1],
            [2, 1, 1],
            [2, 2, 0],
        ]);
    }

    public function testMoveStateWinRow()
    {
        $this->doTestMove([2, 2], 'win', TttBoard::CELL_X, [
            [1, 1, 1],
            [2, 1, 2],
            [2, 2, 0],
        ]);
    }

    public function testMoveStateWinColumn()
    {
        $this->doTestMove([2, 0], 'win', TttBoard::CELL_X, [
            [1, 1, 1],
            [2, 2, 1],
            [0, 2, 1],
        ]);
    }

    public function testMoveStateWinTopRightLeftBottomDiagonal()
    {
        $this->doTestMove([2, 0], 'win', TttBoard::CELL_X, [
            [1, 1, 2],
            [2, 1, 1],
            [0, 2, 1],
        ]);
    }

    public function testMoveStateWinTopLeftRightBottomDiagonal()
    {
        $this->doTestMove([2, 2], 'win', TttBoard::CELL_X, [
            [2, 2, 1],
            [2, 1, 0],
            [1, 2, 0],
        ]);
    }

    public function testMove4X4()
    {
        $this->doTestMove([0, 0], 'win', TttBoard::CELL_X, [
            [0, 0, 1, 1],
            [2, 1, 1, 0],
            [1, 1, 2, 2],
            [1, 2, 2, 0],
        ]);
    }

    public function testMove5X5()
    {
        $this->doTestMove([0, 0], 'win', TttBoard::CELL_X, [
            [0, 0, 1, 1, 1],
            [2, 1, 1, 0, 0],
            [1, 1, 1, 2, 2],
            [1, 2, 1, 2, 0],
            [1, 2, 1, 0, 0],
        ]);
    }

    /**
     * @expectedException GuzzleHttp\Exception\ServerException
     */
    public function testMoveIncorrectInput500()
    {
        $response = $this->client->request('POST', 'api/move', []);

        $this->assertEquals($response->getStatusCode(), 200);
    }

    private function requestInitialMove(): ResponseInterface
    {
        return $this->requestMove([
            [0, 0, 0],
            [0, 0, 0],
            [0, 0, 0],
        ]);
    }

    private function requestMove(array $layoutTypes, array $position = [0, 2], string $botName = 'clueless'): ResponseInterface
    {
        return $this->client->request('POST', 'api/move', [
            'headers' => ['content-type' => 'application/json'],
            'body' => json_encode([
                'position' => $position,
                'botName' => $botName,
                'layout' => $this->buildLayout($layoutTypes),
            ])
        ]);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return array
     */
    private function getParsedContents(ResponseInterface $response): array
    {
        return json_decode($response->getBody()->getContents(), true);
    }

    private function buildLayout(array $layoutTypes): array
    {
        $rows = count($layoutTypes);
        $layout = [];
        for ($row = 0; $row < $rows; $row++) {
            for ($column = 0; $column < $rows; $column++) {
                $layout[$row][$column]['type'] = $layoutTypes[$row][$column];
            }
        }

        return $layout;
    }

    private function assertStatus(array $response, string $state, int $winner)
    {
        $status = $response['status'];
        $this->assertEquals($state, $status['state']);
        $this->assertEquals($winner, $status['winner']);
    }

    private function doTestMove(array $position, string $state, int $winner, array $layoutTypes)
    {
        $response = $this->getParsedContents($this->requestMove($layoutTypes, $position));

        $this->assertStatus($response, $state, $winner);
    }
}
