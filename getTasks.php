<? include_once("shared.php"); ?>
<option></option>
<?
/*
    echo "These are the $count projects in your '$ws->name' workspace '$ws->id' ".PHP_EOL;
    print "Projects owned by $account->user_name ($account->id)";
*/

      $tasks_for_project=$lp->get("/workspaces/{$lp->workspace_id}/tasks?filter[]=project_id=".$_REQUEST['project_id']);

    foreach($tasks_for_project as $i => $task) {
//		    array_push($allTasks, $task->name);
			if($task->is_done!=1){
		    	print "<option value='".$task->id."'>$task->name</option>";
			}
	    }

//print_r($tasks_for_project);
//print json_encode($allTasks);
?>
