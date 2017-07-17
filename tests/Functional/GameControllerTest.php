<?php

use DH\TttApi\GameEngine\Board;
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

    public function testMoveRegistersPosition()
    {
        $position = [0, 1];
        $response = $this->getParsedContents($this->requestMove([
            [1, 0, 0],
            [0, 1, 0],
            [0, 0, 0],
        ], $position));

        $this->assertEquals(1, $response['layout'][$position[0]][$position[1]]['type']);
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
}
