<? include_once('shared.php'); ?>
<? include_once('header.php'); ?>
<?
//	print_r($_SESSION['lpteam']);
	//error_reporting(0); 
	?>

<div class="timerDiv" id="timerDiv">
<span id="runner" class="runnerStyle"></span>
&nbsp;&nbsp;&nbsp;
<span id="startButton" class="glyphicon glyphicon-play glyphiconStyle" style="display: none"><span class="glyphiconStyleText"><br>Start</span></span>
<span id="pauseButton" class="glyphicon glyphicon-pause glyphiconStyle" style="display: none"><span class="glyphiconStyleText"><br>Pause</span></span> <span id="submitButton" class="glyphicon glyphicon-send glyphiconStyle" style="display: none"><span class="glyphiconStyleText"><br>Submit</span></span>
</div>

<div>
<span id="realtimelabel" class="lighter-text"></span>
</div>  
  


<!--
<div class="lighter-text">
<br>
Select a program/project: 
</div>
-->
<div name="project_id">

<select id="project_id" class="chosen-select">
<option></option>

<?
    foreach($projects as $i => $p) {
//	    if($p->owner_id==$account->id){
			if($p->client_id!="19948524"){ // Don't show the Path internal system admin client projects
//				print_r($p);
				if($p->is_done!=1){
					for($i=0;$i<count($p->assignments);$i++){
//						if($p->assignments[$i]->person_id=="468851"){
						if($p->assignments[$i]->person_id==in_array($p->assignments[$i]->person_id, $_SESSION['lpteam'])){
							print "<option value='$p->id'>$p->client_name : $p->name</option>";
						}
					}
				}
		    }
//	    }
    }

?>
</select>
</div>


<!--
<div id="activity_select" style="display: none">
What are you doing on the program? 
<div name="activity_id">
<select class="selects" id="activity_id">
<option></option>

</select>
</div> 
</div>
-->
<br>

<div id="task_select" class="lighter-text" style="display: none">
<!-- Which task are you working on?   -->

<div name="task_id">
<select class="chosen-select-task" id="task_id">

</select> <span id="task_link"></span>
</div>

</div>





<div id="task_comments_post" style="display: none">

<div class="lighter-text">
<br>
<!--

<strong>Task comments...</strong>
-->


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
  <div class="col-lg-1">
    <button id="task_comment_button" name="task_comment_button" class="btn btn-primary">Post</button>
  </div>
</div>
</div>

<script>
var currentTask;
var currentProject;
</script>


<div id="task_documents" style="display: none; clear:both">
	
</div>
<div id="taskemailattachment" style="display: none"><span class="glyphicon glyphicon-email"></span> <span id="taskemailattachmentlabel"></span></div>

</div>


<?

$timers=$lp->get("/workspaces/{$lp->workspace_id}/my_timers");

/*
    foreach($timers as $i => $timer) {
			      if($timer->running==1){
				      $running_timer=round(($timer->running_time + $timer->total_time)*3600000);
//				      $running_timer_label="<strong>logging time for </strong> ".$task->name." in ".$pros->name;
//				      print "<span id=realtime-bottom></span> <em>Currently running</em>";
				      $gotRunningTimer=1;
				      $running_timer_id=$timer->item_id;
*/
/*
				      $running_timer_name=$task->name;
				      $running_timer_project_id=$task->project_id;
*/
				      ?>
				      <?
//			      }else{
//				      print convert_to_time($timer->total_time);
//			      }
//	      $gotAnyTimer=1;
//	}



    foreach($timers as $i => $timer) {
	      $task=$lp->get("/workspaces/{$lp->workspace_id}/tasks/{$timer->item_id}");
	        foreach($projects as $x => $pros) {
		        if($pros->id==$task->project_id){
//			      print "<li>".$task->name." in ".$pros->name." for ".$pros->client_name." Time: ";
			      if($timer->running==1){
				      $running_timer=round(($timer->running_time + $timer->total_time)*3600000);
//				      $running_timer_label="<strong>logging time for </strong> ".$task->name." in ".$pros->name;
//				      print "<span id=realtime-bottom></span> <em>Currently running</em>";
				      $gotRunningTimer=1;
				      $running_timer_id=$task->id;
				      $running_timer_name=$task->name;
				      $running_timer_project_id=$task->project_id;

				      }else{
//				      print convert_to_time($timer->total_time);
			      }
					$gotAnyTimer=1;
					$any_timer_id=$task->id;
					$any_timer_name=$task->name;
					$any_timer_project_id=$task->project_id;

		        }
	    	}
    }




