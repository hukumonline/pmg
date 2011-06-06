<?php
require_once './library/mobile_device_detect.php';
if (mobile_device_detect()) {
	$uri = $_SERVER['REQUEST_URI'];
	header("Location:http://m.hukumonline.com".$uri);	
}
else
{

include_once("baseinit.php");

$application->bootstrap()
        ->run();

}