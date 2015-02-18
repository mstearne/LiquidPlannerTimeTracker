<?

scriptTimer("in shared.php");

/// Configuration information
$pathLPAccountEmailID="ced094287fa4ab40846c4ac26b546750";
$LPAdminAccountUsername="mstearne@pathinteractive.com";
$LPAdminAccountPassword="Pathinc123";
$LPIncludedProjects=array("15882047","16782204","16782205");

session_start();

if(!$_SESSION['lpusername']&&$_SERVER["SCRIPT_NAME"]!="/liquidplanner/lpLogin.php"){
	header("Location: lpLogin.php");
	print '<meta http-equiv="refresh" content="0; url=lpLogin.php">';
	exit();
} 

if($_SESSION['lpuserid']){
    $email = $_SESSION['lpusername'];  
    $password = $_SESSION['lppassword']; 
    $member_id = $_SESSION['lpuserid']; 
    $lp = new LiquidPlanner($email, $password);
    $account = $lp->account();
    $workspaces = $lp->workspaces();
    if($_SERVER['REQUEST_URI']=="/liquidplanner/getComments.php"||$_SERVER['REQUEST_URI']=="/liquidplanner/getUnsubmittedTime.php"){
		scriptTimer(json_encode($workspaces));	    
    }
    if($workspaces->error=="Throttled"){
	    print "Throttled. Try again or <a href='mailto:mstearne@pathinteractive.com?subject=LP Throttled'>contact support</a>.";
   		scriptTimer("Throttled for $email");
	    exit();
    }
//    print_r($workspaces);
    $ws = $workspaces[0];
    $lp->workspace_id = $ws->id;
    $projects = $lp->projects();
	@usort($projects, "cmp");
    $count = count($projects);
}



/// check if there are old cache files that can be deleted, delete them now. 
$files = glob("cache/*");
$time  = time();

foreach ($files as $file){
	if (is_file($file)){
		if ($time - filemtime($file) >= 60*30){ // 1/2 hour old
			unlink($file);
		}
	}
}


function convert_decimal_hours_to_milliseconds($decimalHours){
	
	// in 1.23 hours out to 4428000 milliseconds
	// 1 hour = 3600000 milliseconds
	return $decimalHours*3600000;
	
}
	
function convert_to_time($dec)
{
    // start by converting to seconds
    $seconds = (int)($dec * 3600);
    // we're given hours, so let's get those the easy way
    $hours = floor($dec);
    // since we've "calculated" hours, let's remove them from the seconds variable
    $seconds -= $hours * 3600;
    // calculate minutes left
    $minutes = floor($seconds / 60);
    // remove those from seconds as well
    $seconds -= $minutes * 60;
    // return the time formatted HH:MM:SS
    return lz($hours).":".lz($minutes).":".lz($seconds);
}

// lz = leading zero
function lz($num)
{
    return (strlen($num) < 2) ? "0{$num}" : $num;
}


function cmp($a, $b)
{

    return strcasecmp($a->client_name, $b->client_name);
}



function time_to_decimal($time)
{
    $hms = explode(":", $time);

if(count($hms)==1){
	$hms[2]=$hms[0];
	$hms[1]=0;
	$hms[0]=0;
	
}
if(count($hms)==2){
	$hms[2]=$hms[1];
	$hms[1]=$hms[0];
	$hms[0]=0;
	
}

    
    return ($hms[0] + ($hms[1]/60) + ($hms[2]/3600));
}



class LiquidPlanner {
  private $_base_uri = "https://app.liquidplanner.com/api";
  private $_ch;
  public  $workspace_id;

  function __construct($email, $password) {
    $this->_ch = curl_init();
    curl_setopt($this->_ch, CURLOPT_HEADER, false);
    curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($this->_ch, CURLOPT_USERPWD, "$email:$password");
    curl_setopt($this->_ch, CURLOPT_HTTPHEADER, array('content-type: application/json'));
    curl_setopt($this->_ch, CURLOPT_ENCODING, 'gzip');
  }

  public function get($url) {
    curl_setopt($this->_ch, CURLOPT_HTTPGET, true);
    curl_setopt($this->_ch, CURLOPT_URL, $this->_base_uri.$url);
    return json_decode(curl_exec($this->_ch));
  }

  public function get_raw($url) {
    curl_setopt($this->_ch, CURLOPT_HTTPGET, true);
    curl_setopt($this->_ch, CURLOPT_URL, $url);
    return curl_exec($this->_ch);
  }

  public function post($url, $body=null) {
    curl_setopt($this->_ch, CURLOPT_POST, true);
    curl_setopt($this->_ch, CURLOPT_URL, $this->_base_uri.$url);
    curl_setopt($this->_ch, CURLOPT_POSTFIELDS, json_encode($body));
    return json_decode(curl_exec($this->_ch));
  }