//if(count($timers)>1){
	?>

<div id="unsubmitted-time" style="display: none; background-color: #ffaaaa; padding: 20px; border-radius: 5px; width: 90%"><strong>Hey. You have unsubmitted time. You should <a href="https://app.liquidplanner.com/space/<?=$lp->workspace_id?>/timesheet" target=_blank>view your timesheet to submit all your time.</a></strong> <br>
<div id="unsubmitted-time-body">

</div>
<br>
</div>

<?
//	}

	if(!$gotRunningTimer){
    	$running_timer=0;
    }
?>

<script>

var currentTask;
var currentProject;
var startupTask;

function testGlobal(){
	alert("global");
	$("#runner").runner('start');

}

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

	
	function change_project(){
		
		$('#runner').fadeOut(500);
		$("#pauseButton").fadeOut(500);
		$("#startButton").fadeOut(500);
		$("#submitButton").fadeOut(500);

		$("#task_comments_post").fadeOut(500);
		$("#task_comments").fadeOut(500);
		$("#taskemailattachment").fadeOut(500);
		$("#taskemailattachmentlabel").fadeOut(500);


		
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
			
				$(".chosen-select").chosen({width:"75%",no_results_text: "Oops, nothing found!",placeholder_text_single:"Select the program or project you're working on"});
				$(".chosen-select-task").chosen({width:"75%",no_results_text: "Oops, nothing found!",placeholder_text_single:"Select the task you're working on"});
				$('.chosen-select-task').trigger('chosen:updated');
		} 
		});
	}


function openFile(file,taskID,documentID) {
    var extension = file.substr( (file.lastIndexOf('.') +1) );
    switch(extension) {
        case 'jpg':
        case 'png':
        case 'gif':
            return '<img src="https://app.liquidplanner.com/space/<?=$lp->workspace_id?>/item/'+taskID+'/documents/'+documentID+'/thumbnail">';
        break;                         // the alert ended with pdf instead of gif.
        case 'zip':
        case 'rar':
        	return '<img src="https://app.liquidplanner.com/assets/icons/zip_file-0bbfaaf53712307f1bbae557ae495ec9.png">';
        break;
        case 'pdf':
        	return '<img src="https://app.liquidplanner.com/assets/icons/pdf_file-daf4a4a712045875887b260bc0cbc75c.png">';
        break;
        case 'doc':
        case 'docx':
        	return '<img src="https://app.liquidplanner.com/assets/icons/doc_file-495c3a9721da16baaf8f3dc921a89782.png">';
        break;
        case 'xls':
        case 'xlsx':
        	return '<img src="https://app.liquidplanner.com/assets/icons/xls_file-417b766a13a8444ef2fa0cba316d3295.png">';
        break;
        case 'ppt':
        case 'pptx':
        	return '<img src="https://app.liquidplanner.com/assets/icons/ppt_file-a0e4aa1ad371096946fb2d963784c61e.png">';
        break;
        default:
            alert('who knows');
    }
};



	function change_task(){

		var commentsOutput;
		var documentsOutput;

		$("#task_link").html('<a href="https://app.liquidplanner.com/space/<?=$lp->workspace_id?>/projects/show/'+$("#task_id").val()+'" target=_blank><span class="glyphicon glyphicon-new-window" aria-hidden="true"></span> View in LP</a>');

		$("#unsubmitted-time").hide();
		$("#task_comments_post").hide();
		$("#task_comments").hide();
		$("#runner").fadeTo( 2000, 0.01 );

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
				if(taskJson.timer){
					if(taskJson.timer.running){
						var taskTimeMS=(taskJson.timer.running_time+taskJson.timer.total_time)*3600000;
					}else{
						var taskTimeMS=taskJson.timer.total_time*3600000;
					}
				}else{
					var taskTimeMS=0;
				}

				$('#runner').runner({
			    autostart: false,
			    countdown: false,
			    startAt: parseInt(taskTimeMS),
				milliseconds: false		
				});

				$("#runner").runner('start');
				$("#pauseButton").fadeIn(500);
				$("#startButton").hide();
				$("#submitButton").fadeIn(500);
				$('#runner').fadeTo(500,1);


			    commentsOutput="";
			    for (i=0;i<taskJson.comments.length;i++) {	    
			        commentsOutput+='<div><span class="small-date">' + taskJson.comments[i].updated_at + "</span> " + taskJson.comments[i].comment + "</div>";
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
				//$("#realtime").text("<?=$running_timer?>");
				//$("#realtimelabel").html("<strong>logging time for </strong> "+$('#task_id option:selected').text()+" in "+$('#project_id option:selected').text()+'&nbsp;<a href="https://app.liquidplanner.com/space/<?=$lp->workspace_id?>/projects/show/'+$("#task_id").val()+'" target=_blank><span class="glyphicon glyphicon-new-window" aria-hidden="true"></span> View in LP</a>');
				
				$("#taskemailattachment").show();
				$("#taskemailattachmentlabel").html('<a href="mailto:'+$("#task_id").val()+'-<?=$pathLPAccountEmailID?>@in.liquidplanner.com"><span class="glyphicon glyphicon-paperclip"></span> Send attachment to task</a><br>&nbsp;');

				$("#task_documents").html(documentsOutput);
				$("#task_documents").fadeIn(50);


			}
		});


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
		} 
		});

		currentTask=$("#task_id").val();
	
	}	
	
