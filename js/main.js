
function getPathFromUrl(url) {
  return url.split("?")[0];
}

function clearTimer(taskid){
	
	var r = confirm("Are you sure you want to clear this time and not submit it?");
if (r == true) {

			$.ajax
			({
			type: "POST",
			url: "clearTimer.php",
			data: "task_id="+taskid,
			cache: false,
			dataType: "text",
			success: function(html)
			{
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
						$("#unsubmitted-time").show();
					}else{
						$("#unsubmitted-time").hide();
						$("#unsubmitted-time-body").html("");
					}
				} 
				});
			} 
			});

	
} else {
	return false;
}


}