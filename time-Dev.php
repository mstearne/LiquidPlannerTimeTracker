<? include_once('shared.php'); ?>
<? include_once('header.php'); ?>

<section>
    <div id="runnerDiv">
	    <span id="runner" class="runnerStyle"></span>
    </div>
    <div id="buttonsDiv">
	    <span id="startButton" class="glyphicon glyphicon-play glyphiconStyle" style="display: none"><span class="glyphiconStyleText"><br>&nbsp;Start</span></span>
	    <span id="stopButton" class="glyphicon glyphicon-stop glyphiconStyle" style="display: none"><span class="glyphiconStyleText"><br>&nbsp;&nbsp;Stop</span></span> 
	    <span id="submitButton" class="glyphicon glyphicon-send glyphiconStyle" style="display: none"><span class="glyphiconStyleText"><br>Submit Time</span></span>
		<span id="resetButton" class="glyphicon glyphicon-remove glyphiconStyle glyphiconRemoveStyle" style="display:none"></span>
    </div>
</section>

<div id="effort-label" class="lighter-text">
</div>
	
  
<div name="project_id">
<?
	$foundProjects=array();
    foreach($projects as $i => $p) {
				if($p->is_done!=1){
					if(in_array($p->id, $LPIncludedProjects)){
						array_push($foundProjects, "{$p->id}|{$p->client_name}|{$p->name}");
					}
					for($i=0;$i<count($p->assignments);$i++){
						if($p->assignments[$i]->person_id==in_array($p->assignments[$i]->person_id, $_SESSION['lpteam'])){
							array_push($foundProjects, "{$p->id}|{$p->client_name}|{$p->name}");
					}
				}
    	}
    }
?>
<select id="project_id" class="chosen-select">
<option></option>
<?	
$foundProjectsClean=array_unique($foundProjects);	
$foundProjectsClean=array_values($foundProjectsClean);
	
for($i=0;$i<count($foundProjectsClean);$i++){
	$pro=explode("|", $foundProjectsClean[$i]);
	
	// if the client name in is in the project then we don't need to display that client name, just display the client name
	$strippedClientName=str_replace(" ", "", $pro[1]);
	$strippedProjectName=str_replace(" ", "", $pro[2]);
//	print "<!-- $strippedClientName $strippedProjectName -->";
	if(strstr($strippedProjectName, $strippedClientName)){
		print "<option value='{$pro[0]}'>{$pro[2]}</option>";
	}else{
		print "<option value='{$pro[0]}'>{$pro[1]} : {$pro[2]}</option>";
	}
}
?>
</select>
</div>

<br>
<div id="task_select" style="display: none">
	<div name="task_id">
	<select class="chosen-select-task" id="task_id">
	</select> <span id="task_link"></span>
	</div>
</div>

<div id="task_comments_post" style="display: none">

<div class="lighter-text">
<br>

</div>

<div id="task_comments" style="background-color: #fff; padding:5px; padding-right: 10px;display: none; border-radius: 5px;" class="col-lg-9">
	
</div>

<div style="clear:both">
<br>&nbsp;

<!-- Textarea -->
<div class="form-group">
  <div class="col-lg-6">                     
    <textarea class="inputs" id="task_comment" name="task_comment" style="width:100%;border-radius: 5px; margin-left: 0px">Add comment</textarea>
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <div class="col-sm-1">
    <button id="task_comment_button" name="task_comment_button" class="btn btn-sm btn-primary">Post Comment</button>
  </div>
</div>
</div>

<script>
var currentTask;
var currentProject;
var timerIsPaused;
</script>


<div id="task_documents" style="display: none; clear:both">
	
</div>
<div id="taskemailattachment" style="display: none"><span class="glyphicon glyphicon-email"></span> <span id="taskemailattachmentlabel"></span></div>

</div>


<?

$timers=$lp->get("/workspaces/{$lp->workspace_id}/my_timers");

    foreach($timers as $i => $timer) {
	      $task=$lp->get("/workspaces/{$lp->workspace_id}/tasks/{$timer->item_id}");
	        foreach($projects as $x => $pros) {
		        if($pros->id==$task->project_id){
			      if($timer->running==1){
				      $running_timer=round(($timer->running_time + $timer->total_time)*3600000);
				      $gotRunningTimer=1;
				      $running_timer_id=$task->id;
				      $running_timer_name=$task->name;
				      $running_timer_project_id=$task->project_id;

				      }else{
			      }
					$gotAnyTimer=1;
					$any_timer_id=$task->id;
					$any_timer_name=$task->name;
					$any_timer_project_id=$task->project_id;

		        }
	    	}
    }


