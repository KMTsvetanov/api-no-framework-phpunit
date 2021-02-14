<?php

use App\Exceptions\WrongParamException;
use App\Validation\NewsValidation;
use PHPUnit\Framework\TestCase;
use App\Controllers\NewsController;
use \App\Models\News;
use App\Repository\NewsRepository;
use \App\Response\CustomResponse;

class NewsControllerTest extends TestCase
{
    private $news = [];
    private $newsRepository;
    private $customResponse;

    public function setUp(): void
    {
        // Create a stub for the NewsRepository class.
        $this->newsRepository = $this->getMockBuilder(NewsRepository::class)
            ->getMock();

        // Create a stub for the CustomResponse class.
        $this->customResponse = $this->getMockBuilder(CustomResponse::class)
            ->getMock();
    }

    public static function jsonResponseCallback($argument)
    {
        return !is_bool($argument) ? json_encode($argument) : json_encode(['status' => 'fail', 'message' => 'Not found!']);
    }

    /** @test */
    public function index_return_empty_array_when_no_news()
    {
        $this->newsRepository
            ->expects($this->once())
            ->method('all')
            ->willReturn([]);

        $this->customResponse
            ->expects($this->once())
            ->method('jsonResponse')
            ->will($this->returnCallback(['NewsControllerTest','jsonResponseCallback']));

        $newsController = new NewsController($this->newsRepository, $this->customResponse);

        $this->assertCount(0, json_decode($newsController->index()));
    }

    /** @test */
    public function index_return_expected_news_date_when_we_have_news()
    {
        $news = [];
        $news[] = [
            'id' => 1,
            'title' => 'title 1',
            'date' => '2021-02-13 15:57:12',
            'text' => 'text 1',
        ];

        $news[] = [
            'id' => 2,
            'title' => 'title 2',
            'date' => '2021-02-14 15:57:12',
            'text' => 'text 2',
        ];

        $this->newsRepository
            ->expects($this->once())
            ->method('all')
            ->willReturn($news);

        $this->customResponse
            ->expects($this->once())
            ->method('jsonResponse')
            ->will($this->returnCallback(['NewsControllerTest','jsonResponseCallback']));

        $newsController = new NewsController($this->newsRepository, $this->customResponse);

        $data = json_decode($newsController->index());
        $this->assertCount(2, $data);

        foreach ($data as $key => $datum) {
            $this->assertEquals($news[$key]['id'], $data[$key]->id);
            $this->assertEquals($news[$key]['title'], $data[$key]->title);
            $this->assertEquals($news[$key]['date'], $data[$key]->date);
            $this->assertEquals($news[$key]['text'], $data[$key]->text);
        }
    }

    /** @test */
    public function show_returns_searched_news_in_database()
    {
        $this->customResponse
            ->expects($this->once())
            ->method('jsonResponse')
            ->will($this->returnCallback(['NewsControllerTest','jsonResponseCallback']));

        $newsController = new NewsController($this->newsRepository, $this->customResponse);

        $news = new News([
            'id' => 1,
            'title' => 'title 1',
            'date' => '2021-02-13 15:57:12',
            'text' => 'text 1',
        ]);
        $data = json_decode($newsController->show($news));

        $this->assertEquals($news->getId(), $data->id);
        $this->assertEquals($news->getTitle(), $data->title);
        $this->assertEquals($news->getDate(), $data->date);
        $this->assertEquals($news->getText(), $data->text);
    }

    /** @test */
    public function store_the_news_into_the_database()
    {
        $news = new News([
            'id' => 1,
            'title' => 'title 1',
            'date' => '2021-02-13 15:57:12',
            'text' => 'text 1',
        ]);

        $this->newsRepository
            ->expects($this->once())
            ->method('create')
            ->willReturn(1);

        $this->newsRepository
            ->expects($this->once())
            ->method('findById')
            ->will($this->returnArgument(0))
            ->willReturn([
                'id' => '1',
                'title' => 'title 1',
                'date' => '2021-02-13 15:57:12',
                'text' => 'text 1',
            ]);

        $this->customResponse
            ->expects($this->once())
            ->method('jsonResponse')
            ->will($this->returnCallback(['NewsControllerTest','jsonResponseCallback']));

        $newsController = new NewsController($this->newsRepository, $this->customResponse);

        $validation = $this->getMockBuilder(NewsValidation::class)
            ->getMock();
        $validation->expects($this->once())
            ->method('storeUpdate')
            ->willReturn([
                'title' => 'title 1',
                'text' => 'text 1',
            ]);

        $data = json_decode($newsController->store($validation));

        $this->assertEquals($news->getId(), $data->id);
        $this->assertEquals($news->getTitle(), $data->title);
        $this->assertEquals($news->getDate(), $data->date);
        $this->assertEquals($news->getText(), $data->text);
    }

    /** @test */
    public function update_the_news_into_the_database()
    {
        $news = new News([
            'id' => 1,
            'title' => 'title 1',
            'date' => '2021-02-13 15:57:12',
            'text' => 'text 1',
        ]);

        $this->newsRepository
            ->expects($this->once())
            ->method('update')
            ->willReturn(true);

        $this->customResponse = $this->getMockBuilder(CustomResponse::class)
            ->getMock();

        $this->customResponse
            ->expects($this->once())
            ->method('jsonResponse')
            ->will($this->returnCallback(['NewsControllerTest','jsonResponseCallback']));

        $newsController = new NewsController($this->newsRepository, $this->customResponse);

        $validation = $this->getMockBuilder(NewsValidation::class)
            ->getMock();

        $validation->expects($this->once())
            ->method('storeUpdate')
            ->willReturn([
                'title' => 'title 11',
                'text' => 'text 12',
            ]);

        $data = json_decode($newsController->update($news, $validation));

        $this->assertEquals('success', $data->status);
    }

    /** @test */
    public function destroy_the_news_from_the_database()
    {
        $news = new News([
            'id' => 1,
            'title' => 'title 1',
            'date' => '2021-02-13 15:57:12',
            'text' => 'text 1',
        ]);

        $this->newsRepository
            ->expects($this->once())
            ->method('delete')
            ->willReturn(true);

        $this->customResponse = $this->getMockBuilder(CustomResponse::class)
            ->getMock();

        $this->customResponse
            ->expects($this->once())
            ->method('jsonResponse')
            ->will($this->returnCallback(['NewsControllerTest','jsonResponseCallback']));

        $newsController = new NewsController($this->newsRepository, $this->customResponse);

        $data = json_decode($newsController->destroy($news));

        $this->assertEquals('success', $data->status);
    }
}