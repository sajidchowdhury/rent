<?php
session_start();
include("connect_me.php");

## DATE TIME
$date = date("Y-m-d");
$time = date("h:i:s a");
	
## STATUS UPDATE
$update = db::conn()->prepare("UPDATE `webpush_member` 
		SET 
			`flag` = '0',
			`last_date` = '".date("Y-m-d")."',
			`last_time`='".date("h:i:s a")."' 
            
		WHERE `member_id` = '".$_SESSION['RENT_COLL_SESS_MEMBER_ID']."' ");

$update->execute();	
	
//Unset the variables stored in session
session_destroy();
session_unset($_SESSION['RENT_COLL_SESS_MEMBER_ID']);
session_unset($_SESSION['USER_TYPE']);

## REDIRECT
define('BASE_URL', '/new_rent/');
header("location: login/logout");
exit();
?>
