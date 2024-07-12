<?php

namespace Articles\Controller;

use Articles\Exceptions\InvalidInputException;
use Articles\RedirectResponse;
use Articles\Database\Sqlite;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;

class ArticleController
{
    private Sqlite $db;

    public function __construct(Sqlite $db)
    {
        $this->db = $db;
    }

    public function validateCreate()
    {
        $validation = v::key('headline', v::notEmpty()->length(1, 90))
            ->key('body', v::notEmpty()->length(1, 2000));
        try {
            $validation->assert($_POST);
        } catch (ValidationException $e) {
            $message = new InvalidInputException();
            $view = new ViewController($this->db);
            return $view->create($message->getMessages());
        }
        return $this->create();
    }

    public function create(): RedirectResponse
    {
        $this->db->createArticle($_POST['headline'], $_POST['body']);
        return new RedirectResponse('/');
    }

    public function update(): RedirectResponse
    {
        $this->db->updateArticle($_POST['uuid'], $_POST['headline'], $_POST['body']);
        return new RedirectResponse('/');
    }

    public function like(): RedirectResponse
    {
        $this->db->likeArticle($_POST['uuid']);
        return new RedirectResponse('/');
    }

    public function delete(): RedirectResponse
    {
        $this->db->deleteArticle($_POST['uuid']);
        return new RedirectResponse('/');
    }
}