$(document).ready (function () {

$('.ttpopup').popupWindow({ 
height:800, 
width:400, 
top:0, 
left:50 
}); 

	
	<? if($_REQUEST['message']){ ?>
	/// display some feedback message after the reload
	$('#timer_feedback').html("<?=$_REQUEST['message']?>");
	
	$('#timer_feedback').show();
	  $("#timer_feedback").fadeOut(3000);
	
	setTimeout(function(){ $('#timer_feedback').html("")}, 4000);
	
	<? } ?>
	
	
//	$("#realtimelabel").html("<?=$running_timer_label?>");
	
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
				$(".chosen-select").chosen({width:"75%",no_results_text: "Oops, nothing found!",placeholder_text_single:"Select the program or project you're working on"});
	<? } ?>

	$(document).ajaxSend(function(event, request, settings) {
	  $('#loading-indicator').show();
	});
	
	$(document).ajaxComplete(function(event, request, settings) {
	  $('#loading-indicator').hide();
	  $('#timer_feedback').show();
	  $("#timer_feedback").fadeOut(4000);
	  
	});

});


	$('#startButton').click(function() {
//		$('#runner').runner('stop');
		$('#runner').runner('start');
		console.log($('#runner').runner('info'));
		$('#pauseButton').show();
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
	});

	$('#pauseButton').click(function() {
		$('#runner').runner('stop');
		console.log($('#runner').runner('info'));
		$('#pauseButton').hide();
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


	$('#submitButton').click(function() {
		$('#runner').runner('stop');
		console.log($('#runner').runner('info'));
//		$('#runner').hide();
		$('#startButton').show();
		$('#submitButton').hide();
		$('#pauseButton').hide();
//		$('#timer_feedback').html(currentTask);
		$('#timer_feedback').show();
		$("#timer_feedback").fadeOut(3000);

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
	                var $message = $('<div></div>');
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
                action: function(dialogItself){
                    dialogItself.close();

//					$('#runner').show();
					$('#runner').runner('start');
					$('#startButton').hide();
					$('#pauseButton').show();
					$('#submitButton').show();

					console.log($('#runner').runner('info'));

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
            },
            {
	            id: 'submit-button',
                label: 'Submit Time',
                cssClass: 'btn-primary',
                action: function(dialogItself){
	                if($('#time_amt').val().length<1){
		                alert("Please enter time amount to log.");
		                return false;
	                }
//	                alert($('select[name=activity_id_submit]').val());
	                if($('#activity_id_submit')[0].selectedIndex==0){
		                alert("Please select an activity to log.");
		                return false;
	                }
	                
					//$(this).prop("disabled",true);
					var $button = this; // 'this' here is a jQuery object that wrapping the <button> DOM element.
                    $button.disable();
                    $button.spin();
					$.ajax
					({
						type: "POST",
						url: "submitTimer.php",
						data: "activity_id="+$('select[name=activity_id_submit]').val()+"&task_id="+currentTask+"&time="+$('#time_amt').val()+"&log_comment="+$('#log_comment').val(),
						cache: false,
						success: function(html)
						{
		                    dialogItself.close();
							$("#timer_feedback").html(html);
							location.href = "time.php?message="+encodeURIComponent(html);
//							location.replace(location.href);

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
												    commentsOutput+='<div><span class="small-date">' + taskJson.comments[i].updated_at + "</span> " + taskJson.comments[i].comment + "</div>";
												    }
												}
												$("#task_comments_post").show();
												$("#task_comments").hide();
												$("#task_comments").html(commentsOutput);
												$("#task_comments").fadeIn(350);
			
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



<? include_once('footer.php'); ?>

