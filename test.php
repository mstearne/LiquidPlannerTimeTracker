<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="js/jquery.runner.js"></script>

Standard .runner<div id="runner"></div>
Ajax .runner<div id="runnerAjax"></div>

<script>

var varTest;
varTest=12000;

		$.ajax
		({
			type: "POST",
			url: "testAjax.php",
			cache: false,
			dataType: "text",
			success: function(html)
			{
//				var newInt=parseInt(html);
//				alert(parseInt(html));
					$('#runnerAjax').runner({
				    autostart: false,
				    countdown: false,
				    startAt: parseInt(html),
					milliseconds: true		
					});
 					$("#runnerAjax").runner('start');

			} 
		});

		$('#runner').runner({
	    autostart: false,
	    countdown: false,
	    startAt: varTest,
		milliseconds: true		
		});
			$("#runner").runner('start');

</script>

<? phpinfo() ?>