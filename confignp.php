<?php
	/* Configuration du script */
	// Base facturation
	include('config/db.php');

	$option_name = $prefix . 'options';

	include('build/class.bdpoo.php');
	$bd = new Bd;
	$bd->config($host,$login,$password,$basename,$prefix,$option_name);

	$fichier = 'config/confignp.ini';
	$tableauIni = parse_ini_file($fichier);
	while (list($cles, $val) = each($tableauIni)) {
		//echo $key.' : '.$val."<br>";
		${$cles} = $val;
	}
	$link = mysqli_connect($host,$login_grc,$password_grc,$base_grc) or die('Can\'t connect to MySQL');

	include('include/login.php');
