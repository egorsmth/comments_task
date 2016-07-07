<?php
namespace comments;

use comments\view\ViewInterface;
use comments\database\CommentsDbInterface;

class CommentsController
{
    private $db;
    private $view;
    private $commentsTree;

    public function __construct(CommentsDbInterface $db, ViewInterface $view)
    {
        $this->db = $db;
        $this->view = $view;
        $commentsTree = array();
    }

    /**
     * @param void @return void
     */
    public function index()
    {
        $comments = $this->db->getByParent(0);
        foreach ($comments as $comment) {
            $comment = new Comment($comment);
            $this->commentsTree[] = $comment;
            if($comment->hasChild()) {
                $comment->addChildren($this->getChildren($comment->getId()));
            }
        }
        $this->view->render('comments', ['commentsTree' => $this->commentsTree ? $this->commentsTree : []]);
    }

    /**
     * @param int $parentId @return array
     */
    public function getChildren($parentId)
    {
        $children = array();
        $comments = $this->db->getByParent($parentId);
        foreach ($comments as $comment) {
            $comment = new Comment($comment);
            if($comment->hasChild()) {
                $comment->addChildren($this->getChildren($comment->getId()));
            }
            $children[] = $comment;
        }
        return $children;
    }
}
