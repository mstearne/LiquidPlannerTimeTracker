<? include_once("shared.php"); ?>
<?
	
	error_reporting(E_ERROR | E_PARSE);

/*
    echo "These are the $count projects in your '$ws->name' workspace '$ws->id' ".PHP_EOL;
    print "Projects owned by $account->user_name ($account->id)";
*/
      $documents_for_project=$lp->get("/workspaces/{$lp->workspace_id}/tasks/".$_REQUEST['task_id']."?include=documents&order=updated_at");

//print_r($documents_for_project);

include('FileIcon.inc.php');


if($documents_for_project->documents){
?>
<h3 class="lighter-text">Task Attachments</h3>
<?
	}?>
<ul>
<?	

    foreach($documents_for_project->documents as $i => $document) {



?>
<li><a href="https://app.liquidplanner.com/space/<?=$lp->workspace_id?>/item/<?=$_REQUEST['task_id']?>/documents/<?=$document->id?>/download" target="_blank"><?


//$doc=$lp->get("/workspaces/{$lp->workspace_id}/tasks/{$_REQUEST['task_id']}/documents/{$document->id}/thumbnail");

print "<img =\"https://app.liquidplanner.com/api/workspaces/{$lp->workspace_id}/tasks/{$_REQUEST['task_id']}/documents/{$document->id}/thumbnail\">";
/*
print "22";
print_r($doc);
print "22";
*/
		
/*
$thisFile=file_get_contents("https://app.liquidplanner.com/space/{$lp->workspace_id}/item/{$_REQUEST['task_id']}/documents/{$document->id}/download");
print $thisFile;
file_put_contents("tmp/".$document->file_name, $thisFile);
*/
// get the file
$file = new FileIcon($document->file_name);
// set the icon url
$file -> setIconUrl('icons/');

// print the icon plus some data
echo $file -> displayIcon() . '  ' .  $file -> getName();
?></a></li>

<?
	    }
?>
</ul>