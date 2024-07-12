<?php

use Articles\Controller\ArticleController;
use Articles\Controller\CommentController;
use Articles\Controller\ViewController;

return [
    ['GET', '/', [ViewController::class, 'index']],
    ['GET', '/create', [ViewController::class, 'create']],
    ['GET', '/display', [ViewController::class, 'display']],
    ['GET', '/update', [ViewController::class, 'update']],

    ['POST', '/create', [ArticleController::class, 'validateCreate']],
    ['POST', '/articles/{uuid}/delete', [ArticleController::class, 'delete']],
    ['POST', '/articles/{uuid}/update', [ArticleController::class, 'update']],
    ['POST', '/articles/{uuid}/like', [ArticleController::class, 'like']],

    ['POST', '/articles/{uuid}/comments', [CommentController::class, 'create']],
    ['POST', '/comments/{comment_uuid}/delete', [CommentController::class, 'delete']],
    ['POST', '/comments/{comment_uuid}/like', [CommentController::class, 'like']],

];