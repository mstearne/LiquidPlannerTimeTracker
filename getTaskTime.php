<? include_once("shared.php"); 

    $tasks_for_project=$lp->get("/workspaces/{$lp->workspace_id}/tasks/".$_REQUEST['task_id']."/timer");

if($tasks_for_project->running){
	print number_format(convert_decimal_hours_to_milliseconds($tasks_for_project->running_time + $tasks_for_project->total_time),0,'.','');
}else{
	print number_format(convert_decimal_hours_to_milliseconds($tasks_for_project->total_time),0,'.','');
}
?>