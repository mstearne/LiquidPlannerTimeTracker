<? include_once("shared.php"); ?>
<?
/// Teams structure

$members=$lp->get("/workspaces/{$lp->workspace_id}/teams","");

$_SESSION['lpteams']=$members;

// print_r($members);

?>



