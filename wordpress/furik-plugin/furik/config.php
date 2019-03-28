<?php
 
$config = array(
    'HUF_MERCHANT' => "",
    'HUF_SECRET_KEY' => "",			//secret key for account ID (HUF)	
    'CURL' => true,					//use cURL or not
    'SANDBOX' => true,				//true: sandbox transaction, false: live transaction
    'PROTOCOL' => 'http',			//http or https

    'BACK_REF' => $_SERVER['HTTP_HOST'] . '/wordpress/',		   //url of payment backref page
    'TIMEOUT_URL' => $_SERVER['HTTP_HOST'] . '/timeout.php',     //url of payment timeout page
    'IRN_BACK_URL' => $_SERVER['HTTP_HOST'] . '/irn.php',        //url of payment irn page
    'IDN_BACK_URL' => $_SERVER['HTTP_HOST'] . '/idn.php',        //url of payment idn page
    'IOS_BACK_URL' => $_SERVER['HTTP_HOST'] . '/ios.php',        //url of payment idn page

    'GET_DATA' => $_GET,
    'POST_DATA' => $_POST,
    'SERVER_DATA' => $_SERVER,

    'LOGGER' => false,                                   //basic transaction log
    'LOG_PATH' => 'log',  								//path of log file

	'DEBUG_LIVEUPDATE_PAGE' => false,					//Debug message on demo LiveUpdate page (only for development purpose)
	'DEBUG_LIVEUPDATE' => false,						//LiveUpdate debug into log file
	'DEBUG_BACKREF' => false,							//BackRef debug into log file
	'DEBUG_IPN' => false,								//IPN debug into log file
	'DEBUG_IRN' => false,								//IRN debug into log file
	'DEBUG_IDN' => false,								//IDN debug into log file
	'DEBUG_IOS' => false,								//IOS debug into log file
	'DEBUG_ONECLICK' => false,							//OneClick debug into log file
	'DEBUG_ALU' => false,								//ALU debug into log file
);

@include_once "config_local.php";
?>
