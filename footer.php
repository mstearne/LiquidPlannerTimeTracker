
            </div> <!-- #main -->
        </div> <!-- #main-container -->


        <div class="footer-container">
            <footer class="wrapper">
                &copy;2015 Path Interactive - <a style="color:purple" href="mailto:mstearne@pathinteractive.com?subject=Time Tracker Bug Report">Please report any bugs</a> 
            </footer>
        </div>

<? if($_SERVER['REQUEST_URI']=="/liquidplanner/time.php"){ ?>
<iframe marginwidth="0" marginheight="0" width="1" height="1" scrolling="no" frameborder=0 src="stayLoggedIn.php">
</iframe>
<? } ?>
        <script src="js/main.js"></script>

  <script src="js/chosen_v1/chosen.jquery.js" type="text/javascript"></script>
  <script src="js/jquery.popupWindow.js" type="text/javascript"></script>

  <script type="text/javascript">
/*
    var config = {
      '.chosen-select'           : {width:"85%",no_results_text:'Oops, nothing found!'}
    }
*/
    
    
//	$(".chosen-select").chosen({width:"85%",no_results_text: "Oops, nothing found!"});
    
  </script>



		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		
		  ga('create', 'UA-5309252-7', 'auto');
		  ga('send', 'pageview');
		
		</script>
    </body>
</html>
<? 
	if(function_exists('scriptTimer')){
		scriptTimer("footer");
	}
?>
