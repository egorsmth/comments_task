<?php
namespace comments;

interface CommentsDbInterface
{
    public function get($id);
    public function getByParent($parentId);
    public function delete($id);
}
?>
