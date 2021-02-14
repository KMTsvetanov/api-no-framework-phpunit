<?php

namespace App\Models;

use JsonSerializable;
/**
 * Class News
 * @package App\Models
 */
class News implements JsonSerializable
{
    protected int $id;
    protected string $title;
    protected string $date;
    protected string $text;

    public function __construct($data)
    {
        foreach ($data as $field => $value) {
            $function = 'set' . ucfirst($field);
            if (method_exists($this, $function)) {
                $this->$function($value);
            }
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'date' => $this->getDate(),
            'text' => $this->getText(),
        ];

    }
}