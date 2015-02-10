<? include_once("shared.php"); ?>
<?

$lp->post("/workspaces/{$lp->workspace_id}/tasks/".$_REQUEST['task_id']."/timer/start","");

print "Timer Started";

?>
