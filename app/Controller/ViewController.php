<?php

namespace Articles\Controller;

use Articles\Exceptions\InvalidInputException;
use Articles\Response;
use Articles\Database\Sqlite;
use Exception;
use Respect\Validation\Validator as v;

class ViewController
{
    private Sqlite $db;

    public function __construct(Sqlite $db)
    {
        $this->db = $db;
    }

    public function index(): Response
    {
        $articles = $this->db->getArticles();
        return new Response('index', ['articles' => $articles]);
    }

    public function create(string $message = ""): Response
    {
        return new Response('create', ['error' => $message]);
    }

    public function display(): Response
    {
        $uuid = $_GET['uuid'];
        $article = $this->db->getByUuidArticle($uuid);
        $comments = $this->db->getByUuidComment($uuid);

        return new Response('display', ['articles' => $article, 'comments' => $comments]);
    }

    public function update(): Response
    {
        $article = $this->db->getByUuidArticle($_GET['uuid']);
        return new Response('update', ['article' => $article]);
    }
}