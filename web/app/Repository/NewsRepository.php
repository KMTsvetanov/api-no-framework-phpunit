<?php

namespace App\Repository;

use App\Db\DBConnection;
use PDO;

/**
 * Class PostRepository
 * @package App\Repository
 */
class NewsRepository
{
    /**
     * @return array
     */
    public function all(): array
    {
        $db = DBConnection::getInstance();
        $stm = $db->prepare("SELECT * FROM news");
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id): mixed
    {
        $db = DBConnection::getInstance();
        $stm = $db->prepare("SELECT * FROM news WHERE id = ?");
        $stm->execute([$id]);

        return $stm->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        $db = DBConnection::getInstance();
        $stm= $db->prepare("INSERT INTO news (title, text) VALUES (:title, :text)");
        $stm->execute($data);
        return $db->lastInsertId();
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($news)
    {
        $db = DBConnection::getInstance();
        $stm = $db->prepare("UPDATE news SET title = ?, date = now(), text = ? WHERE id = ?");

        return $stm->execute([$news->getTitle(), $news->getText(), $news->getId()]);
    }

    /**
     * @param $news
     * @return mixed
     */
    public function delete($news)
    {
        $db = DBConnection::getInstance();
        $stm = $db->prepare('DELETE FROM news WHERE id = ?');

        return $stm->execute([$news->getId()]);
    }
}