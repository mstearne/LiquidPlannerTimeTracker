<? include_once("shared.php"); ?>

<link rel="stylesheet" href="js/chosen_v1/chosen.css">

<!-- <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script> -->


</head>
<body>

<?
$task=$lp->get("/workspaces/{$lp->workspace_id}/tasks/".$_REQUEST['task_id']."?include=comments,documents,timer&order=updated_at");

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


});	
	
	</script>


    <form method="POST" action="contact-form-submission.php" class="form-horizontal" id="contact-form">
        <div class="control-group">
            <label class="control-label" for="time_amt">Amount of time to log</label>
            <div class="controls">
                <input type="text" name="time_amt" id="time_amt" placeholder="Time to log" value="<?=$_REQUEST['time']?>" class="inputs" style="font-size: 33px"> HH:MM:SS
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="subject">Your general activity on this task</label>
            <div class="controls">
<select class="chosen-select-activities" id="activity_id_submit" name="activity_id_submit">
<option></option>
<?

    $activities=$lp->get("/workspaces/{$lp->workspace_id}/activities");

    foreach($activities as $i => $activity) {
	    	print "<option value='".$activity->id."'";
			if($activity->id==$expectedActivity){
		    	print " selected";
		    }
		    print ">$activity->name</option>\n";
	    }
?>
</select>


            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="message">Timesheet note (usually not used)</label>
            <div class="controls">
	            <textarea name="log_comment" id="log_comment" class="inputs" style="font-size: 22px; width:100%"></textarea>
                
            </div>
        </div>
<!--
        <div class="form-actions">
             <button type="submit" class="btn btn-primary">Submit Time</button> 
        </div>
-->
    </form>


  <script src="js/chosen_v1/chosen.jquery.js" type="text/javascript"></script>

