<?php

namespace Articles\Database;

use Articles\Models\Comment;
use Articles\Models\Article;
use Medoo\Medoo;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

class Sqlite
{
    private Medoo $db;
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->db = new Medoo([
            'database_type' => 'sqlite',
            'database_name' => 'storage/articles.sqlite',
        ]);

        $this->create();
        $this->logger = $logger;
    }

    private function create(): void
    {
        $this->db->exec("CREATE TABLE IF NOT EXISTS articles (
            uuid TEXT,
            headline TEXT,
            body TEXT,
            likes INT
        )");
        $this->db->exec("CREATE TABLE IF NOT EXISTS comments (
            comments TEXT,
            author TEXT,
            uuid TEXT,
            likes INT,
            comment_uuid TEXT
        )");
    }

    public function createArticle(string $headline, string $body, int $likes = 0): void
    {
        $uuid = Uuid::uuid4()->toString();
        $newArticle = new Article($uuid, $headline, $body, $likes);
        $this->db->insert('articles', $newArticle->getArticle());
        $this->logger->info("[CREATED ARTICLE]" . " [" . $uuid . "] [" . $headline . "]");
    }

    public function createComment(string $comment, string $author, string $uuid, int $likes = 0): void
    {
        $comment_uuid = Uuid::uuid4()->toString();
        $newComment = new Comment($comment, $author, $uuid, $likes, $comment_uuid);
        $this->db->insert('comments', $newComment->getComment());
        $this->logger->info("[CREATED COMMENT]" . " [" . $comment_uuid . "] [" . $author . "] [" . $comment . "]");
    }

    public function updateArticle(string $uuid, string $headline, string $body): void
    {
        $this->db->update("articles", ["headline" => $headline, "body" => $body], ['uuid' => $uuid]);
        $this->logger->info("[UPDATED ARTICLE]" . " [" . $uuid . "] [" . $headline . "]");
    }

    public function deleteArticle(string $uuid): void
    {
        $this->db->delete('articles', ['uuid' => $uuid]);
        $this->db->delete('comments', ['uuid' => $uuid]);
        $this->logger->info("[DELETED ARTICLE]" . " [" . $uuid . "]");
    }

    public function deleteComment(string $comment_uuid): void
    {
        $this->db->delete('comments', ['comment_uuid' => $comment_uuid]);
        $this->logger->info("[DELETED COMMENT]" . " [" . $comment_uuid . "]");
    }

    public function getByUuidArticle(string $uuid): array
    {
        return $this->db->get("articles", ["uuid", "headline", "body"], ['uuid' => $uuid]);
    }

    public function getByUuidComment(string $uuid): array
    {
        $comments = $this->db->select("comments", ["comment", "author", "likes", "comment_uuid"], ['uuid' => $uuid]);
        if ($comments == null) {
            $comments = [];
        }
        return $comments;
    }

    public function likeArticle(string $uuid): void
    {
        $article = $this->db->get("articles", ["likes"], ['uuid' => $uuid]);
        $likes = $article['likes'];
        $this->db->update("articles", ["likes" => $likes + 1], ['uuid' => $uuid]);
    }

    public function likeComment(string $comment_uuid): void
    {
        $comments = $this->db->get("comments", ["likes"], ['comment_uuid' => $comment_uuid]);
        $likes = $comments['likes'];
        $this->db->update("comments", ["likes" => $likes + 1], ['comment_uuid' => $comment_uuid]);
    }

    public function getArticles(): array
    {
        return $this->db->select("articles", [
            "uuid",
            "headline",
            "body",
            "likes"
        ]);
    }
}