<?

$body = array(
    "project" => array("name" => "New SLBC Project", "owner_id" => "344851", "promise_by" => "12/15/13")
    );
$json_body = json_encode($body);
$conn = curl_init("https://app.liquidplanner.com/api/workspaces/121138/projects");
curl_setopt_array($conn, array(
    CURLOPT_ENCODING => "",
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_FORBID_REUSE => true,
    CURLOPT_FRESH_CONNECT => true,
    CURLOPT_HEADER => false,
    CURLOPT_HTTPHEADER => array('Content-Type:application/json', 'Content-Length: ' . strlen($json_body)),
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $json_body,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_USERPWD => $_SESSION['email'] . ":" . $_SESSION['pwd']
));
$response = curl_exec($conn);
curl_close($conn);
return $response;

?>