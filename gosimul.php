 <?php
	/*
                This file is part of MyLittleInvoice.

    MyLittleInvoice is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License.

    MyLittleInvoice is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Foobar.  If not, see <http://www.gnu.org/licenses/>
                */
	include('include/head.php');
	global $bd;
	global $link;
	global $lang;

	$Rtotadh2017 = 0;
	$Rtotadha = 0;
	$Rtotadhesion = 0;
	$Rtots1 = 0;
	$Rtots2 = 0;
	$Rtots3MET = 0;
	$Rtots3DEMAT = 0;
	$Rtots2MP = 0;
	$Rtots3SI = 0;
	$Rtots3MESS = 0;
	$Rtots3SVE = 0;
	$Rtotregul = 0;
	$Rtottotal = 0;

	$fichier = 'config/confignp.ini';
	$tableauIni = parse_ini_file($fichier);
	foreach($tableauIni as $cles => $val) {
		${$cles} = $val;
	}
	?>

 <body class="skin-blue sidebar-mini">
 	<div class="wrapper">

 		<?php include('include/header.php'); ?>
 		<!-- Left side column. contains the logo and sidebar -->
 		<?php include('include/left.php'); ?>
 		<!-- Main content -->
 		<div class="content-wrapper">
 			<!-- Content Header (Page header) -->
 			<section class="content-header">
 				<h1>
 					<i class="fa fa-cubes"></i> <?php echo $lang['global_simulation']; ?>
 				</h1>
 				<ol class="breadcrumb">
 					<li><a href="index.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
 					<li class="active"><i class="fa fa-cubes"></i> <?php echo $lang['global_simulation']; ?></li>
 				</ol>
 			</section>

 			<!-- Main content -->
 			<section class="content">
 				<div class="row">
 					<div class="col-xs-12">
 						<div class="box">
 							<div class="box-body">

 								<div class="jumbotron">
 									<h2>Lancement simulation pour <?php echo $anneenp; ?></h2>
 									<div>
 										<table class="table table-striped">
 											<thead>
 												<tr>
 													<th>Insee</th>
 													<th>Collectivit&eacute;</th>
 													<!-- <th>Adh 2017</th>
 													<th>Adh 2018</th> -->
 													<th>Adh</th>
 													<th>S1</th>
 													<th>S2</th>
 													<th>S3 M&eacute;t</th>
 													<th>S3 D&eacute;m</th>
 													<th>S3 MP</th>
 													<th>S3 SI</th>
 													<th>S3 MESS</th>
 													<th>S3 SVE</th>
 													<th>R&eacute;gul</th>
 													<th>DPD</th>
 													<th>TOTAL</th>
 												</tr>
 											</thead>
 											<tbody>

 												<?php
													function mysqli_result($res, $row, $col)
													{
														$numrows = mysqli_num_rows($res);
														if ($numrows && $row <= ($numrows - 1) && $row >= 0) {
															mysqli_data_seek($res, $row);
															$resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
															if (isset($resrow[$col])) {
																return $resrow[$col];
															}
														}
														return false;
													}

													date_default_timezone_set('Europe/Paris');

													$reqquery = "SELECT A.*, C.* FROM " . $table_accounts . " A inner join " . $table_accounts_cstm . " C  on A.id = C.id_c where A.is_supplier!='1' && A.deleted='0' && A.account_type!='Manufacturer' && (C.adhesion_at86_c='adhesion_oui') order by numero_adherent_collectivite_c asc";
													$reqresult = mysqli_query($link, $reqquery);
													$reqnum = mysqli_num_rows($reqresult);

													$z = 0;
													while ($z < $reqnum) {
														$name = mysqli_result($reqresult, $z, "name");
														$code_adh = mysqli_result($reqresult, $z, "numero_adherent_collectivite_c");
														$id = mysqli_result($reqresult, $z, "id");
														$idsimul = $id;
														$adhesion_vs = mysqli_result($reqresult, $z, "adhesion_vs_c");
														$compte = $name;

														//echo "<div class=\"panel panel-primary \">";
														//echo "<div class=\"panel-heading\"><h3 class=\"panel-title\">".$compte." : ".$code_adh." -> Id : ".$idsimul."</h3></div>";
														//echo "<div class=\"panel-body\">";
														//Lancement de la simulation par collectivit�            
														//$exclusion = array("99999","86710","87147","86522","86613","87088","87135","87085","86600","87087","87114","86604","86603","86601","86501","86773","87079","87227","87092","86602","87214","87222","87140","87093","86504","87137","87138","87122","87117","87225","87217","87133","86606","86854","87094","86608","87095","87219","87097","87098","87134","87083","87099","86614","87204","87145","86507","87100");
														$exclusion = array();
														if (in_array($code_adh, $exclusion)) {
															//    echo "Simulation exclue !";
														} else {
															$fusion = array();
															//$fusion = array ("86281","86060","86030","86071","86053","86208","86019","86219","86115","86146","87001","86404","86421","86401","86400","87141","86403","86410","87213","86420","87078","86409","86422","86406","86405","86908","86744","86415","86414","86907","86444","86996","86984","86952","86731","86997");
															if (in_array($code_adh, $fusion)) {
																//	echo "Simulation exclue : Fusion !";
															} else {
																////////////////////////////////////////////////////////////////////////////////
																// DEBUT SIMULATION                                                           //
																////////////////////////////////////////////////////////////////////////////////

																$name_fac = $name_facturation;
																$ligne_article = 0;
																$cotisation = 0;
																$service1 = 0;
																$service2 = 0;
																$service3 = 0;
																$service4mp = 0;
																$service4act = 0;
																$service4si = 0;
																$service3dns = 0;
																$service4bal = 0;
																$regul_mon = 0;
																$totalvs = 0;
																$detailusers = 0;

																date_default_timezone_set('Europe/Paris');

																$query = "SELECT A.*, C.* FROM " . $table_accounts . " A inner join " . $table_accounts_cstm . " C  on A.id = C.id_c where A.id='$idsimul'";
																$result = mysqli_query($link, $query);
																$num = mysqli_num_rows($result);
																$i = 0;

																$name = mysqli_result($result, $i, "name");
																$code_adh = mysqli_result($result, $i, "numero_adherent_collectivite_c");
																$id = mysqli_result($result, $i, "id");
																$adhesion_vs = mysqli_result($result, $i, "adhesion_vs_c");
																$compte = mysqli_result($result, $i, "name");
																$insee = mysqli_result($result, $i, "numero_adherent_collectivite_c");
																$type = mysqli_result($result, $i, "account_type");
																$etablissement = mysqli_result($result, $i, "nomcomplet_c");
																$adresse1 = mysqli_result($result, $i, "billing_address_street");
																$cp = mysqli_result($result, $i, "billing_address_postalcode");
																$commune = mysqli_result($result, $i, "billing_address_city");
																$tel = mysqli_result($result, $i, "phone_office");
																$fax = mysqli_result($result, $i, "phone_fax");
																$email = mysqli_result($result, $i, "email1");
																$site = mysqli_result($result, $i, "website");
																$population = mysqli_result($result, $i, "population_pair");
																$nbcommb = mysqli_result($result, $i, "nbr_communes_membres_c");
																$synccascias = mysqli_result($result, $i, "syndicat_ccas_cias_c");
																$nbagents = mysqli_result($result, $i, "nombre_agents_c");
																$nbagentscorrige = mysqli_result($result, $i, "nombre_agents_corrige_c");
																$s1adh = mysqli_result($result, $i, "s1_compte_c");
																$s2adh = mysqli_result($result, $i, "s2_compte_c");
																$s1nbserv = mysqli_result($result, $i, "nbr_serveurs_c");
																$s1nbpc = mysqli_result($result, $i, "nbr_postes_travail_c");
																$s1nbpcsec = mysqli_result($result, $i, "nbr_postes_security_c");
																$s2nbclas = mysqli_result($result, $i, "nbr_classes_c");
																$s2nbclassd = mysqli_result($result, $i, "nbr_classessd_c");
																$s3metier = mysqli_result($result, $i, "s3_compte_c");
																$s3demmp = mysqli_result($result, $i, "adherant_demat_marches_publics_c");
																$s3demat = mysqli_result($result, $i, "contrat_demat_actes_c");
																$s3siint = mysqli_result($result, $i, "site_web_heberge_vs_c");
																$s3outcol = mysqli_result($result, $i, "outils_collab_c");
																$s3balvs = mysqli_result($result, $i, "bal_vs_c");
																$s3afnic = mysqli_result($result, $i, "liste_dns_c");
																$s3bal = mysqli_result($result, $i, "afnic_balsup");
																$regul_txt = mysqli_result($result, $i, "adherent_regul_def");
																$regul_mon = mysqli_result($result, $i, "adherent_regul_mon");
																$quota_teles_q = mysqli_result($result, $i, "quota_teles_q");
																$quota_teles_u = mysqli_result($result, $i, "quota_teles_u");
																$s3sve = mysqli_result($result, $i, "sve_c");
																$utmcol = mysqli_result($result, $i, "nbr_utm_col");
																$utmeco = mysqli_result($result, $i, "nbr_utm_eco");
																$dpd_adhesion = mysqli_result($result, $i, "dpd_adh_c");
																$dpd_cnil = mysqli_result($result, $i, "dpd_cnil_c");

																//Requetes pour les contacts assistance m�tier usager
																$queryconts3mu = "SELECT D.*, E.* FROM " . $table_contacts . " D inner join " . $table_contacts_cstm . " E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='" . $id . "' && (E.usager_metier_c='usager_u')order by last_name asc";
																$resultconts3mu = mysqli_query($link, $queryconts3mu);
																$numconts3mu = mysqli_num_rows($resultconts3mu);

																//Requetes pour les contacts temps partag�s
																$querycontpart = "SELECT D.*, E.* FROM " . $table_contacts . " D inner join " . $table_contacts_cstm . " E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='" . $id . "' && E.usager_temppart_c=1 && (E.usager_metier_c<>'' || E.usager_demat_c<>'' || E.usager_mp_c<>'' || E.usager_si_c<>'' || E.usager_outcol_c<>'')order by last_name asc";
																$resultcontpart = mysqli_query($link, $querycontpart);
																$numcontpart = mysqli_num_rows($resultcontpart);

																//Requetes pour les contacts MP usager
																$queryconts3mpu = "SELECT D.*, E.* FROM " . $table_contacts . " D inner join " . $table_contacts_cstm . " E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='" . $id . "' && (E.usager_mp_c='usager_u')order by last_name asc";
																$resultconts3mpu = mysqli_query($link, $queryconts3mpu);
																$numconts3mpu = mysqli_num_rows($resultconts3mpu);

																//Requetes pour les contacts D�mat usager
																$queryconts3deu = "SELECT D.*, E.* FROM " . $table_contacts . " D inner join " . $table_contacts_cstm . " E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='" . $id . "' && (E.usager_demat_c='usager_u')order by last_name asc";
																$resultconts3deu = mysqli_query($link, $queryconts3deu);
																$numconts3deu = mysqli_num_rows($resultconts3deu);

																//Requetes pour les contacts Site Internet usager
																$queryconts3siu = "SELECT D.*, E.* FROM " . $table_contacts . " D inner join " . $table_contacts_cstm . " E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='" . $id . "' && (E.usager_si_c='usager_u')order by last_name asc";
																$resultconts3siu = mysqli_query($link, $queryconts3siu);
																$numconts3siu = mysqli_num_rows($resultconts3siu);

																//Requetes pour les contacts SVE usager
																$queryconts3sveu = "SELECT D.*, E.* FROM " . $table_contacts . " D inner join " . $table_contacts_cstm . " E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='" . $id . "' && (E.usager_sve_c='usager_u')order by last_name asc";
																$resultconts3sveu = mysqli_query($link, $queryconts3sveu);
																$numconts3sveu = mysqli_num_rows($resultconts3sveu);

																//Requetes pour les contacts outils collaboratifs
																$querycontcolu = "SELECT D.*, E.* FROM " . $table_contacts . " D inner join " . $table_contacts_cstm . " E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='" . $id . "' && ( E.usager_outcol_c='usager_u') && (SUBSTR(D.email1,1,8) <> 'contact@') order by last_name asc";
																$resultcontcolu = mysqli_query($link, $querycontcolu);
																$numcontcolu = mysqli_num_rows($resultcontcolu);

																//Requetes pour les contacts outils collaboratifs Service
																$querycontcolc = "SELECT D.*, E.* FROM " . $table_contacts . " D inner join " . $table_contacts_cstm . " E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='" . $id . "' && ( E.usager_outcol_c='usager_c') && (SUBSTR(D.email1,1,8) <> 'contact@') order by last_name asc";
																$resultcontcolc = mysqli_query($link, $querycontcolc);
																$numcontcolc = mysqli_num_rows($resultcontcolc);

																//Requetes pour les contacts BALs VS
																$queryconts3balu = "SELECT D.*, E.* FROM " . $table_contacts . " D inner join " . $table_contacts_cstm . " E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='" . $id . "' && ( E.usager_bas_vs_c='usager_u') && (SUBSTR(D.email1,1,8) <> 'contact@') order by last_name asc";
																$resultconts3balu = mysqli_query($link, $queryconts3balu);
																$numconts3balu = mysqli_num_rows($resultconts3balu);

																//Requetes pour les contacts BALs Zimbra 1Go
																$queryz1 = "SELECT D.*, E.* FROM " . $table_contacts . " D inner join " . $table_contacts_cstm . " E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='" . $id . "' && ( E.usager_outcol_c='usager_u') && ( E.usager_typebal_c='z1') order by last_name asc";
																$resultz1 = mysqli_query($link, $queryz1);
																$numz1 = mysqli_num_rows($resultz1);

																//Requetes pour les contacts BALs Zimbra 5Go
																$queryz5 = "SELECT D.*, E.* FROM " . $table_contacts . " D inner join " . $table_contacts_cstm . " E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='" . $id . "' && ( E.usager_outcol_c='usager_u') && ( E.usager_typebal_c='z5') order by last_name asc";
																$resultz5 = mysqli_query($link, $queryz5);
																$numz5 = mysqli_num_rows($resultz5);

																//Requetes pour les contacts BALs Zimbra 10Go
																$queryz10 = "SELECT D.*, E.* FROM " . $table_contacts . " D inner join " . $table_contacts_cstm . " E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='" . $id . "' && ( E.usager_outcol_c='usager_u') && ( E.usager_typebal_c='z10') order by last_name asc";
																$resultz10 = mysqli_query($link, $queryz10);
																$numz10 = mysqli_num_rows($resultz10);
																//Requetes pour les contacts BALs Zimbra 10Go Contact
																$queryz10c = "SELECT D.*, E.* FROM " . $table_contacts . " D inner join " . $table_contacts_cstm . " E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='" . $id . "' && ( E.usager_outcol_c='usager_u') && ( E.usager_typebal_c='z10i') order by last_name asc";
																$resultz10c = mysqli_query($link, $queryz10c);
																$numz10c = mysqli_num_rows($resultz10c);

																//Requetes pour les contacts BALs Namebay 2Go
																$queryb2 = "SELECT D.*, E.* FROM " . $table_contacts . " D inner join " . $table_contacts_cstm . " E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='" . $id . "' && ( E.usager_bas_vs_c='usager_u') && ( E.usager_typebal_c='b2') order by last_name asc";
																$resultb2 = mysqli_query($link, $queryb2);
																$numb2 = mysqli_num_rows($resultb2);

																//Requetes pour les contacts BALs Namebay 5Go
																$queryb5 = "SELECT D.*, E.* FROM " . $table_contacts . " D inner join " . $table_contacts_cstm . " E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='" . $id . "' && ( E.usager_bas_vs_c='usager_u') && ( E.usager_typebal_c='b5') order by last_name asc";
																$resultb5 = mysqli_query($link, $queryb5);
																$numb5 = mysqli_num_rows($resultb5);
																//Requetes pour les contacts BALs Namebay 5Go Contact
																$queryb5c = "SELECT D.*, E.* FROM " . $table_contacts . " D inner join " . $table_contacts_cstm . " E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='" . $id . "' && ( E.usager_bas_vs_c='usager_u') && ( E.usager_typebal_c='b5i') order by last_name asc";
																$resultb5c = mysqli_query($link, $queryb5c);
																$numb5c = mysqli_num_rows($resultb5c);

																//Requetes pour les contacts BALs Namebay 10Go
																$queryb10 = "SELECT D.*, E.* FROM " . $table_contacts . " D inner join " . $table_contacts_cstm . " E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='" . $id . "' && ( E.usager_bas_vs_c='usager_u') && ( E.usager_typebal_c='b10') order by last_name asc";
																$resultb10 = mysqli_query($link, $queryb10);
																$numb10 = mysqli_num_rows($resultb10);
																//Requetes pour les contacts BALs Namebay 10Go Contact
																$queryb10c = "SELECT D.*, E.* FROM " . $table_contacts . " D inner join " . $table_contacts_cstm . " E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='" . $id . "' && ( E.usager_bas_vs_c='usager_u') && ( E.usager_typebal_c='b10i') order by last_name asc";
																$resultb10c = mysqli_query($link, $queryb10c);
																$numb10c = mysqli_num_rows($resultb10c);

																//Requetes pour les contacts BALs Namebay 15Go
																$queryb15 = "SELECT D.*, E.* FROM " . $table_contacts . " D inner join " . $table_contacts_cstm . " E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='" . $id . "' && ( E.usager_bas_vs_c='usager_u') && ( E.usager_typebal_c='b15') order by last_name asc";
																$resultb15 = mysqli_query($link, $queryb15);
																$numb15 = mysqli_num_rows($resultb15);
																//Requetes pour les contacts BALs Namebay 15Go Contact
																$queryb15c = "SELECT D.*, E.* FROM " . $table_contacts . " D inner join " . $table_contacts_cstm . " E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='" . $id . "' && ( E.usager_bas_vs_c='usager_u') && ( E.usager_typebal_c='b15i') order by last_name asc";
																$resultb15c = mysqli_query($link, $queryb15c);
																$numb15c = mysqli_num_rows($resultb15c);

																//Requetes pour les contacts BALs Namebay 20Go
																$queryb20 = "SELECT D.*, E.* FROM " . $table_contacts . " D inner join " . $table_contacts_cstm . " E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='" . $id . "' && ( E.usager_bas_vs_c='usager_u') && ( E.usager_typebal_c='b20') order by last_name asc";
																$resultb20 = mysqli_query($link, $queryb20);
																$numb20 = mysqli_num_rows($resultb20);
																//Requetes pour les contacts BALs Namebay 20Go Contact
																$queryb20c = "SELECT D.*, E.* FROM " . $table_contacts . " D inner join " . $table_contacts_cstm . " E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='" . $id . "' && ( E.usager_bas_vs_c='usager_u') && ( E.usager_typebal_c='b20i') order by last_name asc";
																$resultb20c = mysqli_query($link, $queryb20c);
																$numb20c = mysqli_num_rows($resultb20c);

																$datedujour = date("d-m-Y");

																$cotisation = 0;
																$service1 = 0;
																$service2 = 0;
																$service3metier = 0;
																$service3demat = 0;
																$service3mp = 0;
																$service3sve = 0;
																$service3si = 0;
																$service3com = 0;
																$cotisationdpd = 0;
																$regul_mon = 0;

																//*************************************
																// Cotisation d'adh�sion
																//*************************************
																$filename = $grc_config['at86']['path'].'/adhesion2018.txt';
																$ligne = file($filename);
																$nbTotalLignes = count($ligne);
																$tab = array();
																for ($i = 0; $i < $nbTotalLignes; $i++) {
																	$ligneTab = explode("|", $ligne[$i]);
																	// Mappage du tableau
																	$tab[$ligneTab[1]]['compte'] = $ligneTab[0];
																	$tab[$ligneTab[1]]['numero'] = $ligneTab[1];
																	$tab[$ligneTab[1]]['adhvs'] = $ligneTab[5];
																	$tab[$ligneTab[1]]['adhatd'] = $ligneTab[6];
																}

																$cotisation = 0;
																$cotisation_new = 0;
																$couleuradh = 'black';

																if ($type == 'Commune') {
																	$cotisation = $cotis_type_1 * $population;
																	if ($cotisation > $cotis_plafond) {
																		$cotisation = $cotis_plafond;
																	}
																	if ($cotisation < $cotis_plancher) {
																		$cotisation = $cotis_plancher;
																	}
																	$tab[$insee]['adhvs'] = intval($tab[$insee]['adhvs']);
																	$tab[$insee]['adhatd'] = intval($tab[$insee]['adhatd']);
																	if ($cotisation < ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd'])) {
																		$cotisation_new = ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']) - ((($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']) - $cotisation) * $var_baisse);
																		$couleuradh = "green";
																	} else {
																		if ($cotisation > ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd'])) {
																			$cotisation_new = ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']) + (($cotisation - ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd'])) * $var_hausse);
																			$couleuradh = "red";
																		} else {
																			$cotisation_new = $cotisation;
																			$couleuradh = "blue";
																		}
																	}
																	if (($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']) == 0) {
																		$cotisation_new = $cotis_plancher;
																		$couleuradh = "red";
																	}
																}
																if ($type == "Communaute communes") {
																	$cotisation = $cotis_type_2;
																	$tab[$insee]['adhvs'] = intval($tab[$insee]['adhvs']);
																	$tab[$insee]['adhatd'] = intval($tab[$insee]['adhatd']);
																	if ($cotisation < ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd'])) {
																		$cotisation_new = ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']) - ((($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']) - $cotisation) * $var_baisse);
																		$couleuradh = "green";
																	} else {
																		if ($cotisation > ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd'])) {
																			$cotisation_new = ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']) + (($cotisation - ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd'])) * $var_hausse);
																			$couleuradh = "red";
																		} else {
																			$cotisation_new = $cotisation;
																			$couleuradh = "blue";
																		}
																	}
																	if (($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']) == 0) {
																		$cotisation_new = $cotis_type_2;
																		$couleuradh = "red";
																	}
																}
																if (($type == "Syndicat") || ($type == "EPAD-CCAS") || ($type == "ehpad")) {
																	// Calcul nombre agents r�el ou corrig�
																	if (($nbagentscorrige != $nbagents) && ($nbagentscorrige != 0)) {
																		$nbagents = $nbagentscorrige;
																	}

																	if ($nbagents < 11) {
																		$cotisation = $cotis_type_3_0;
																	}
																	if (($nbagents > 10) && ($nbagents < 31)) {
																		$cotisation = $cotis_type_3_1;
																	}
																	if ($nbagents > 30) {
																		$cotisation = $cotis_type_3_2;
																	}
																	$tab[$insee]['adhvs'] = intval($tab[$insee]['adhvs']);
																	$tab[$insee]['adhatd'] = intval($tab[$insee]['adhatd']);
																	if ($cotisation < ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd'])) {
																		$cotisation_new = ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']) - ((($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']) - $cotisation) * $var_baisse);
																		$couleuradh = "green";
																	} else {
																		if ($cotisation > ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd'])) {
																			$cotisation_new = ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']) + (($cotisation - ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd'])) * $var_hausse);
																			$couleuradh = "red";
																		} else {
																			$cotisation_new = ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']);
																			$couleuradh = "blue";
																		}
																	}
																	if (($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']) == 0) {
																		if ($nbagents < 11) {
																			$cotisation_new = $cotis_type_3_0;
																		}
																		if (($nbagents > 10) && ($nbagents < 31)) {
																			$cotisation_new = $cotis_type_3_1;
																		}
																		if ($nbagents > 30) {
																			$cotisation_new = $cotis_type_3_2;
																		}
																		$couleuradh = "red";
																	}
																}
																$cotisation = $cotisation_new;

																// On r�cup�re la cotisation n-1
																$cotisationa = 0;
                                                                $fileanneea = $grc_config['at86']['path']."/txt/facturation" . $anneea . "-" . $insee . ".txt";
                                                                if (file_exists($fileanneea)) {
                                                                    $contents = file_get_contents($fileanneea);
                                                                    $pattern = '';
                                                                    if (isset($searchfor) && $searchfor) {
                                                                        $pattern = preg_quote($searchfor, '/');
                                                                    }
                                                                    $pattern = "/^.*$pattern.*\$/m";

                                                                    if (preg_match_all($pattern, $contents, $matches)) {
																	    for ($mi = 0; $mi < sizeof($matches[0]); ++$mi) {
																		     $mip = explode("|", $matches[0][$mi]);
																		     if (($mip[11] == "Cotisation 2018") && ($mip[10] == "G")) {
																			     $cotisationa = number_format($mip[17], 2, '.', '');
																		     }
																	    }
																    }
                                                                }

																//*******************************************
																// Service Assistance Technique Collectivit�s
																//*******************************************
																if ($s1adh == "adhesion_oui") {
																	$service1 = 0;
																	$mat_sec = ($s1nbpcsec * $s1_uc_sec);
																	$mat_ser1 = ($s1nbserv * $s1_serv_mat) + ($s1nbpc * $s1_uc_mat);
																	$tot_mat = ($s1nbserv + $s1nbpc);
																	// Calcul UTM
																	if ($utmcol > 0) {
																		$tot_utmcol = $utmcol * $sx_utm;
																	} else {
																		$tot_utmcol = 0;
																	}
																	// Calcul T�l�-sauvegarde
																	//	if ($quota_teles_u > $quota_teles_q)
																	//	{
																	//	    $telesnb = ceil((($quota_teles_u - $quota_teles_q) / 1000) / 5 );
																	//		$telesauvegarde = $telesnb * $s1_uc_teles;
																	//	}
																	//	else
																	//	{
																	//	
																	//	}
																	// Calcul Global	
																	$service1 = number_format(($mat_ser1) + $mat_sec + $tot_utmcol + $telesauvegarde, 2, '.', '');
																}

																//*************************************
																// Service Assistance Technique �coles
																//*************************************
																if ($s2adh == "adhesion_oui") {
																	$service2 = 0;
																	$tot_cla = $s2nbclas + $s2nbclassd;
																	// Calcul UTM
																	if ($utmeco > 0) {
																		$tot_utmeco = $utmeco * $sx_utm;
																	} else {
																		$tot_utmeco = 0;
																	}

																	$adm_ser2_hr = ($s2_mat * ($s2nbclas + $s2nbclassd));
																	$service2 = number_format(($s2_mat * ($s2nbclas + $s2nbclassd)) + $tot_utmeco, 2, '.', '');
																}

																//****************************************
																// Service Assistance aux logiciels M�tier
																//****************************************
																if ($s3metier == "adhesion_oui") {
																	$service3metier = 0;
																	if ($type == "Commune") {
																		if ($population < 500) {
																			$service3metier = $s3_metier_forfait;
																		} else {
																			$service3metier = $s3_metier_type_1 * $population;
																			if ($service3metier > $s3_metier_plafond) {
																				$service3metier = $s3_metier_plafond;
																			}
																		}
																	}
																	if ($type == "Communaute communes") {
																		$service3metier = $s3_metier_type_2;
																	}
																	if (($type == "Syndicat") || ($type == "EPAD-CCAS") || ($type == "ehpad")) {
																		if ($nbagents < 11) {
																			$service3metier = $s3_metier_type_3_0;
																		}
																		if (($nbagents > 10) && ($nbagents < 31)) {
																			$service3metier = $s3_metier_type_3_1;
																		}
																		if ($nbagents > 30) {
																			$service3metier = $s3_metier_type_3_2;
																		}
																	}
																	$service3metier = $service3metier + ($s3_metier_user * $numconts3mu);
																}

																//*************************************
																// Service Assistance aux logiciels M�tier : logiciel unique
																//*************************************
																if ($s3metier == "adhesion_partielle") {
																	$service3metier = 0;
																	$service3metier = $s3_metier_forfait;
																	$service3metier = $service3metier + ($s3_metier_user * $numconts3mu);
																}

																//*************************************
																// D�mat�rialisation des proc�dures
																//*************************************
																if ($s3demat == "adhesion_oui") {
																	$service3demat = 0;
																	$service3dematqte = 0;
																	$service3dematunit = 0;

																	if ($type == "Commune") {
																		$service3demat = $s3_demat_type_1 * $population;
																		if ($service3demat > $s3_demat_plafond) {
																			$service3demat = $s3_demat_plafond;
																		}
																		$service3dematqte = $population;
																		$service3dematunit = $s3_demat_type_1;
																	}
																	if ($type == "Communaute communes") {
																		$service3demat = $s3_demat_type_2;
																		$service3dematqte = 1;
																		$service3dematunit = $s3_demat_type_2;
																	}
																	if (($type == "Syndicat") || ($type == "EPAD-CCAS") || ($type == "ehpad")) {
																		if ($nbagents < 11) {
																			$service3demat = $s3_demat_type_3_0;
																			$service3dematqte = 1;
																			$service3dematunit = $s3_demat_type_3_0;
																		}
																		if (($nbagents > 10) && ($nbagents < 31)) {
																			$service3demat = $s3_demat_type_3_1;
																			$service3dematqte = 1;
																			$service3dematunit = $s3_demat_type_3_1;
																		}
																		if ($nbagents > 30) {
																			$service3demat = $s3_demat_type_3_2;
																			$service3dematqte = 1;
																			$service3dematunit = $s3_demat_type_3_2;
																		}
																	}

																	$service3demat = $service3demat + ($s3_demat_user * $numconts3deu);
																}

																//*************************************
																// D�mat�rialisation des March�s Publics
																//*************************************
																if (($s3demmp == "adhesion_s4_mp_oui") || ($s3demmp == "adhesion_s4_mp_rattache")) {
																	$service3mp = 0;
																	$service3mpqte = 0;
																	$service3mpunit = 0;

																	if ($type == "Commune") {
																		$service3mp = $s3_mp_type_1 * $population;
																		if ($service3mp > $s3_mp_plafond) {
																			$service3mp = $s3_mp_plafond;
																		}
																		if ($service3mp < $s3_mp_plancher) {
																			$service3mp = $s3_mp_plancher;
																		}
																		$service3mpqte = $population;
																		$service3mpunit = $s3_mp_type_1;
																	}
																	if ($type == "Communaute communes") {
																		$service3mp = $s3_mp_type_2;
																		$service3mpqte = 1;
																		$service3mpunit = $s3_mp_type_2;
																	}
																	if (($type == "Syndicat") || ($type == "EPAD-CCAS") || ($type == "ehpad")) {
																		if ($nbagents < 11) {
																			$service3mp = $s3_mp_type_3_0;
																			$service3mpqte = 1;
																			$service3mpunit = $s3_mp_type_3_0;
																		}
																		if (($nbagents > 10) && ($nbagents < 31)) {
																			$service3mp = $s3_mp_type_3_1;
																			$service3mpqte = 1;
																			$service3mpunit = $s3_mp_type_3_1;
																		}
																		if ($nbagents > 30) {
																			$service3mp = $s3_mp_type_3_2;
																			$service3mpqte = 1;
																			$service3mpunit = $s3_mp_type_3_2;
																		}
																	}
																	$service3mp = $service3mp + ($s3_mp_user * $numconts3mpu);
																}

																//*************************************
																// Site Internet
																//*************************************
																if ($s3siint == "adhesion_oui") {
																	$service3si = 0;
																	$service3siqte = 0;
																	$service3siunit = 0;

																	if ($type == "Commune") {
																		$service3si = $s3_si_type_1 * $population;
																		if ($service3si > $s3_si_plafond) {
																			$service3si = $s3_si_plafond;
																		}
																		$service3siqte = $population;
																		$service3siunit = $s3_si_type_1;
																	}
																	if ($type == "Communaute communes") {
																		$service3si = $s3_si_type_2;
																		$service3siqte = $population;
																		$service3siunit = $s3_si_type_2;
																	}
																	if (($type == "Syndicat") || ($type == "EPAD-CCAS") || ($type == "ehpad")) {
																		if ($nbagents < 11) {
																			$service3si = $s3_si_type_3_0;
																			$service3siqte = 1;
																			$service3siunit = $s3_si_type_3_0;
																		}
																		if (($nbagents > 10) && ($nbagents < 31)) {
																			$service3si = $s3_si_type_3_1;
																			$service3siqte = 1;
																			$service3siunit = $s3_si_type_3_1;
																		}
																		if ($nbagents > 30) {
																			$service3si = $s3_si_type_3_2;
																			$service3siqte = 1;
																			$service3siunit = $s3_si_type_3_2;
																		}
																	}
																	$service3si = $service3si + ($s3_si_user * $numconts3siu);
																}

																//*************************************
																// Saisine par Voie �lectronique
																//*************************************
																if ($s3sve == "adhesion_oui") {
																	$service3sve = 0;
																	$service3sveqte = 0;
																	$service3sveunit = 0;

																	if ($type == "Commune") {
																		$service3sve = $s3_sve_type_1 * $population;
																		if ($service3sve > $s3_sve_plafond) {
																			$service3sve = $s3_sve_plafond;
																		}
																		$service3sveqte = $population;
																		$service3sveunit = $s3_sve_type_1;
																	}
																	if ($type == "Communaute communes") {
																		$service3sve = $s3_sve_type_2;
																		$service3sveqte = $population;
																		$service3sveunit = $s3_sve_type_2;
																	}
																	if (($type == "Syndicat") || ($type == "EPAD-CCAS") || ($type == "ehpad")) {
																		if ($nbagents < 11) {
																			$service3sve = $s3_sve_type_3_0;
																			$service3sveqte = 1;
																			$service3sveunit = $s3_sve_type_3_0;
																		}
																		if (($nbagents > 10) && ($nbagents < 31)) {
																			$service3sve = $s3_sve_type_3_1;
																			$service3sveqte = 1;
																			$service3sveunit = $s3_sve_type_3_1;
																		}
																		if ($nbagents > 30) {
																			$service3sve = $s3_sve_type_3_2;
																			$service3sveqte = 1;
																			$service3sveunit = $s3_sve_type_3_2;
																		}
																	}
																	$service3sve = $service3sve + ($s3_sve_user * $numconts3sveu);
																}

																//*************************************
																// Messagerie - Nom de domaine
																//*************************************
																// Nom de domaine
																//*************************************
																$nb_lignes_dns = count(explode("\n", $s3afnic));
																$service3com = 0;
																if (($nb_lignes_dns - 1) > 0) {
																	$service3dns = 0;
																	$service3dns = $s3_dns * $nb_lignes_dns;
																	$service3dnsqte = ($nb_lignes_dns - 1);
																	$service3dnsunit = $s3_dns;
																	$service3dns = $service3dnsqte * $service3dnsunit;
																	$service3com = $service3com + $service3dns;
																}
																//*************************************
																// Service 3 Outils Collaboratifs et BAL VS
																//*************************************
																if (($s3outcol == "adhesion_oui") || ($s3balvs == "Oui")) {
																	if ($s3outcol == "adhesion_oui") {
																		// Zimbra 1Go
																		if ($numz1 > 0) {
																			$service3com = $service3com + ($numz1 * $s3_outcol_bal_1go);
																		}
																		// Zimbra 5Go
																		if ($numz5 > 0) {
																			$service3com = $service3com + ($numz5 * $s3_outcol_bal_5go);
																		}
																		// Zimbra 10Go
																		if ($numz10 > 0) {
																			$service3com = $service3com + ($numz10 * $s3_outcol_bal_10go);
																		}
																		// Zimbra 10Go Contact
																		if ($numz10c > 0) {
																			$service3com = $service3com + ($numz10c * $s3_outcol_depass);
																		}
																	}
																	//$service3balu = $numconts3balu * $s3_bal;
																	//if (($s3balvs == "Oui") && ($numconts3balu > 0))
																	if ($s3balvs == "Oui") {
																		//BAL 2Go
																		if ($numb2 > 0) {
																			$service3com = $service3com + ($numb2 * $s3_bal_2go);
																		}
																		//BAL 5Go
																		if ($numb5 > 0) {
																			$service3com = $service3com + ($numb5 * $s3_bal_5go);
																		}
																		//BAL 5Go Contact
																		if ($numb5c > 0) {
																			$service3com = $service3com + ($numb5c * $s3_bal_depass);
																		}
																		//BAL 10Go
																		if ($numb10 > 0) {
																			$service3com = $service3com + ($numb10 * $s3_bal_10go);
																		}
																		//BAL 10Go Contact
																		if ($numb10c > 0) {
																			$service3com = $service3com + ($numb10c * $s3_bal_depass * 2);
																		}
																		//BAL 15Go
																		if ($numb15 > 0) {
																			$service3com = $service3com + ($numb15 * $s3_bal_15go);
																		}
																		//BAL 15Go Contact
																		if ($numb15c > 0) {
																			$service3com = $service3com + ($numb15c * $s3_bal_depass * 3);
																		}
																		//BAL 20Go
																		if ($numb20 > 0) {
																			$service3com = $service3com + ($numb20 * $s3_bal_20go);
																		}
																		//BAL 20Go Contact
																		if ($numb20c > 0) {
																			$service3com = $service3com + ($numb20c * $s3_bal_depass * 4);
																		}
																	}
																}

																//*************************************
																// DPD
																//*************************************
																if ($dpd_adhesion == "adhesion_oui") {
																	$cotisationdpd = 0;
																	if ($type == 'Commune') {
																		$cotisationdpd = $dpd_base * $population;
																		if ($cotisationdpd > $dpd_plafond) {
																			$cotisationdpd = $dpd_plafond;
																		}
																		if ($cotisationdpd < $dpd_plancher) {
																			$cotisationdpd = $dpd_plancher;
																		}
																	}
																	if ($type == 'Communaute communes') {
																		$cotisationdpd = $dpd_epci;
																	}
																	if (($type <> 'Commune') && ($type <> 'Communaute communes')) {
																		if ($nbagents < 11) {
																			$cotisationdpd = $dpd_etp_0;
																		}
																		if (($nbagents > 10) && ($nbagents < 31)) {
																			$cotisationdpd = $dpd_etp_11;
																		}
																		if ($nbagents > 30) {
																			$cotisationdpd = $dpd_etp_31;
																		}
																	}
																}

																//*************************************
																// R�gularisation
																//*************************************
																if ($regul_mon <> 0) {
																	$regul_mon = $regul_mon;
																} else {
																	$regul_mon = 0;
																}

																//*************************************
																// ligne des totaux
																//*************************************
																$totalvs = $cotisation + $service1 + $service2 + $service3metier + $service3demat + $service3mp +  $service3sve + $service3si + $service3com + $cotisationdpd + $regul_mon;

																$Rtotadh2017 += ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']);
																$Rtotadha += $cotisationa;
																$Rtotadhesion += $cotisation;
																$Rtots1 += $service1;
																$Rtots2 += $service2;
																$Rtots3MET += $service3metier;
																$Rtots3DEMAT += $service3demat;
																$Rtots2MP += $service3mp;
																$Rtots3SI += $service3si;
																$Rtots3MESS += $service3com;
																$Rtots3SVE += $service3sve;
																$Rtotregul += $regul_mon;
																$Rtotdpd += $cotisationdpd;
																$Rtottotal += $totalvs;
																//<td>" . number_format($tab[$insee]['adhvs'] + $tab[$insee]['adhatd'], 2, '.', '') . "</td><td>" . number_format($cotisationa, 2, '.', '') . "</td>
																echo "<tr><td>" . $insee . "</td><td>" . $compte . "</td><td><font color=$couleuradh>" . number_format($cotisation, 2, '.', '') . "</font></td><td>" . number_format($service1, 2, '.', '') . "</td><td>" . number_format($service2, 2, '.', '') . "</td><td>" . number_format($service3metier, 2, '.', '') . "</td><td>" . number_format($service3demat, 2, '.', '') . "</td><td>" . number_format($service3mp, 2, '.', '') . "</td><td>" . number_format($service3si, 2, '.', '') . "</td><td>" . number_format($service3com, 2, '.', '') . "</td><td>" . number_format($service3sve, 2, '.', '') . "</td><td>" . number_format($regul_mon, 2, '.', '') . "</td><td>" . number_format($cotisationdpd, 2, '.', '') . "</td><td>" . number_format($totalvs, 2, '.', '') . "</td></tr>";

																//$chainerecap=$insee."|".$compte."|".$type."|".number_format($cotisation,2,'.','')."|".number_format($service1,2,'.','')."|".number_format($service2,2,'.','')."|".number_format($service3metier,2,'.','')."|".number_format($service3demat,2,'.','')."|".number_format($service3mp,2,'.','')."|".number_format($service3si,2,'.','')."|".number_format($service3usersmescol,2,'.','')."|".number_format($service3sve,2,'.','')."|".number_format($service3dns,2,'.','')."|".number_format($service4bal,2,'.','')."|".number_format($cotisationdpd,2,'.','')."|".number_format($regul_mon,2,'.','')."|".number_format($totalvs,2,'.','');

																//echo $chainerecap."<br>";
																////////////////////////////////////////////////////////////////////////////////
																// FIN   SIMULATION                                                           //
																////////////////////////////////////////////////////////////////////////////////
																//echo "Simulation OK !";
															}
														}
														// Fin de la simultation par collectivit�			
														//	echo "</div></div>";
														// Passage � la collectivit� suivante
														$z++;
													}
													mysqli_close($link);
														//<td>" . number_format($Rtotadh2017, 2, '.', '') . "</td><td>" . number_format($Rtotadha, 2, '.', '') . "</td>
													echo "<tr><td>Total</td><td>&nbsp;</td><td>" . number_format($Rtotadhesion, 2, '.', '') . "</td><td>" . number_format($Rtots1, 2, '.', '') . "</td><td>" . number_format($Rtots2, 2, '.', '') . "</td><td>" . number_format($Rtots3MET, 2, '.', '') . "</td><td>" . number_format($Rtots3DEMAT, 2, '.', '') . "</td><td>" . number_format($Rtots2MP, 2, '.', '') . "</td><td>" . number_format($Rtots3SI, 2, '.', '') . "</td><td>" . number_format($Rtots3MESS, 2, '.', '') . "</td><td>" . number_format($Rtots3SVE, 2, '.', '') . "</td><td>" . number_format($Rtotregul, 2, '.', '') . "</td><td>" . number_format($Rtotdpd, 2, '.', '') . "</td><td>" . number_format($Rtottotal, 2, '.', '') . "</td></tr>";
													echo "<tr><td><b>Insee</b></td><td><b>" . utf8_encode(Collectivite) . "</b></td><td><b>Adh</b></td><td><b>S1</b></td><td><b>S2</b></td><td><b>S3MET</b></td><td><b>S3DEMAT</b></td><td><b>S3MP</b></td><td><b>S3SI</b></td><td><b>S3MESS</b></td><td><b>S3SVE</b></td><td><b>Regul</b></td><td><b>DPD</b></td><td><b>Total</b></td></tr>";
													//<td><b>Adh 2017</b></td><td><b>Adh 2018</b></td>
													?>
 											</tbody>
 										</table>
 									</div>
 								</div>
 							</div>
 						</div>
 					</div><!-- /.box-body -->
 				</div><!-- /.box -->
 		</div><!-- /.col -->
 	</div><!-- /.row -->
 	</section><!-- /.content -->
 	</div><!-- /.content-wrapper -->
 	<?php include('include/footer.php'); ?>