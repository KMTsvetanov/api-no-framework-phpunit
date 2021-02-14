<?php


namespace App\Validation;


use App\Exceptions\WrongParamException;

class NewsValidation
{
    public function storeUpdate()
    {
        if (!isset($_POST['title']) || $_POST['title'] === '') {
            throw new WrongParamException(WrongParamException::CODE_5001);
        }
        if (strlen($_POST['title']) > 40) {
            throw new WrongParamException(WrongParamException::CODE_5002 . 40);
        }


        return $this->transform([
            'title' => $_POST['title'],
            'text' => $_POST['text']
        ]);
    }

    private function transform($data)
    {
        return array_map(function ($field) {
            $field = trim($field);
            $field = stripslashes($field);
            $field = htmlspecialchars($field);

            return $field;
        }, $data);
    }
}