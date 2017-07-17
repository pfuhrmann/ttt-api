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

    public function testInitContainsValues()
    {
        $response = $this->client->request('GET', 'api/init');
        $response = json_decode($response->getBody()->getContents(), true);

        $this->assertCount(3, $response['layout']);

        // Check that initial is all empty
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
        $response = $this->requestMove([
            [0, 0, 0],
            [0, 0, 0],
            [0, 0, 0],
        ]);

        $this->assertEquals($response->getStatusCode(), 200);
    }

    /**
     * @expectedException GuzzleHttp\Exception\ServerException
     */
    public function testMoveIncorrectInput500()
    {
        $response = $this->client->request('POST', 'api/move', []);

        $this->assertEquals($response->getStatusCode(), 200);
    }

    private function requestMove(array $layoutTypes, array $position = [0, 2], string $botName = 'clueless'): ResponseInterface
    {
        $rows = count($layoutTypes);
        $layout = [];
        for ($row = 0; $row < $rows; $row++) {
            for ($column = 0; $column < $rows; $column++) {
                $layout['type'] = $layoutTypes[$row][$column];
            }
        }

        return $this->client->request('POST', 'api/move', [
            'content-type' => 'application/json',
            'body' => json_encode(['position' => $position, 'botName' => $botName, 'layout' => $layout])
        ]);
    }
}