?>

<div id="unsubmitted-time" style="display: none; background-color: #ffaaaa; padding: 20px; margin-top: 10px; border-radius: 5px; width: 90%"><strong>Hey. You have unsubmitted time. You should <a href="https://app.liquidplanner.com/space/<?=$lp->workspace_id?>/timesheet" target=_blank>view your timesheet to submit all your time.</a></strong> <br>
<div id="unsubmitted-time-body">

</div>
<br>
</div>

<?

	if(!$gotRunningTimer){
    	$running_timer=0;
    }
?>

<script>

var currentTask;
var currentTaskStartTime;
var currentProject;
var startupTask;
var globalStartTime;

function testGlobal(){
	alert("global");
	$("#runner").runner('start');

}

$('#runner').runner({
autostart: false,
countdown: false,
startAt: 0,
milliseconds: false		
});

<? if($_REQUEST['task_id']){ ?>

		startupTask='<?=$_REQUEST['task_id']?>';
		<?
		$lp->post("/workspaces/{$lp->workspace_id}/tasks/".$_REQUEST['task_id']."/timer/start","");
?>
<? }else{ ?>
	<? if($gotAnyTimer){ ?> 
	<?	if($running_timer_id){ ?>
		startupTask=<?=$running_timer_id?>;
	<?	}else{ 
			if($any_timer_id){
				?>
				startupTask=<?=$any_timer_id?>;
	<?			
			}
	?>
	<? } ?>
	<? } ?>
<? } ?>
	
	function change_project(){
		
		$(".chosen-select").chosen=undefined;
		$(".chosen-select-task").chosen=undefined;
		 
		$('#runner').fadeOut(250);
		$("#stopButton").fadeOut(250);
		$("#startButton").fadeOut(250);
		$("#submitButton").fadeOut(250);
		$("#resetButton").fadeOut(250);

		$("#task_comments_post").fadeOut(250);
		$("#task_comments").fadeOut(250);
		$("#taskemailattachment").fadeOut(250);
		$("#taskemailattachmentlabel").fadeOut(250);
		$("#effort-label").html(" ");


		
		currentTask=false;
//		$("#task_id").val()=false;
		
		$.ajax
		({
		type: "POST",
		url: "getTasks.php",
		data: "project_id="+$("#project_id").val(),
		cache: false,
		success: function(html)
		{
			$("#task_select").show();
			$("#activity_select").show();
			$("#task_id").html(html);
			
			if(startupTask){
				$("#task_id").val(startupTask).trigger('change');
				startupTask=false;
			}
			
				$(".chosen-select").chosen({width:"90%",no_results_text: "Oops, nothing found!",placeholder_text_single:"Select the program or project you're working on"});
				$(".chosen-select-task").chosen({width:"90%",no_results_text: "Oops, nothing found!",placeholder_text_single:"Select the task you're working on"});
				$('.chosen-select-task').trigger('chosen:updated');
		} 
		});
	}



	function change_task(){

		var commentsOutput;
		var documentsOutput;
		
		// if we have another timer running we have to stop that first after we switched to the new task
//		alert("currentTask="+currentTask+" selected="+$("#task_id").val())
		if(currentTask){
			$("#runner").runner('stop');
			$.ajax
			({
			type: "POST",
			url: "stopTimer.php",
			data: "task_id="+currentTask,
			cache: false,
			success: function(html)
			{
				$("#timer_feedback").html(html);
			} 
			});
		}

				$('#runner').fadeIn(250);


		$("#task_link").html('<a href="https://app.liquidplanner.com/space/<?=$lp->workspace_id?>/projects/show/'+$("#task_id").val()+'" target=_blank><span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a> <span class="hidden-xs"><a href="https://app.liquidplanner.com/space/<?=$lp->workspace_id?>/projects/show/'+$("#task_id").val()+'" target=_blank>View in LP</a></span>');

		$("#unsubmitted-time").show();
		$("#task_comments_post").hide();
		$("#task_comments").hide();
		$("#runner").fadeTo( 500, 0.01 );

		/// We should also update the running timers area during this update
		$.ajax
		({
			type: "POST",
			url: "getTaskData.php",
			data: "task_id="+$("#task_id").val(),
			cache: false,
			dataType: "json",
			success: function(taskJson){


				var tmpTime=0;
				if(!taskJson.timer){
					var taskTimeMS=0;
				}else{
					if(!taskJson.timer.running){
						var taskTimeMS=taskJson.timer.total_time*3600000;
					}else{
						var taskTimeMS=(taskJson.timer.running_time+taskJson.timer.total_time)*3600000;
					}
				}
				
				currentTaskStartTime=parseInt(taskTimeMS);
				
				console.log(JSON.stringify(taskJson));

				console.log(parseInt(taskTimeMS)+" "+parseInt(taskTimeMS/1000/60));
				/// we need to set the start time for the 
				globalStartTime=parseInt(taskTimeMS);
				$("#runner").runner('setNewStartAt',globalStartTime);

				$("#runner").runner('reset');

				if(!taskJson.timer){
					$("#startButton").fadeIn(250);
					$("#stopButton").hide();
				}else{
					if(taskJson.timer.running){
						/// start the new timer in LP with selected task_id
						$.ajax
						({
						type: "POST",
						url: "startTimer.php",
						data: "task_id="+$("#task_id").val(),
						cache: false,
						success: function(html)
						{
							$("#timer_feedback").html(html);
						} 
						});
						$("#runner").runner('start');
						$("#startButton").hide();
						$("#stopButton").fadeIn(250);
					}else{
						$("#runner").runner('stop');
						$("#startButton").fadeIn(250);
						$("#stopButton").hide();
					}
				}

				$("#submitButton").fadeIn(250);
				$("#resetButton").fadeIn(250);
				$('#runner').fadeTo(250,1);
				$('#runner').fadeIn(250);

				effortOutput="";
				if(taskJson.max_effort>0){
					effortOutput="<em>Max</em>: "+taskJson.max_effort+" hours <em>Remaining </em>: "+taskJson.low_effort_remaining+"-"+taskJson.high_effort_remaining+" hours";
				}
				
				hoursLogged=0;
			    for (i=0;i<taskJson.assignments.length;i++) {
				    hoursLogged=hoursLogged+taskJson.assignments[i].hours_logged;
			    }
			    
			    effortOutput="<em>Logged</em>: "+hoursLogged.toFixed(2)+" hours";
				
				
			    commentsOutput="";
			    for (i=0;i<taskJson.comments.length;i++) {	    
			        commentsOutput+='<div><span class="small-date">' + jQuery.format.prettyDate(taskJson.comments[i].updated_at) + "</span> " + taskJson.comments[i].comment + "</div>";
			    }

			    documentsOutput="";
			    for (i=0;i<taskJson.documents.length;i++) {
				    if(i==0){
				        documentsOutput+='<h3 class="lighter-text">Task Attachments</h3>';
				    }

			        documentsOutput+='<p><a href="https://app.liquidplanner.com/space/<?=$lp->workspace_id?>/item/'+$("#task_id").val()+'/documents/'+taskJson.documents[i].id+'/download" target="_blank">'+openFile(taskJson.documents[i].file_name,$("#task_id").val(),taskJson.documents[i].id)+' '+taskJson.documents[i].file_name+'</a></p>';
			        
			    }

				//alert("json "+taskTimeMS);
				$("#task_comments_post").show();
				$("#task_comments").html(commentsOutput);
				$("#task_comments").fadeIn(250);
				$("#effort-label").html(effortOutput);
				$("#effort-label").fadeIn(250);
				
				//$("#realtime").text("<?=$running_timer?>");
								
				$("#taskemailattachment").show();
				$("#taskemailattachmentlabel").html('<a href="mailto:'+$("#task_id").val()+'-<?=$pathLPAccountEmailID?>@in.liquidplanner.com"><span class="glyphicon glyphicon-paperclip"></span> Send attachment to task</a><br>&nbsp;');
				$("#taskemailattachmentlabel").show();

				$("#task_documents").html(documentsOutput);
				$("#task_documents").fadeIn(50);


			}
		});



		$.ajax
		({
		type: "POST",
		url: "getUnsubmittedTime.php",
		cache: false,
		dataType: "text",
		success: function(html)
		{
			//								alert(html)
			if(html!="false"){
				$("#unsubmitted-time").hide();
				$("#unsubmitted-time-body").show();
				$("#unsubmitted-time-body").html(html);
				$("#unsubmitted-time").fadeIn(250);
			}else{
				$("#unsubmitted-time").hide();
				$("#unsubmitted-time-body").html("");
			}
		} 
		});

		currentTask=$("#task_id").val();
	
	}	
	
