<? include_once("shared.php"); 

$timers=$lp->get("/workspaces/{$lp->workspace_id}/my_timers");

if(count($timers)>1){
print "<ul>";

    foreach($timers as $i => $timer) {
	      $task=$lp->get("/workspaces/{$lp->workspace_id}/tasks/{$timer->item_id}");
	        foreach($projects as $x => $pros) {
		        if($pros->id==$task->project_id){
			      print "<li>".$task->name." in ".$pros->name." for ".$pros->client_name." Time: ";
			      if($timer->running==1){
				      $running_timer=round(($timer->running_time + $timer->total_time)*3600000);
//				      $running_timer_label="<strong>logging time for </strong> ".$task->name." in ".$pros->name;
				      print "<span id=realtime-bottom></span> <em>Currently running</em>";
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
    }
print "</ul>";

}else{
	print "false";
}
?>
