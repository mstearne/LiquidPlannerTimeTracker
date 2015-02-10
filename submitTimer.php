<? include_once("shared.php"); ?>
<?

/*
$_REQUEST['activity_id']="188004";
$_REQUEST['log_comment']="this is the comment";
$_REQUEST['task_id']="20176570";
$_REQUEST['time']="00:45:30";
*/
$_REQUEST['work']=time_to_decimal($_REQUEST['time']);
// $_REQUEST['work_performed_on']="2015-01-28";

$lp->post("/workspaces/{$lp->workspace_id}/tasks/".$_REQUEST['task_id']."/timer/clear","");

$lp->post("/workspaces/{$lp->workspace_id}/tasks/".$_REQUEST['task_id']."/track_time",array("activity_id"=>$_REQUEST['activity_id'],"member_id"=>$member_id,"note"=>$_REQUEST['log_comment'],"work"=>$_REQUEST['work'], "work_performed_on"=>$_REQUEST['work_performed_on']));

print number_format($_REQUEST['work'],4)."h submitted";
?>