  public function put($url, $body=null) {
    curl_setopt($this->_ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($this->_ch, CURLOPT_URL, $this->_base_uri.$url);
    curl_setopt($this->_ch, CURLOPT_POSTFIELDS, json_encode($body));
    return json_decode(curl_exec($this->_ch));
  }

  public function account() {
  		///// ------------------------------------ /////
		$pageCache=false;
		$cacheSnippetName="account-".$_SESSION['lpusername'];
		if($pageCache=@file_get_contents("cache/$cacheSnippetName")==false||!$_SESSION['lpusername']){
			///// -----------Start Code-------------- /////
			$account=$this->get('/account');
			///// -----------End Code-------------- /////
			file_put_contents("cache/$cacheSnippetName", json_encode($account));
		}else{
			/// Show existing cache file  
			$pageCache=file_get_contents("cache/$cacheSnippetName");
			$account=json_decode($pageCache);
		}
		///// ------------------------------------ /////
   return $account;
  }

  public function workspaces() {
  		///// ------------------------------------ /////
		$pageCache=false;
		$cacheSnippetName="workspaces-".$_SESSION['lpusername'];
		if($pageCache=@file_get_contents("cache/$cacheSnippetName")==false){
			///// -----------Start Code-------------- /////
			$workspaces=$this->get('/workspaces');
			///// -----------End Code-------------- /////
			file_put_contents("cache/$cacheSnippetName", json_encode($workspaces));
		}else{
			/// Show existing cache file  
			$pageCache=file_get_contents("cache/$cacheSnippetName");
			$workspaces=json_decode($pageCache);
		}
		///// ------------------------------------ /////
		return $workspaces;
  }

  public function projects() {
  		///// ------------------------------------ /////
		$pageCache=false;
		$cacheSnippetName="projects-".$_SESSION['lpusername'];
		if($pageCache=@file_get_contents("cache/$cacheSnippetName")==false){
			///// -----------Start Code-------------- /////
 			$projects=$this->get("/workspaces/{$this->workspace_id}/projects");
			///// -----------End Code-------------- /////
			file_put_contents("cache/$cacheSnippetName", json_encode($projects));
		}else{
			/// Show existing cache file  
			$pageCache=file_get_contents("cache/$cacheSnippetName");
			$projects=json_decode($pageCache);
		}
		///// ------------------------------------ /////
		return $projects;
	  
  }

  public function tasks() {
    return $this->get("/workspaces/{$this->workspace_id}/tasks");
  }

  public function create_task($data) {	

    return $this->post("/workspaces/{$this->workspace_id}/tasks", array("task"=>$data));
  }

  public function update_task($data) {
    return $this->put("/workspaces/{$this->workspace_id}/tasks/{$data['id']}", array("task"=>$data));
  }


}



function scriptTimer($label=""){
//	@unlink("tmp/scriptTimer@".date("Y-m-d")."@.txt".txt");
	file_put_contents("tmp/scriptTimer@".date("Y-m-d")."@.txt", date("H:i:s")." ".$_SESSION['lpusername'].": ".$label." ".$_SERVER["REQUEST_URI"]."\n",FILE_APPEND);
//	@chmod("tmp/scriptTimer@".date("Y-m-d")."@.txt", 0777);
}

function print_r_reverse($in) { 
    $lines = explode("\n", trim($in)); 
    if (trim($lines[0]) != 'Array') { 
        // bottomed out to something that isn't an array 
        return $in; 
    } else { 
        // this is an array, lets parse it 
        if (preg_match("/(\s{5,})\(/", $lines[1], $match)) { 
            // this is a tested array/recursive call to this function 
            // take a set of spaces off the beginning 
            $spaces = $match[1]; 
            $spaces_length = strlen($spaces); 
            $lines_total = count($lines); 
            for ($i = 0; $i < $lines_total; $i++) { 
                if (substr($lines[$i], 0, $spaces_length) == $spaces) { 
                    $lines[$i] = substr($lines[$i], $spaces_length); 
                } 
            } 
        } 
        array_shift($lines); // Array 
        array_shift($lines); // ( 
        array_pop($lines); // ) 
        $in = implode("\n", $lines); 
        // make sure we only match stuff with 4 preceding spaces (stuff for this array and not a nested one) 
        preg_match_all("/^\s{4}\[(.+?)\] \=\> /m", $in, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER); 
        $pos = array(); 
        $previous_key = ''; 
        $in_length = strlen($in); 
        // store the following in $pos: 
        // array with key = key of the parsed array's item 
        // value = array(start position in $in, $end position in $in) 
        foreach ($matches as $match) { 
            $key = $match[1][0]; 
            $start = $match[0][1] + strlen($match[0][0]); 
            $pos[$key] = array($start, $in_length); 
            if ($previous_key != '') $pos[$previous_key][1] = $match[0][1] - 1; 
            $previous_key = $key; 
        } 
        $ret = array(); 
        foreach ($pos as $key => $where) { 
            // recursively see if the parsed out value is an array too 
            $ret[$key] = print_r_reverse(substr($in, $where[0], $where[1] - $where[0])); 
        } 
        return $ret; 
    } 
} 

?>