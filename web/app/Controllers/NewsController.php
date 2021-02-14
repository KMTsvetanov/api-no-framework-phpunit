<?php


namespace App\Controllers;


use App\Models\News;
use App\Repository\NewsRepository;
use App\Response\CustomResponse;
use App\Exceptions\WrongParamException;
use App\Validation\NewsValidation;
use App\Db\DBConnection;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use PDO;
use http\Client\Request;

/**
 * Class NewsController
 * @package App
 */
class NewsController
{
    /**
     * @var NewsRepository
     */
    private NewsRepository $newsRepository;
    /**
     * @var CustomResponse
     */
    private CustomResponse $customResponse;

    /**
     * News constructor.
     * @param NewsRepository $newsRepository
     * @param CustomResponse $customResponse
     */
    public function __construct(NewsRepository $newsRepository, CustomResponse $customResponse)
    {
        $this->newsRepository = $newsRepository;
        $this->customResponse = $customResponse;
    }

    /**
     * @return bool|string
     */
    public function index(): bool|string
    {
        $data = $this->newsRepository->all();

        return $this->customResponse->jsonResponse($data);
    }

    /**
     * @param News $news
     * @return bool|string
     */
    public function show(News $news): bool|string
    {
        return $this->customResponse->jsonResponse($news);
    }

    /**
     * @param NewsValidation $validation
     * @return bool|string
     * @throws WrongParamException
     */
    public function store(NewsValidation $validation): bool|string
    {
        $data = $validation->storeUpdate();

        $id = $this->newsRepository->create($data);

        $news = $this->newsRepository->findById($id);

        return $this->customResponse->jsonResponse($news);
    }

    /**
     * @param News $news
     * @param NewsValidation $validation
     * @return bool|string
     * @throws WrongParamException
     */
    public function update(News $news, NewsValidation $validation): bool|string
    {
        $data = $validation->storeUpdate();

        $news->setTitle($data['title']);
        $news->setText($data['text']);

        $updated = $this->newsRepository->update($news);

        $response = $updated ? ['status' => 'success'] : ['status' => 'fail'];

        return $this->customResponse->jsonResponse($response);
    }

    /**
     * @param News $news
     * @return bool|string
     */
    public function destroy(News $news): bool|string
    {
        $deleted = $this->newsRepository->delete($news);

        $response = $deleted ? ['status' => 'success'] : ['status' => 'fail'];
        return $this->customResponse->jsonResponse($response);
    }
}