$(document).ready (function () {
$('#ttpopup').popupWindow({ height:(screen.height-50), width:360, top:0, left:(screen.width-360), scrollbars:1,resizable:1, menubar:0, location:0 }); 


	
	<? if($_REQUEST['message']){ ?>
	/// display some feedback message after the reload
	$('#timer_feedback').html("<?=$_REQUEST['message']?>");
	
	$('#timer_feedback').show();
	  $("#timer_feedback").fadeOut(3000);
	
	setTimeout(function(){ $('#timer_feedback').html("")}, 4000);
	
	<? } ?>
	
	
	
	$("#project_id").change(function(){
		change_project();
	});
	$("#task_id").change(function(){
		change_task();
	});


	<? if($gotAnyTimer){ ?>
	

		<? if($gotRunningTimer){ ?>
			$("#project_id").val('<?=$running_timer_project_id?>').trigger('change');
		<? }else{ 
			if($any_timer_project_id){ ?>
				$("#project_id").val('<?=$any_timer_project_id?>').trigger('change');
			<? } ?>
		<? } ?>
		
	
	<? }else{ ?>
				$(".chosen-select").chosen({width:"90%",no_results_text: "Oops, nothing found!",placeholder_text_single:"Select the program or project you're working on"});
	<? } ?>

	$(document).ajaxSend(function(event, request, settings) {
	  $('#loading-indicator').show();
	});
	
	$(document).ajaxComplete(function(event, request, settings) {
	  $('#loading-indicator').hide();
	  $('#timer_feedback').show();
	  $("#timer_feedback").fadeOut(3000);
	  
	});

});


	$('#startButton').click(function() {
		$('#runner').runner('start');
		console.log($('#runner').runner('info'));
		$('#stopButton').show();
		$('#startButton').hide();
		$('#submitButton').show();

		$.ajax
		({
			type: "POST",
			url: "startTimer.php",
			data: "task_id="+$("#task_id").val(),
			cache: false,
			success: function(html)
			{
				$("#timer_feedback").html(html);
			} 
		});

		$.ajax
		({
		type: "POST",
		url: "getUnsubmittedTime.php",
		cache: false,
		dataType: "text",
		success: function(html)
		{
			//								alert(html)
			if(html!="false"){
				$("#unsubmitted-time").hide();
				$("#unsubmitted-time-body").show();
				$("#unsubmitted-time-body").html(html);
				$("#unsubmitted-time").fadeIn(250);
			}else{
				$("#unsubmitted-time").hide();
				$("#unsubmitted-time-body").html("");
			}
		} 
		});

	});

	$('#stopButton').click(function() {
		$('#runner').runner('stop');
		console.log($('#runner').runner('info'));
		$('#stopButton').hide();
		$('#startButton').show();
		$('#submitButton').show();

		$.ajax
		({
		type: "POST",
		url: "pauseTimer.php",
		data: "task_id="+$("#task_id").val(),
		cache: false,
		success: function(html)
		{
			$("#timer_feedback").html(html);
		} 
		});
	});

	$('#resetButton').click(function() {
		var areYouSure=confirm("Reset timer?");
		
		if(areYouSure==true){
		
			$('#runner').runner('stop');
			console.log($('#runner').runner('info'));
			$('#stopButton').hide();
			$('#startButton').show();
			$('#submitButton').hide();
	
			$.ajax
			({
			type: "POST",
			url: "resetTimer.php",
			data: "task_id="+$("#task_id").val(),
			cache: false,
			success: function(html)
			{
				$("#timer_feedback").html(html);
				$("#runner").runner('setNewStartAt',0);
				$("#runner").runner('reset');

				$('#startButton').trigger('click');
				
			} 
			});
			
		}else{
			return false;
		}



	});


	$('#submitButton').click(function() {
		$('#runner').runner('stop');
		console.log($('#runner').runner('info'));
		$('#startButton').show();
		$('#submitButton').hide();
		$('#stopButton').hide();
		$('#timer_feedback').show();
		$("#timer_feedback").fadeOut(3000);
		$("#runner").fadeTo( 100, 0.01 );
		

		$.ajax
		({
			type: "POST",
			url: "pauseTimer.php",
			data: "task_id="+$("#task_id").val(),
			cache: false,
			success: function(html)
			{
				$("#timer_feedback").html(html);
			} 
		});

		
			BootstrapDialog.show({
				title: 'Log time for <strong>'+$('#task_id option:selected').text()+'</strong>',
	            message: function(dialog) {
	                var $message = $('<div><img src="images/ajax-loader-black.gif" /> Please wait...</div>');
	                var pageToLoad = dialog.getData('pageToLoad');
	                $message.load(pageToLoad);
	                return $message;
	            },
	            type: BootstrapDialog.TYPE_WARNING,
	            data: {
	                'pageToLoad': 'getActivities.php?time='+$('#runner').runner('info').formattedTime+'&task_id='+currentTask
	            },
            buttons: [
             {
                label: 'Cancel',
                cssClass: 'btn-default btn-sm btn',
                action: function(dialogItself){
                    dialogItself.close();

					$('#submitButton').show();
					$("#runner").fadeTo( 100, 1 );

					console.log($('#runner').runner('info').running);
//					alert($('#runner').runner('info').running);

					if($('#runner').runner('info').running==false){
						$('#startButton').show();
						$('#stopButton').hide();
						$('#runner').runner('stop');
						$.ajax({
							type: "POST",
							url: "pauseTimer.php",
							data: "task_id="+$("#task_id").val(),
							cache: false,
							success: function(html)
							{
								$("#timer_feedback").html(html);
							} 
						}); 
					}else{
						$('#startButton').hide();
						$('#stopButton').show();
						$('#runner').runner('start');
						$.ajax({
							type: "POST",
							url: "startTimer.php",
							data: "task_id="+$("#task_id").val(),
							cache: false,
							success: function(html)
							{
								$("#timer_feedback").html(html);
							} 
						}); 
					}




                }
            },
            {
	            id: 'submit-button',
                label: 'Submit Time',
                cssClass: 'btn-primary btn-sm btn',
                action: function(dialogItself){
	                if($('#time_amt').val().length<1){
		                alert("Please enter time amount to log.");
		                return false;
	                }
	                if($('#activity_id_submit')[0].selectedIndex==0){
		                alert("Please select an activity to log.");
		                return false;
	                }
	                
					var $button = this; // 'this' here is a jQuery object that wrapping the <button> DOM element.
                    $button.disable();
                    $button.spin();
					$.ajax
					({
						type: "POST",
						url: "submitTimer.php",
						data: "activity_id="+$('select[name=activity_id_submit]').val()+"&task_id="+currentTask+"&time="+$('#time_amt').val()+"&log_comment="+$('#log_comment').val()+"&mark_done="+$('#mark_done').val(),
						cache: false,
						success: function(html)
						{
		                    dialogItself.close();
							$("#timer_feedback").html(html);
							location.href = "time.php?message="+encodeURIComponent(html);

						} 
					});

	                
	                
                }
            }]
	        });
	});

					$('#task_comment').on('focus', function (e) {
					$('#task_comment').val('');	
						});
						
			

			
					
					$('#task_comment_button').on('click', function (e) {
							$.ajax
							({
							type: "POST",
							url: "postComment.php",
							data: "task_id="+$("#task_id").val()+"&comment="+$("#task_comment").val(),
							cache: false,
							success: function(html)
							{
								$("#task_comment").val('');
								$("#task_id").html(html);
								
										$.ajax
										({
											type: "POST",
											url: "getTaskData.php",
											dataType: "json",
											data: "task_id="+currentTask,
											cache: false,
											success: function(taskJson)
											{
												commentsOutput="";
												$("#task_comments").html("");
												for (i=0;i<taskJson.comments.length;i++) {
													if (typeof taskJson.comments[i].comment != 'undefined'){
												    commentsOutput+='<div><span class="small-date">' + jQuery.format.prettyDate(taskJson.comments[i].updated_at) + "</span> " + taskJson.comments[i].comment + "</div>";
												    }
												}
												$("#task_comments_post").show();
												$("#task_comments").hide();
												$("#task_comments").html(commentsOutput);
												$("#task_comments").fadeIn(250);
			
										$.ajax
										({
										type: "POST",
										url: "getTasks.php",
										data: "project_id="+$("#project_id").val(),
										cache: false,
										success: function(html)
										{
											$("#task_id").html(html);
											$("#task_id").val(currentTask);
										} 
										});
			
			
											} 
										});
								} 
								});
					});





</script>

<? include_once('footer.php');	?>