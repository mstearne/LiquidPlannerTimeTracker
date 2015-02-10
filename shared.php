<?
// 		print date("s")."<br>";

scriptTimer("in shared.php");

/// Configuration information
$pathLPAccountEmailID="ced094287fa4ab40846c4ac26b546750";
$LPAdminAccountUsername="mstearne@pathinteractive.com";
$LPAdminAccountPassword="Pathinc123";

session_start();


/*
print $_SERVER["SCRIPT_NAME"];
print_r($_SESSION);
*/
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
		scriptTimer(print_r($workspaces,true));	    
    }
    //print_r($workspaces);
    $ws = $workspaces[0];
    $lp->workspace_id = $ws->id;
    $projects = $lp->projects();
	@usort($projects, "cmp");
    $count = count($projects);
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
    return $this->get('/account');
  }

  public function workspaces() {
    return $this->get('/workspaces');
  }

  public function projects() {
    return $this->get("/workspaces/{$this->workspace_id}/projects");
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
//	unlink("tmp/scriptTimer.txt");
	file_put_contents("tmp/scriptTimer.txt", date("H:i:s").": ".$label." ".$_SERVER["REQUEST_URI"]."\n",FILE_APPEND);
}



?>