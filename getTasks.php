<? include_once("shared.php"); ?>
<option></option>
<?
/*
    echo "These are the $count projects in your '$ws->name' workspace '$ws->id' ".PHP_EOL;
    print "Projects owned by $account->user_name ($account->id)";
*/


		///// ------------------------------------ /////
		$cacheTmp=$pageCache=false;
		$cacheSnippetName="projectIDTasks-".$_REQUEST['project_id'];
//		$cacheSnippetName=md5($cacheSnippetName);
		if($pageCache=@file_get_contents("cache/$cacheSnippetName")==false){
			///// -----------Start Code-------------- /////
			$tasks_for_project=$lp->get("/workspaces/{$lp->workspace_id}/tasks?filter[]=project_id=".$_REQUEST['project_id']);
//			$task=$lp->get("/workspaces/{$lp->workspace_id}/tasks/{$timer->item_id}");
			///// -----------End Code-------------- /////
			file_put_contents("cache/$cacheSnippetName", json_encode($tasks_for_project));
		}else{
			/// Show existing cache file  
			$pageCache=file_get_contents("cache/$cacheSnippetName");
			$tasks_for_project=json_decode($pageCache);
		}
		///// ------------------------------------ /////



    foreach($tasks_for_project as $i => $task) {
//		    array_push($allTasks, $task->name);
			if($task->is_done!=1){
		    	print "<option value='".$task->id."'>$task->name</option>";
			}
	    }

//print_r($tasks_for_project);
//print json_encode($allTasks);
?>
