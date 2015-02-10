<? include_once("shared.php"); ?>
<?


      $task=$lp->get("/workspaces/{$lp->workspace_id}/tasks/".$_REQUEST['task_id']."?include=comments,documents,timer&order=updated_at");

$taskJSON=json_encode($task);

print_r($taskJSON);
//print_r($task);
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
