
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
        case 'psd':
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
        	return '<img src="https://app.liquidplanner.com/assets/icons/pdf_file-daf4a4a712045875887b260bc0cbc75c.png">';
    }
};

