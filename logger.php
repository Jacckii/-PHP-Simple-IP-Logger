<?php
if (!isset($vist_page))
{
	$vist_page     =   "logger.php";
}
$user_agent     =   $_SERVER['HTTP_USER_AGENT'];

// YOU NEED TO CONFIGURE BROWSCAP FOR THIS TO WORK
$browser        =   get_browser($user_agent, true);
$user_os        =   $browser['platform'];
$user_browser   =   $browser['browser'];

function getIP() 
{ 
	if (isset($_SERVER)) 
	{ 
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) 
		{ 
			$realip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
		} 
		else
			if (isset($_SERVER['HTTP_CLIENT_IP'])) 
			{ 
				$realip = $_SERVER['HTTP_CLIENT_IP']; 
			} 
			else 
			{ 
				$realip = $_SERVER['REMOTE_ADDR']; 
			} 
		} 
	else 
	{
		if (getenv("HTTP_X_FORWARDED_FOR")) 
		{ 
			$realip = getenv( "HTTP_X_FORWARDED_FOR"); 
		} 
		else
			if (getenv("HTTP_CLIENT_IP")) 
			{ 
				$realip = getenv("HTTP_CLIENT_IP"); 
			}
			else
			{ 
				$realip = getenv("REMOTE_ADDR"); 
	} 
} 
return $realip; 
}
session_start();
if(!isset($_SESSION[$vist_page]) || $_SESSION[$vist_page]!=1)
{
	$_SESSION[$vist_page]=1;

	// Getting visitor IP address
	//$ip = $_SERVER['REMOTE_ADDR'];
	$ip = getIP();
	// Getting where visitor come
	$site_refer = "";
	if(isset($_SERVER['HTTP_REFERER'])) {
 		$site_refer = $_SERVER['HTTP_REFERER'];
	}
	// If hes connected directly 
	if($site_refer == ""){
		$site = "direct connection";
	}
 	// If he doesn't
	else{
		$site = $site_refer;
	}
  	// Hide ownr's IP address
	$owner = "HIDE THIS IP ADDRESS";   //Change $owner for something else, cuz someone can simple read that by calling out $owner
  	// change it for $absdfs5sd4 for example and change it down there 
	$owner_country = "YOUR COUNTRY TAG FOR YOUR IP ↑"; //This u can leave how it is.

	if($ip == $owner){ //Change it here 
		$ip = "Owner"; 
		$country = $owner_country;
	}
 	//If that wasn't you, it woun't change IP address and it will find info about IP address
	else{
		$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}"));
		$country = $details->country;
	}
	// Writing in to txt file
	file_put_contents(__DIR__ . '/iplist.txt',date("Y-m-d - H:i:s - "). $country. " ". $ip." | ". $vist_page." | ". $user_os. " | ". $user_browser. " | From: ". $site. " | user agent:" .$user_agent .PHP_EOL , FILE_APPEND | LOCK_EX);
}
?>
