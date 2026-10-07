<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

/*
Michelle Knight's Drop Calc - Version 3
Author - Michelle Knight
Copyright 2006
Contact - dropcalc@msknight.com

GNU General Licence
Use and distribute freely, but leave headers intact and make no charge.
Change HTML code as necessary to fit your own site.
Code distributed without warantee or liability as to merchantability as
no charge is made for its use.  Use is at users risk.
*/


include('config.php');
include('config-read.php');
include('skin.php');
include('common.php');

// Retrieve environment variables
$username = input_check($_REQUEST['username'],1);
$token = input_check($_REQUEST['token'],0);
$langval = input_check($_REQUEST['langval'],2);
$ipaddr = $_SERVER["REMOTE_ADDR"];
$sendchat = $_REQUEST['sendchat'];

$langfile = $language_array[$langval][1];
include($langfile);		// Import language variables.
$sendchat1 = utf82lang ($sendchat);
echo "$sendchat - $sendchat1";
header ("Location: makeannounce.php?sendchat=$sendchat1&username=$username&token=$token&langval=$langval&server_id=$server_id&skin_id=$skin_id");
?>