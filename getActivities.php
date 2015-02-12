<? include_once("shared.php"); ?>
<? scriptTimer("Start getActivites for submit"); ?>
<link rel="stylesheet" href="js/chosen_v1/chosen.css">


</head>
<body>
<div id="pleasewait" align="center">
	<em>Loading...</em>
</div>
<?
///// ------------------------------------ /////
$cacheTmp=$pageCache=false;
$cacheSnippetName="expectedActivity".$email.$_REQUEST['task_id'];
$cacheSnippetName=md5($cacheSnippetName);
if($pageCache=@file_get_contents("cache/$cacheSnippetName")==false){
ob_start();
///// -----------Start Code-------------- /////
	
$task=$lp->get("/workspaces/{$lp->workspace_id}/tasks/".$_REQUEST['task_id']."?include=comments,documents,timer&order=updated_at");


///// -----------End Code-------------- /////
$cacheTmp=ob_get_contents();
ob_end_clean();

file_put_contents("cache/$cacheSnippetName", print_r($task,true));
//print $cacheTmp;

}else{
print "<!-- got cache -->";	
/// Show existing cache file  
$pageCache=file_get_contents("cache/$cacheSnippetName");
$task=print_r_reverse($pageCache);
	
}
///// ------------------------------------ /////
//print "out";
//print_r($task);
//print "out";

for($i=0;$i<count($task->assignments);$i++){
	if($task->assignments[$i]->activity_id){
		$expectedActivity=$task->assignments[$i]->activity_id;
	}
}



?>

<script>

$(document).ready(function () {
$(".chosen-select-activities").chosen({width:"85%",no_results_text: "Oops, nothing found!"});
$("#time_amt").focus();
$("#pleasewait").hide();

});	
	
	</script>
<?
	
	switch(strlen($_REQUEST['time'])){
		case 2:
		$_REQUEST['time']="00:00:".$_REQUEST['time'];
		break;
		case 5:
		$_REQUEST['time']="00:".$_REQUEST['time'];
		break;
		default:
		$_REQUEST['time']=$_REQUEST['time'];
		break;
		
		
		
	}
?>
    <form method="POST" action="contact-form-submission.php" class="form-horizontal" id="contact-form">
        <div class="control-group">
            <label class="control-label" for="time_amt">Amount of time to log</label>
            <div class="controls">
                <input type="text" name="time_amt" id="time_amt" placeholder="Time to log" value="<?=$_REQUEST['time']?>" class="inputs" style="font-size: 33px"> HH:MM:SS (Edit time if necessary)
            </div>
        </div>
        <br>
        <div class="control-group">
            <label class="control-label" for="subject">Your general activity on this task</label>
            <div class="controls">
<select class="chosen-select-activities" id="activity_id_submit" name="activity_id_submit">
<option></option>
<?
///// ------------------------------------ /////
$cacheTmp=$pageCache=false;
$cacheSnippetName="activitiesList".$email;
$cacheSnippetName=md5($cacheSnippetName);
if($pageCache=file_get_contents("cache/$cacheSnippetName")==false){
ob_start();
///// -----------Start Code-------------- /////


    $activities=$lp->get("/workspaces/{$lp->workspace_id}/activities");

    foreach($activities as $i => $activity) {
	    	print "<option value='".$activity->id."'";
			if($activity->id==$expectedActivity){
		    	print " selected";
		    }
		    print ">$activity->name</option>\n";
	    }



///// -----------End Code-------------- /////
$cacheTmp=ob_get_contents();
ob_end_clean();

file_put_contents("cache/$cacheSnippetName", $cacheTmp);
print $cacheTmp;

}else{
	$pageCache=file_get_contents("cache/$cacheSnippetName");
print "<!-- got cache -->";	
/// Show existing cache file  
print $pageCache;
	
}
///// ------------------------------------ /////




?>
</select>


            </div>
        </div>
        <br>
        <div class="control-group">
            <label class="control-label" for="message">Timesheet note (usually not used)</label>
            <div class="controls">
	            <textarea name="log_comment" id="log_comment" class="inputs" style="font-size: 22px; width:100%"></textarea>
                
            </div>
        </div>

<!--
        <div class="control-group">
            <div class="controls">Mark this task as <strong>done</strong> <input type="checkbox" value="1" name="mark_done" id="mark_done"> 
                
            </div>
        </div>
-->



<!--
        <div class="form-actions">
             <button type="submit" class="btn btn-primary">Submit Time</button> 
        </div>
-->
    </form>


  <script src="js/chosen_v1/chosen.jquery.js" type="text/javascript"></script>
<? scriptTimer("Finish getActivites for submit"); ?>

