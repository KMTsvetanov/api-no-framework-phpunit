<?php

use App\Db\DBConnection;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

/**
 * Class NewsTest
 */
class NewsTest extends TestCase
{
    /**
     * @var Client
     */
    private Client $http;

    /**
     *
     */
    public function setUp(): void
    {
        $this->http = new Client(['base_uri' => 'http://api.local/']);
    }

    /**
     *
     */
    public static function tearDownAfterClass(): void
    {
        $db = DBConnection::getInstance();
        $stm = $db->prepare('DELETE FROM news WHERE title = ?');

        $stm->execute(['phpUnit create']);
    }

    /** @test */
    public function get_all()
    {
        $response = $this->http->request('GET', 'news');
        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()['Content-Type'][0];
        $this->assertEquals('application/json; charset=UTF-8', $contentType);

        $news = json_decode($response->getBody(), true);

        $this->assertEquals('1', $news[0]['id']);
        $this->assertEquals('1234567890123456789012345678901234567893', $news[0]['title']);
        $this->assertEquals('2021-02-14 15:19:06', $news[0]['date']);
        $this->assertEquals('Кики', $news[0]['text']);
    }

    /** @test */
    public function get_one()
    {
        $response = $this->http->request('GET', 'news/1');
        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()['Content-Type'][0];
        $this->assertEquals('application/json; charset=UTF-8', $contentType);

        $news = json_decode($response->getBody());

        $this->assertEquals('1', $news->id);
        $this->assertEquals('1234567890123456789012345678901234567893', $news->title);
        $this->assertEquals('2021-02-14 15:19:06', $news->date);
        $this->assertEquals('Кики', $news->text);
    }

    /** @test */
    public function create_one_news()
    {
        $options = [
            'form_params' => [
                'title' => 'phpUnit create',
                'text' => 'text 123',
            ],
            'headers' => [
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1c2VyQGV4YW1wbGUuY29tIn0.q-_StMZv9CZ1bIAZ6PR9x9mUUg1hEDZ7dPakaPWXCig'
            ]
        ];

        $response = $this->http->request('POST', 'news', $options);
        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()['Content-Type'][0];
        $this->assertEquals('application/json; charset=UTF-8', $contentType);

        $news = json_decode($response->getBody());

        $this->assertEquals('phpUnit create', $news->title);
        $this->assertEquals('text 123', $news->text);
    }

    /** @test */
    public function update_one_news()
    {
        $options = [
            'form_params' => [
                'title' => 'title1 ' . date("h:i:sa"),
                'text' => 'text1',
            ],
            'headers' => [
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1c2VyQGV4YW1wbGUuY29tIn0.q-_StMZv9CZ1bIAZ6PR9x9mUUg1hEDZ7dPakaPWXCig'
            ]
        ];

        $response = $this->http->request('POST', 'news/2', $options);
        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()['Content-Type'][0];
        $this->assertEquals('application/json; charset=UTF-8', $contentType);

        $response = json_decode($response->getBody());

        $this->assertEquals('success', $response->status);
    }
}