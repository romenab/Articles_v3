<?php

namespace Articles\Models;
class Article
{
    private string $uuid;
    private string $headline;
    private string $body;
    private int $likes;
    public function __construct(string $uuid, string $headline, string $body, int $likes)
    {
        $this->uuid = $uuid;
        $this->headline = $headline;
        $this->body = $body;
        $this->likes = $likes;
    }
    public function getArticle(): array
    {
        return [
            'uuid' => $this->uuid,
            'headline' => $this->headline,
            'body' => $this->body,
            'likes' => $this->likes
        ];
    }
}