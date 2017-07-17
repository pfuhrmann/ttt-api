<?php

use DH\TttApi\GameEngine\Board;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;

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

    public function testGetInit200()
    {
        $response = $this->client->request('GET', 'api/init');

        $this->assertEquals($response->getStatusCode(), 200);
    }

    public function testGetInitContainsValues()
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
}
