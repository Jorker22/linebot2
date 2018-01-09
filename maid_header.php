<?php 

session_start();
require_once("../callback/LineLoginLib.php");
require_once("class/AzyMaid.php");
require_once("class/AzyBot.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('LINE_LOGIN_CHANNEL_ID','1553173384');
define('LINE_LOGIN_CHANNEL_SECRET','a47f24be001ab71dfe5d3a271f73c43e');
define('LINE_LOGIN_CALLBACK_URL','https://azyservice.com/callback/maid_callback.php');
define("ADMIN_GROUP","Ccc69fd9f5b3b590ff765cb975532812a");

$_SESSION["url"] = urlencode($_SERVER['REQUEST_URI']);
$LineLogin = new LineLoginLib(LINE_LOGIN_CHANNEL_ID, LINE_LOGIN_CHANNEL_SECRET, LINE_LOGIN_CALLBACK_URL);
	   
if(!isset($_SESSION['ses_login_accToken_val'])){    
	$LineLogin->authorize();
	exit;
}

$accToken = $_SESSION['ses_login_accToken_val'];
// GET LINE USER PROFILE 
$userInfo = $LineLogin->userProfile($accToken,true);
$userId = $userInfo['userId'];
$displayName = $userInfo['displayName'];

$AzyMaid = new AzyMaid($userId,$displayName,ADMIN_GROUP);

?>