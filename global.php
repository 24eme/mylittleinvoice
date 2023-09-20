<?php
	global $host;
	global $login;
	global $password;
	global $basename;
	global $dbase;
	global $logindb;
	global $passworddb;
	global $prefix;
	global $option_name;
	global $bd;

	// Base facturation
	if(!file_exists('config/config.php')){header('Location: install.php');exit();}
	include('config/config.php');

	if (!isset($option_name)) {
		$option_name = $prefix . 'options';
	}

	include('build/class.bdpoo.php');
	$bd = new Bd;
	$bd->config($host,$login,$password,$basename,$prefix,$option_name);

	include('include/login.php');
