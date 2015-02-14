<? include_once('shared.php'); 
	
	
	
if($_REQUEST['action']=="login"){

		/// check if there are old cache files that can be deleted, delete them now. 
		$files = glob("cache/*");
		$time  = time();
		
		foreach ($files as $file)
		if (is_file($file))
		if ($time - filemtime($file) >= 60*60) // 1 hour old
		unlink($file);

	
/// check the username and password again LP
    $email = $_REQUEST['lpusername']; //trim(fgets(STDIN));
    $password = $_REQUEST['lppassword']; //trim(fgets(STDIN));
    $lp = new LiquidPlanner($email, $password);

    $account = $lp->account();

    if($account){
	    
	    $_SESSION['lpusername']=$_REQUEST['lpusername'];
	    $_SESSION['lppassword']=$_REQUEST['lppassword'];
	    $_SESSION['lpuserid']=$account->id;

	    $email = $_SESSION['lpusername']; 
	    $password = $_SESSION['lppassword']; 
	    $member_id = $_SESSION['lpuserid']; 
	    $lp = new LiquidPlanner($email, $password);
	    $account = $lp->account();
	    $workspaces = $lp->workspaces();
	    $ws = $workspaces[0];
	    $lp->workspace_id = $ws->id;
	    $projects = $lp->projects();
	    usort($projects, "cmp");
	    $count = count($projects);

		$lpAdmin = new LiquidPlanner($LPAdminAccountUsername, $LPAdminAccountPassword);


	    $accountAdmin = $lpAdmin->account();
	    $workspacesAdmin = $lpAdmin->workspaces();
	    $wsAdmin = $workspacesAdmin[0];
	    $lpAdmin->workspace_id = $wsAdmin->id;
	
		/// We need to record the teams structure in LP in order make sure that we get the projects and tasks that not only this user is the owner of but also anyone on their team
		
  		///// ------------------------------------ /////
		$pageCache=false;
		$cacheSnippetName="teams-".$_SESSION['lpusername'];
		if($pageCache=@file_get_contents("cache/$cacheSnippetName")==false){
			///// -----------Start Code-------------- /////
			$teamMembers=$lpAdmin->get("/workspaces/{$lpAdmin->workspace_id}/teams","");
			///// -----------End Code-------------- /////
			file_put_contents("cache/$cacheSnippetName", json_encode($teamMembers));
		}else{
			/// Show existing cache file  
			$pageCache=file_get_contents("cache/$cacheSnippetName");
			$teamMembers=json_decode($pageCache);
		}
		///// ------------------------------------ /////
		

		$_SESSION['lpteam']=$teamMembers;
		
		for($i=0;$i<count($teamMembers);$i++){
			for($j=0;$j<count($teamMembers[$i]->member_ids);$j++){
				if($teamMembers[$i]->member_ids[$j]==$account->id){
					/// the logged in user is on this team
					$_SESSION['lpteam']=$teamMembers[$i]->member_ids;
				}	
			}
		}
		

		
	

	    header("Location: time.php");
	    exit();
	    
	    
    }else{
include_once('header.php');
?>
<div class="alert">  
  <a class="close" data-dismiss="alert">×</a>  
  <strong>Warning!</strong> Login incorrect.  <a href="https://app.liquidplanner.com/login/forgot_password">Forgot your password?</a>
</div>  
<br>
<?

//include_once('footer.php');

    }

	
	
	
	
}

if($_REQUEST['action']=="logout"){
	$_SESSION['lpusername']=false;
	$_SESSION['lppassword']=false;
	$_SESSION['lpuserid']=false;
	$_SESSION['lpteam']=false;
}

	
?>
<? include_once('header.php'); ?>



<form class="form-horizontal">
	<input type="hidden" name="action" value="login">
<fieldset>

<!-- Form Name -->
<legend>Log in to Liquid Planner</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="lpusername">Username</label>  
  <div class="col-md-4">
  <input id="lpusername" name="lpusername" type="text" placeholder="" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Password input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="lppassword">Password</label>
  <div class="col-md-4">
    <input id="lppassword" name="lppassword" type="password" placeholder="" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="submit"></label>
  <div class="col-md-4">
    <button id="submit" name="submit" class="btn btn-primary">Login</button>
  </div>
</div>
<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="lppassword"></label>
  <div class="col-md-4">
<a href="https://app.liquidplanner.com/login/forgot_password" target="_blank">Forgot Password?</a>
  </div>
</div>

</fieldset>
</form>


<? include_once('footer.php'); ?>

