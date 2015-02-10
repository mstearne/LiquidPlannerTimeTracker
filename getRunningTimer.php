<? include_once("shared.php"); ?>
<?

$timers=$lp->get("/workspaces/{$lp->workspace_id}/my_timers");

//print_r($timers);



for($i=0;$i<count($timers);$i++){
	if($timers[$i]->running==1){
		/// if we found a running timer return the task id
		print $timers[$i]->running_time+$timers[$i]->total_time;
		print time_to_decimal($timers[$i]->running_time+$timers[$i]->total_time);
//		print $timers[$i]->item_id;
		exit();
	}
}
?>