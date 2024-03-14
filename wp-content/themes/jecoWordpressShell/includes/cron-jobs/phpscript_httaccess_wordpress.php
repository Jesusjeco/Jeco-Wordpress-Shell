<?php

/**************************************
 *  Myip.ms, Copyright (c) 2013
 *    
 *  FreePHP Script � This Script Automatically Downloads the 
 *  Latest Blacklist IP List to your website and saves blacklist 
 *  ip in your file .htaccess. This will block all spam bots, 
 *  prohibits spam bots from accessing your site. 
 *  You can setup this script as cron job (every hour)

 *  If you use NGINX - please use function nginx_htaccess() from 
 *  the file phpscript_nginx.php at the top of each of your php files. 
 *  By default NGINX ignore .htaccess data, but this procedure manually 
 *  compares to determine if user IP in .htaccess blacklisted and 
 *  if yes -  blocks it from accessing your website pages
 *  
 *  More info: http://myip.ms/browse/blacklist/
 *  
/**************************************/

error_reporting(E_ALL);

// Latest Blacklist IP file
$file 	= "http://myip.ms/files/blacklist/htaccess/latest_blacklist.txt";

// Latest Blacklist User Submitted IP 
// Optional, delete this variable $file2 if you don't need it
$file2 	= "http://myip.ms/files/blacklist/htaccess/latest_blacklist_users_submitted.txt";

// .htaccess apache file
//$file3 = rtrim($_SERVER['DOCUMENT_ROOT'], "/ ")."/.htaccess";
$file3 = ABSPATH . "/.htaccess"; 

// Separator
$line 	= "## BLACKLIST IP AUTO ADDED ##";

$data = @file_get_contents($file);
if ($data === false || !$data) die ("<font color='red'><b>Error!</b></font> No access to file: $file");

$htaccess = @file_get_contents($file3);
if ($htaccess === false) die ("<font color='red'><b>Error!</b></font> No access to file: $file3 in your website root directory. Please create this file or change file permissions if it exists");

$htaccess = trim($htaccess);
if (stripos($htaccess, $line) !== false) $htaccess = trim(substr($htaccess, 0, stripos($htaccess, $line)));
if (stripos($data, "# Copyright")) $data = substr($data, 0, strripos($data, "# Copyright")) . substr($data, strripos($data, "##############")+16);
$htaccess .= "\n\n\n" . $line . "\n\n" . $data;  

if (isset($file2) && $file2)
{
	$data2 = @file_get_contents($file2);
	if ($data2 === false || !$data2) die ("<font color='red'><b>Error!</b></font> No access to file: $file2");
	
	$data2 = substr($data2, strripos($data2, "##############"));
	$htaccess .= "\n\n" . $data2;
}

$htaccess = trim($htaccess) . "\n\n";


$res = file_put_contents($file3, $htaccess);

if ($res === false) echo ("<font color='red'><b>Error!</b></font> Cannot write blacklist ip to file: $file3 in your website root directory. Please change file permissions to 0777 (command: chmod 0777 $file3)");
else echo ("<font color='green'><b>File .htaccess successfully updated with new Myip.ms Blacklist IPs.</b></font><br>Date: " . date("r"));
