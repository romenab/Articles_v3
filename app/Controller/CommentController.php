<?php

namespace Articles\Controller;

use Articles\RedirectResponse;
use Articles\Database\Sqlite;


class CommentController
{
    private Sqlite $db;

    public function __construct(Sqlite $db)
    {
        $this->db = $db;
    }
    public function create(): RedirectResponse
    {
        $this->db->createComment($_POST['comment'], $_POST['author'], $_POST['uuid']);
        return new RedirectResponse('/display?uuid=' . $_POST['uuid']);
    }
    public function like(): RedirectResponse
    {
        $this->db->likeComment($_POST['comment_uuid']);
        return new RedirectResponse('/display?uuid=' . $_POST['uuid']);
    }
    public function delete(): RedirectResponse
    {
        $this->db->deleteComment($_POST['comment_uuid']);
        return new RedirectResponse('/display?uuid=' . $_POST['uuid']);
    }
}