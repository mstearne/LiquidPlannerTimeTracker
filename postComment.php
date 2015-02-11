<? include_once("shared.php"); ?>
<?


$lp->post("/workspaces/{$lp->workspace_id}/tasks/".$_REQUEST['task_id']."/comments",array("comment"=>array("comment"=>$_REQUEST['comment'])));
// we need to invalidate the cache because we posted a comment.
unlink("cache/taskData-".$_REQUEST['task_id']);
?>