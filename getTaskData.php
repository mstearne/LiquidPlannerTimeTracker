<? include_once("shared.php"); ?>
<?


		///// ------------------------------------ /////
		$cacheTmp=$pageCache=false;
		$cacheSnippetName="taskData-".$_REQUEST['task_id'];
//		$cacheSnippetName=md5($cacheSnippetName);
		if($pageCache=@file_get_contents("cache/$cacheSnippetName")==false){
			///// -----------Start Code-------------- /////
			$task=$lp->get("/workspaces/{$lp->workspace_id}/tasks/".$_REQUEST['task_id']."?include=comments,documents,timer&order=updated_at");
			///// -----------End Code-------------- /////
// disabled we can't continue timers like this			file_put_contents("cache/$cacheSnippetName", json_encode($task));
		}else{
			/// Show existing cache file  
			$pageCache=file_get_contents("cache/$cacheSnippetName");
			$task=json_decode($pageCache);
		}
		///// ------------------------------------ /////

      

$taskJSON=json_encode($task);

print_r($taskJSON);
exit();
$comments_for_task=$task->comments;
$documents_for_task=$task->documents;
$timer_for_task=$task->timer;


print_r($comments_for_task);
print "---------------------------------------------\n";
print_r($documents_for_task);
print "---------------------------------------------\n";
print_r($timer_for_task);
print "---------------------------------------------\n";
exit();

if($comments_for_task->type!="Error"){
    foreach($comments_for_task->comments as $i => $comment) {
		?>
		<div><span class="small-date"><?=date("Y-m-d h:i A",strtotime($comment->created_at)) ?></span> <?=str_replace("/space/", "https://app.liquidplanner.com/space/",$comment->comment)  ?></div>
		<?
    }
}
?>
