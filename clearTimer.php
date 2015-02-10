<? include_once("shared.php"); 

if($_REQUEST['task_id']){
$lp->post("/workspaces/{$lp->workspace_id}/tasks/".$_REQUEST['task_id']."/timer/clear","");
print "Cleared";
}
?>