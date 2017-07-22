<?php declare(strict_types=1);

namespace Tests\Functional;

use DH\TttApi\GameEngine\Board;
use DH\TttApi\GameEngine\TttBoard;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Tests\BaseTttTest;

class GameControllerTest extends BaseTttTest
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
        $this->client = new Client(['base_uri' => 'http://ttt-api-nginx']);
    }

    public function testInit200()
    {
        $response = $this->client->request('GET', '/init');

        $this->assertEquals($response->getStatusCode(), 200);
    }

    public function testInitContainsEmptyLayout()
    {
        $response = $this->getParsedContents($this->client->request('GET', '/init'));

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

        $this->assertEquals(TttBoard::CELL_X, $this->getTypeAtPosition($response['layout'], $position));
    }

    public function testMoveCluelessBot()
    {
        $position = [0, 1];
        $response = $this->getParsedContents($this->requestMove([
            [1, 0, 0],
            [2, 1, 0],
            [2, 0, 0],
        ], $position));

        // We need to check all empty positions for the random counter-move
        $emptyPositions = [[0, 2], [1, 2], [2, 1], [2, 2]];
        foreach ($emptyPositions as $position) {
            $type = $this->getTypeAtPosition($response['layout'], $position);
            if (Board::CELL_BLANK !== $type) {
                $this->assertEquals(TttBoard::CELL_O, $type);
            }
        }
    }

    public function testMoveUnbeatableBot()
    {
        // When first player plays corner, perfect player
        // has to play the center as initial move
        $position = [0, 0];
        $response = $this->getParsedContents($this->requestMove([
            [0, 0, 0],
            [0, 0, 0],
            [0, 0, 0],
        ], $position, 'unbeatable'));

        $this->assertEquals($this->buildLayout([
            [1, 0, 0],
            [0, 2, 0],
            [0, 0, 0],
        ]), $response['layout']);

        // When first player plays center, perfect player
        // has to play the corner as initial move
        $position = [1, 1];
        $response = $this->getParsedContents($this->requestMove([
            [0, 0, 0],
            [0, 0, 0],
            [0, 0, 0],
        ], $position, 'unbeatable'));

        $this->assertEquals($this->buildLayout([
            [2, 0, 0],
            [0, 1, 0],
            [0, 0, 0],
        ]), $response['layout']);
    }

    public function testMoveStateOngoing()
    {
        $this->doTestMove([2, 1], 'ongoing', 0, [
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
     * @expectedException \GuzzleHttp\Exception\ServerException
     */
    public function testMoveIncorrectInput500()
    {
        $response = $this->client->request('POST', '/move', []);

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
        return $this->client->request('POST', '/move', [
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
