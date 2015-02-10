<? include_once("shared.php"); ?>
<?

include('FileIcon.inc.php');




      $task=$lp->get("/workspaces/{$lp->workspace_id}/tasks/".$_REQUEST['task_id']."?include=comments,documents,timer&order=updated_at");

print_r($task);
$comments_for_project=$task->comments;
$documents_for_project=$task->documents;


print_r($comments_for_project);
print_r($documents_for_project);
exit();

if($comments_for_project->type!="Error"){
    foreach($comments_for_project->comments as $i => $comment) {
		?>
		<div><span class="small-date"><?=date("Y-m-d h:i A",strtotime($comment->created_at)) ?></span> <?=str_replace("/space/", "https://app.liquidplanner.com/space/",$comment->comment)  ?></div>
		<?
    }
}
?>
