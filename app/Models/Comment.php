<?php

namespace Articles\Models;
class Comment
{

    private string $comment;
    private string $author;
    private string $uuid;
    private int $likes;
    private string $comment_uuid;

    public function __construct(string $comment, string $author, string $uuid, int $likes, string $comment_uuid)
    {
        $this->comment = $comment;
        $this->author = $author;
        $this->uuid = $uuid;
        $this->likes = $likes;
        $this->comment_uuid = $comment_uuid;
    }

    public function getComment(): array
    {
        return [
            'comment' => $this->comment,
            'author' => $this->author,
            'uuid' => $this->uuid,
            'likes' => $this->likes,
            'comment_uuid' => $this->comment_uuid
        ];
    }
}