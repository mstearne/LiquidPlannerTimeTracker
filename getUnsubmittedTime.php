<? include_once("shared.php"); 

$timers=$lp->get("/workspaces/{$lp->workspace_id}/my_timers");


if(count($timers)>1){
print "<ul>";

    foreach($timers as $i => $timer) {
		///// ------------------------------------ /////
		$cacheTmp=$pageCache=false;
		$cacheSnippetName="timerID-".$timer->item_id;
//		$cacheSnippetName=md5($cacheSnippetName);
		if($pageCache=@file_get_contents("cache/$cacheSnippetName")==false){
			///// -----------Start Code-------------- /////
			$task=$lp->get("/workspaces/{$lp->workspace_id}/tasks/{$timer->item_id}");
			///// -----------End Code-------------- /////
			file_put_contents("cache/$cacheSnippetName", json_encode($task));
		}else{
			/// Show existing cache file  
			$pageCache=file_get_contents("cache/$cacheSnippetName");
			$task=json_decode($pageCache);
		}
		///// ------------------------------------ /////
	        foreach($projects as $x => $pros) {
		        if($pros->id==$task->project_id){
			      print "<li><a href='?task_id={$task->id}' class='black-link'>".$task->name." in ".$pros->name." for ".$pros->client_name."</a> Time: ";
			      if($timer->running==1){
				      $running_timer=round(($timer->running_time + $timer->total_time)*3600000);
//				      $running_timer_label="<strong>logging time for </strong> ".$task->name." in ".$pros->name;
				      print "<span id=realtime-bottom></span> <em style='color:white'>Currently running</em>";
				      $gotRunningTimer=1;
				      $running_timer_id=$task->id;
				      $running_timer_name=$task->name;
				      $running_timer_project_id=$task->project_id;

				      }else{
				      print convert_to_time($timer->total_time).'&nbsp;&nbsp;&nbsp;<span id="submitButton-'.$task->id.'" class="glyphicon glyphicon-remove"></span><script>$( "#submitButton-'.$task->id.'" ).click(function() { clearTimer('.$task->id.') });</script>';
			      }
			      $gotAnyTimer=1;
		        }
	    	}
				print "</li>";
    }
print "</ul>";

}else{
	print "false";
}
?>
