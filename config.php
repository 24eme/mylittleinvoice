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
    global $grc_config;

	global $fichier;
	global $tableauIni;
	global $link;


	$grc_config = [
		'at86' => [
            'path' => null,
            'soap_url' => null,
            'soap_user' => null,
            'soap_pass' => null,
			'exportservice_path' => null,
        ],
		'urba' => [
			'path' => null,
			'soap_url' => null,
            'soap_user' => null,
            'soap_pass' => null,
			'exportservice_path' => null,
		],
		'dpd' =>  [
			'path' => null,
			'soap_url' => null,
            'soap_user' => null,
            'soap_pass' => null,
			'exportservice_path' => null,
		]
	];

	/* Configuration du script */
	// Base facturation
	include('config/db.php');

	$option_name = $prefix . 'options';

	include('build/class.bdpoo.php');
	$bd = new Bd;
	$bd->config($host,$login,$password,$basename,$prefix,$option_name);

	$fichier = 'config/config.ini';
	$tableauIni = parse_ini_file($fichier);
	while (list($cles, $val) = each($tableauIni)) {
                global ${$cles};
		//echo $key.' : '.$val."<br>";
		${$cles} = $val;
	}
	$link = mysqli_connect($host,$login_grc,$password_grc,$base_grc) or die('Can\'t connect to MySQL');

	include('include/login.php');
