<?php

namespace App\Response;


/**
 * Class CustomResponse
 * @package App\Response
 */
class CustomResponse
{
    /**
     * @param $data
     * @return bool|string
     */
    public function jsonResponse($data): bool|string
    {
        return !is_bool($data) ? json_encode($data) : json_encode(['status' => 'fail', 'message' => 'Not found!']);
    }
}