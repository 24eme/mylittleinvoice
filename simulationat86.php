<?php
include('include/head.php');
global $bd;
global $link;
global $lang;
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
           <i class="fa fa-cubes"></i> <?php echo $lang['global_invoicing']; ?>
                <small></small>   <a href="new-customer.php" class="btn btn-success btn-xs"><i class="fa fa-user-plus"></i> <?php echo $lang['add']; ?></a>
<a href="#" class="btn btn-info btn-xs newquote" style="opacity: 0.4;"><i class="fa fa-plus-square-o"></i> <?php echo $lang['quote']; ?></a>
<a href="#" class="btn btn-warning btn-xs newinvoice" style="opacity: 0.4;"><i class="fa fa-plus-square"></i> <?php echo $lang['invoice']; ?></a>
            
          </h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><i class="fa fa-cubes"></i> <?php echo $lang['global_invoicing']; ?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-body">
<pre>
<?php
$name_fac=$name_facturation;
$ligne_article=0;
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

function mysqli_result($res,$row,$col){
    $numrows = mysqli_num_rows($res);
    if ($numrows && $row <= ($numrows-1) && $row >=0){
        mysqli_data_seek($res,$row);
        $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
        if (isset($resrow[$col])){
            return $resrow[$col];
        }
    }
    return false;
}


date_default_timezone_set('Europe/Paris');

if ($_GET['etab'] <> '')
{
	$idsimul = $_GET['etab'];
}
else
{
	$idsimul = $idsession;
}
$query = "SELECT A.*, C.* FROM ".$table_accounts. " A inner join ".$table_accounts_cstm." C  on A.id = C.id_c where A.id='$idsimul'";
//$query = "SELECT * FROM ".$table." where id='$idsimul'";
$result = mysqli_query($link,$query);
$num = mysqli_num_rows ($result);

$i = 0;

$name = mysqli_result($result,$i,"name");
$code_adh = mysqli_result($result,$i,"numero_adherent_collectivite_c");		
$id = mysqli_result($result,$i,"id");
$adhesion_vs = mysqli_result($result,$i,"adhesion_vs_c");
$compte = mysqli_result($result,$i,"name");
$insee = mysqli_result($result,$i,"numero_adherent_collectivite_c");
$type = mysqli_result($result,$i,"account_type");
$etablissement = utf8_encode(mysqli_result($result,$i,"nomcomplet_c"));
$adresse1 = utf8_encode(mysqli_result($result,$i,"billing_address_street"));
$cp = mysqli_result($result,$i,"billing_address_postalcode");
$commune = utf8_encode(mysqli_result($result,$i,"billing_address_city"));
$tel = mysqli_result($result,$i,"phone_office");
$fax = mysqli_result($result,$i,"phone_fax");
$email = mysqli_result($result,$i,"email1");
$site = mysqli_result($result,$i,"website");
$population = mysqli_result($result,$i,intval(date('Y'))% 2 == 0 ?"population_pair":"population_impair");
$nbcommb = mysqli_result($result,$i,"nbr_communes_membres_c");
$synccascias = mysqli_result($result,$i,"syndicat_ccas_cias_c");
$nbagents = mysqli_result($result,$i,"nombre_agents_corrige_c");
$s1adh = mysqli_result($result,$i,"s1_compte_c");
$s2adh = mysqli_result($result,$i,"s2_compte_c");
$s1nbserv = mysqli_result($result,$i,"nbr_serveurs_c");
$s1nbpc = mysqli_result($result,$i,"nbr_postes_travail_c");
$s1nbpcsec = mysqli_result($result,$i,"nbr_postes_security_c");
$s2nbclas = mysqli_result($result,$i,"nbr_classes_c");
$s2nbclassd = mysqli_result($result,$i,"nbr_classessd_c");
$s3metier = mysqli_result($result,$i,"s3_compte_c");
$s3demmp = mysqli_result($result,$i,"adherant_demat_marches_publics_c");
$s3demat = mysqli_result($result,$i,"contrat_demat_actes_c");
$s3siint = mysqli_result($result,$i,"site_web_heberge_vs_c");
$s3outcol = mysqli_result($result,$i,"outils_collab_c");
$s3balvs = mysqli_result($result,$i,"bal_vs_c");				
$s3afnic = mysqli_result($result,$i,"liste_dns_c");
$s3bal = mysqli_result($result,$i,"afnic_balsup");
$regul_txt = utf8_encode(mysqli_result($result,$i,"adherent_regul_def"));
$regul_mon = mysqli_result($result,$i,"adherent_regul_mon");
$quota_teles_q = mysqli_result($result,$i,"quota_teles_q");
$quota_teles_u = mysqli_result($result,$i,"quota_teles_u");
$s3sve = mysqli_result($result,$i,"sve_c");
$utmcol = mysqli_result($result,$i,"nbr_utm_col");
$utmeco = mysqli_result($result,$i,"nbr_utm_eco");
$dpd_adhesion = mysqli_result($result,$i,"dpd_adh_c");
$dpd_cnil = mysqli_result($result,$i,"dpd_cnil_c");

//Requetes pour les contacts assistance métier usager
$queryconts3mu = "SELECT D.*, E.* FROM ".$table_contacts. " D inner join ".$table_contacts_cstm." E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='".$id."' && (E.usager_metier_c='usager_u')order by last_name asc";
$resultconts3mu = mysqli_query($link,$queryconts3mu);
$numconts3mu = mysqli_num_rows($resultconts3mu);

//Requetes pour les contacts temps partagés
$querycontpart = "SELECT D.*, E.* FROM ".$table_contacts. " D inner join ".$table_contacts_cstm." E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='".$id."' && E.usager_temppart_c=1 && (E.usager_metier_c<>'' || E.usager_demat_c<>'' || E.usager_mp_c<>'' || E.usager_si_c<>'' || E.usager_outcol_c<>'')order by last_name asc";
$resultcontpart = mysqli_query($link,$querycontpart);
$numcontpart = mysqli_num_rows($resultcontpart);

//Requetes pour les contacts MP usager
$queryconts3mpu = "SELECT D.*, E.* FROM ".$table_contacts. " D inner join ".$table_contacts_cstm." E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='".$id."' && (E.usager_mp_c='usager_u')order by last_name asc";
$resultconts3mpu = mysqli_query($link,$queryconts3mpu);
$numconts3mpu = mysqli_num_rows($resultconts3mpu);

//Requetes pour les contacts Démat usager
$queryconts3deu = "SELECT D.*, E.* FROM ".$table_contacts. " D inner join ".$table_contacts_cstm." E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='".$id."' && (E.usager_demat_c='usager_u')order by last_name asc";
$resultconts3deu = mysqli_query($link,$queryconts3deu);
$numconts3deu = mysqli_num_rows($resultconts3deu);

//Requetes pour les contacts Site Internet usager
$queryconts3siu = "SELECT D.*, E.* FROM ".$table_contacts. " D inner join ".$table_contacts_cstm." E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='".$id."' && (E.usager_si_c='usager_u')order by last_name asc";
$resultconts3siu = mysqli_query($link,$queryconts3siu);
$numconts3siu = mysqli_num_rows($resultconts3siu);

//Requetes pour les contacts SVE usager
$queryconts3sveu = "SELECT D.*, E.* FROM ".$table_contacts. " D inner join ".$table_contacts_cstm." E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='".$id."' && (E.usager_sve_c='usager_u')order by last_name asc";
$resultconts3sveu = mysqli_query($link,$queryconts3sveu);
$numconts3sveu = mysqli_num_rows($resultconts3sveu);

//Requetes pour les contacts outils collaboratifs
$querycontcolu = "SELECT D.*, E.* FROM ".$table_contacts. " D inner join ".$table_contacts_cstm." E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='".$id."' && ( E.usager_outcol_c='usager_u') && (SUBSTR(D.email1,1,8) <> 'contact@') order by last_name asc";
$resultcontcolu = mysqli_query($link,$querycontcolu);
$numcontcolu = mysqli_num_rows($resultcontcolu);

//Requetes pour les contacts outils collaboratifs Service
$querycontcolc = "SELECT D.*, E.* FROM ".$table_contacts. " D inner join ".$table_contacts_cstm." E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='".$id."' && ( E.usager_outcol_c='usager_c') && (SUBSTR(D.email1,1,8) <> 'contact@') order by last_name asc";
$resultcontcolc = mysqli_query($link,$querycontcolc);
$numcontcolc = mysqli_num_rows($resultcontcolc);

//Requetes pour les contacts BALs VS
$queryconts3balu = "SELECT D.*, E.* FROM ".$table_contacts. " D inner join ".$table_contacts_cstm." E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='".$id."' && ( E.usager_bas_vs_c='usager_u') && (SUBSTR(D.email1,1,8) <> 'contact@') order by last_name asc";
$resultconts3balu = mysqli_query($link,$queryconts3balu);
$numconts3balu = mysqli_num_rows($resultconts3balu);

//Requetes pour les contacts BALs Zimbra 1Go
$queryz1 = "SELECT D.*, E.* FROM ".$table_contacts. " D inner join ".$table_contacts_cstm." E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='".$id."' && ( E.usager_outcol_c='usager_u') && ( E.usager_typebal_c='z1') order by last_name asc";
$resultz1 = mysqli_query($link,$queryz1);
$numz1 = mysqli_num_rows($resultz1);

//Requetes pour les contacts BALs Zimbra 5Go
$queryz5 = "SELECT D.*, E.* FROM ".$table_contacts. " D inner join ".$table_contacts_cstm." E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='".$id."' && ( E.usager_outcol_c='usager_u') && ( E.usager_typebal_c='z5') order by last_name asc";
$resultz5 = mysqli_query($link,$queryz5);
$numz5 = mysqli_num_rows($resultz5);

//Requetes pour les contacts BALs Zimbra 10Go
$queryz10 = "SELECT D.*, E.* FROM ".$table_contacts. " D inner join ".$table_contacts_cstm." E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='".$id."' && ( E.usager_outcol_c='usager_u') && ( E.usager_typebal_c='z10') order by last_name asc";
$resultz10 = mysqli_query($link,$queryz10);
$numz10 = mysqli_num_rows($resultz10);
//Requetes pour les contacts BALs Zimbra 10Go Contact
$queryz10c = "SELECT D.*, E.* FROM ".$table_contacts. " D inner join ".$table_contacts_cstm." E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='".$id."' && ( E.usager_outcol_c='usager_u') && ( E.usager_typebal_c='z10i') order by last_name asc";
$resultz10c = mysqli_query($link,$queryz10c);
$numz10c = mysqli_num_rows($resultz10c);

//Requetes pour les contacts BALs Namebay 2Go
$queryb2 = "SELECT D.*, E.* FROM ".$table_contacts. " D inner join ".$table_contacts_cstm." E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='".$id."' && ( E.usager_bas_vs_c='usager_u') && ( E.usager_typebal_c='b2') order by last_name asc";
$resultb2 = mysqli_query($link,$queryb2);
$numb2 = mysqli_num_rows($resultb2);

//Requetes pour les contacts BALs Namebay 5Go
$queryb5 = "SELECT D.*, E.* FROM ".$table_contacts. " D inner join ".$table_contacts_cstm." E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='".$id."' && ( E.usager_bas_vs_c='usager_u') && ( E.usager_typebal_c='b5') order by last_name asc";
$resultb5 = mysqli_query($link,$queryb5);
$numb5 = mysqli_num_rows($resultb5);
//Requetes pour les contacts BALs Namebay 5Go Contact
$queryb5c = "SELECT D.*, E.* FROM ".$table_contacts. " D inner join ".$table_contacts_cstm." E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='".$id."' && ( E.usager_bas_vs_c='usager_u') && ( E.usager_typebal_c='b5i') order by last_name asc";
//$queryb5c = "SELECT D.*, E.* FROM ".$table_contacts. " D inner join ".$table_contacts_cstm." E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='".$id."' && ( E.usager_bas_vs_c='usager_u') && ( E.usager_typebal_c='b5i') && (SUBSTR(D.email1,1,8)='contact@') order by last_name asc";
$resultb5c = mysqli_query($link,$queryb5c);
$numb5c = mysqli_num_rows($resultb5c);

//Requetes pour les contacts BALs Namebay 10Go
$queryb10 = "SELECT D.*, E.* FROM ".$table_contacts. " D inner join ".$table_contacts_cstm." E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='".$id."' && ( E.usager_bas_vs_c='usager_u') && ( E.usager_typebal_c='b10') order by last_name asc";
$resultb10 = mysqli_query($link,$queryb10);
$numb10 = mysqli_num_rows($resultb10);
//Requetes pour les contacts BALs Namebay 10Go Contact
$queryb10c = "SELECT D.*, E.* FROM ".$table_contacts. " D inner join ".$table_contacts_cstm." E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='".$id."' && ( E.usager_bas_vs_c='usager_u') && ( E.usager_typebal_c='b10i') order by last_name asc";
$resultb10c = mysqli_query($link,$queryb10c);
$numb10c = mysqli_num_rows($resultb10c);

//Requetes pour les contacts BALs Namebay 15Go
$queryb15 = "SELECT D.*, E.* FROM ".$table_contacts. " D inner join ".$table_contacts_cstm." E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='".$id."' && ( E.usager_bas_vs_c='usager_u') && ( E.usager_typebal_c='b15') order by last_name asc";
$resultb15 = mysqli_query($link,$queryb15);
$numb15 = mysqli_num_rows($resultb15);
//Requetes pour les contacts BALs Namebay 15Go Contact
$queryb15c = "SELECT D.*, E.* FROM ".$table_contacts. " D inner join ".$table_contacts_cstm." E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='".$id."' && ( E.usager_bas_vs_c='usager_u') && ( E.usager_typebal_c='b15i') order by last_name asc";
$resultb15c = mysqli_query($link,$queryb15c);
$numb15c = mysqli_num_rows($resultb15c);

//Requetes pour les contacts BALs Namebay 20Go
$queryb20 = "SELECT D.*, E.* FROM ".$table_contacts. " D inner join ".$table_contacts_cstm." E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='".$id."' && ( E.usager_bas_vs_c='usager_u') && ( E.usager_typebal_c='b20') order by last_name asc";
$resultb20 = mysqli_query($link,$queryb20);
$numb20 = mysqli_num_rows($resultb20);
//Requetes pour les contacts BALs Namebay 20Go Contact
$queryb20c = "SELECT D.*, E.* FROM ".$table_contacts. " D inner join ".$table_contacts_cstm." E on D.id = E.id_c where D.deleted='0' && D.primary_account_id='".$id."' && ( E.usager_bas_vs_c='usager_u') && ( E.usager_typebal_c='b20i') order by last_name asc";
$resultb20c = mysqli_query($link,$queryb20c);
$numb20c = mysqli_num_rows($resultb20c);

// On réinitialise le fichier pour l'import dans le SI en le supprimant
//$ficlog="../custom/import/invoices/facturation".$annee."-".$insee.".txt";
$ficlog=$grc_config['at86']['path']."/txt/facturation".$annee."-".$insee.".txt";
$ficrecap=$grc_config['at86']['path']."/recap/facturation".$annee."-".$insee.".txt";

if( file_exists ($ficlog)){
	unlink( $ficlog ) ;
}
if( file_exists ($ficrecap)){
	unlink( $ficrecap ) ;
}

// On initialise la première ligne d'entête du fichier d'export
$fp = fopen ("$ficlog","a+");
$chaine="Code Adhérent|Compte|Adresse|CP|Commune|Date Facture|Date Paiement|Montant Total|Fichier pdf|Assigné à|Type Ligne|Groupe|Désignation|Descriptif|Qté|PU|QtéxPU|Total Groupe\r\n";
// On écrit dans le fichier d'export la chaine
$ligne = fputs($fp,iconv("UTF-8", "WINDOWS-1252", $chaine));
fclose($fp);

mysqli_close($link);

$datedujour = date("d-m-Y");
//$datedujour = ("24-02-2020");

//*************************************
// Cotisation d'adhésion
//*************************************
$filename = $grc_config['at86']['path'].'/adhesion2018.txt';
$ligne= file($filename);
$nbTotalLignes=count($ligne);
$tab = array();
for($i=0;$i<$nbTotalLignes;$i++){
	$ligneTab = explode("|", $ligne[$i]);
	// Mappage du tableau
	$tab[$ligneTab[1]]['compte']=$ligneTab[0];
	$tab[$ligneTab[1]]['numero']=$ligneTab[1];
	$tab[$ligneTab[1]]['adhvs']=$ligneTab[5];
	$tab[$ligneTab[1]]['adhatd']=$ligneTab[6];
}

$cotisation = 0;
$cotisation_new = 0;
$texte = '';
$texte0 = '';
$texte1 = '';
$texte2 = '';
$texte3 = '';
$texte4 = '';
$lissage_bas = 0;
$lissage_haut = 0;

if ($type == 'Commune') {
	$cotisation = $cotis_type_1 * $population;
	if ($cotisation > $cotis_plafond) {
		$cotisation = $cotis_plafond;
	}
	if ($cotisation < $cotis_plancher) {
		$cotisation = $cotis_plancher;
	}
	$adhunit = $cotis_type_1;
	$quantite = $population;

        if (($tab[$insee]['adhvs']+$tab[$insee]['adhatd']) == 0)
        {
		$cotisation_new = $cotisation;
                $tab[$insee]['adhvs'] = $cotisation;
        }
	elseif ($cotisation < ($tab[$insee]['adhvs']+$tab[$insee]['adhatd']))
	{
		$cotisation_new = ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']) - ((($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']) - $cotisation) * $var_baisse);
		$lissage_bas = (($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']) - $cotisation) * $var_baisse ;
	}
	else 
	{
                $cotisation_new = ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']) + (( $cotisation - ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd'])) *$var_hausse );
		$lissage_haut = ($cotisation - ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd'])) * $var_hausse;
	}

	$texte1 = "Selon conditions tarifaires et population totale $annee : $population habitants";
}

if ($type == "Communaute communes") {
	$cotisation = $cotis_type_2;
        if (($tab[$insee]['adhvs']+$tab[$insee]['adhatd']) == 0)
        {
                $cotisation_new = $cotisation;
                $tab[$insee]['adhvs'] = $cotisation;
        }
	elseif ($cotisation < ($tab[$insee]['adhvs']+$tab[$insee]['adhatd']))
	{
		$cotisation_new = ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']) - ((($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']) - $cotisation) * $var_baisse);
		$lissage_bas = (($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']) - $cotisation) * $var_baisse;
	}
	else 
	{
		$cotisation_new = (($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']) + (( $cotisation - ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd'])) * $var_hausse)); 
		$lissage_haut = ($cotisation - ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd'])) * $var_hausse;
	}
	$texte1 = "Selon conditions tarifaires, forfait annuel EPCI";
}

if (($type == "Syndicat") || ($type == "EPAD-CCAS") || ($type == "ehpad")) 
{
	if ($nbagents < 11)
	{
		$cotisation = $cotis_type_3_0;
	}	
	if (($nbagents > 10) && ($nbagents < 31))
	{
		$cotisation = $cotis_type_3_1;
	}
	if ($nbagents > 30)
	{
		$cotisation = $cotis_type_3_2;
	}
        if (($tab[$insee]['adhvs']+$tab[$insee]['adhatd']) == 0)
        {
                $cotisation_new = $cotisation;
                $tab[$insee]['adhvs'] = $cotisation;
        }
	elseif ($cotisation < ($tab[$insee]['adhvs']+$tab[$insee]['adhatd']))
	{
		$cotisation_new = ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']) - ((($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']) - $cotisation) * $var_baisse);
		$lissage_bas = (($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']) - $cotisation) * $var_baisse;
	}
	else 
	{
		$cotisation_new = ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd']) + (( $cotisation - ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd'])) * $var_hausse); 
		$lissage_haut = ($cotisation - ($tab[$insee]['adhvs'] + $tab[$insee]['adhatd'])) * $var_hausse;
	}
	$texte1 = "Selon conditions tarifaires et nombre d'ETP, Équivalent Temps Plein de la collectivité";
}

//************************************************************
// Lignes de facturation adhésion pour intégration dans le CRM
//************************************************************
$order = array("\r\n", "\n", "\r");
$replace = '';
$texte1 = str_replace($order, $replace, $texte1);
$texte0 = "Cotisation d'adhésion AT86";
$phase2[$ligne_article]="A|".$texte0."|Adhésion obligatoire pour bénéficier des services de l'AT86|".$texte1."|1|".number_format($cotisation_new,2,'.','')."|".number_format($cotisation_new,2,'.','')."|";
$ligne_article = $ligne_article + 1;

$phase2[$ligne_article]="G|".$texte0."|Total Adhésion|||||".number_format($cotisation_new,2,'.','');
$ligne_article = $ligne_article + 1;

$cotisation = $cotisation_new ;
//*************************************

//*******************************************
// Service Assistance Technique Collectivités
//*******************************************
if ($s1adh == "adhesion_oui")
{
	$service1 = 0;
	$mat_sec = ($s1nbpcsec * $s1_uc_sec);
	$mat_ser1 = ($s1nbserv * $s1_serv_mat) + ($s1nbpc * $s1_uc_mat);
	$tot_mat = ($s1nbserv + $s1nbpc);
// Calcul UTM
        if ($utmcol > 0)
        {
            $tot_utmcol = $utmcol*$sx_utm;
        }
// Calcul Télé-sauvegarde
	if ($quota_teles_u > $quota_teles_q)
	{
	    $telesnb = ceil((($quota_teles_u - $quota_teles_q) / 1000) / 5 );
		$telesauvegarde = $telesnb * $s1_uc_teles;
	}
	else
	{
	
	}
// Calcul Global	
	$service1 = number_format(($mat_ser1) + $mat_sec + $tot_utmcol + $telesauvegarde,2,'.','');
//*************************************
//Lignes de facturation adhésion pour intégration dans le CRM
//*************************************
	$texte0 = "Assistance technique Collectivités";
    if ($s1nbpc > 0)
	{
		$phase2[$ligne_article]="A|".$texte0."|Poste de travail|Forfait $s1_uc_mat EUR par poste|$s1nbpc|$s1_uc_mat|".$s1nbpc*$s1_uc_mat."|";
		$ligne_article = $ligne_article + 1;
	}
	if ($s1nbpcsec > 0)
	{
		$phase2[$ligne_article]="A|".$texte0."|Pack Antivirus|Forfait $s1_uc_sec EUR par pack Antivirus|$s1nbpcsec|$s1_uc_sec|".$s1nbpcsec*$s1_uc_sec."|";
		$ligne_article = $ligne_article + 1;
	}
        if ($s1nbserv > 0)
	{
		$phase2[$ligne_article]="A|".$texte0."|Serveur|Forfait $s1_serv_mat EUR par serveur|$s1nbserv|$s1_serv_mat|".$s1nbserv*$s1_serv_mat."|";
		$ligne_article = $ligne_article + 1;
	}
        if ($utmcol > 0)
	{
		$phase2[$ligne_article]="A|".$texte0."|UTM (Sécurisation accès réseau)|Forfait $sx_utm EUR par UTM|$utmcol|$sx_utm|".$utmcol*$sx_utm."|";
		$ligne_article = $ligne_article + 1;
	}
	if ($quota_teles_u > $quota_teles_q)
	{
		$phase2[$ligne_article]="A|".$texte0."|Service 1 - Dépassement de télé-sauvegarde|$s1_uc_teles EUR par tranche de 5 Go (Consommation de ". ($quota_teles_u/1000)." Go sur ". ($quota_teles_q/1000)." Go autorisés)|$telesnb|$s1_uc_teles|".$telesauvegarde."|";
		$ligne_article = $ligne_article + 1;
	}
	$phase2[$ligne_article]="G|".$texte0."|Total du Service|||||$service1";
	$ligne_article = $ligne_article + 1;
}
//*************************************

//*************************************
// Service Assistance Technique Écoles
//*************************************
if ($s2adh == "adhesion_oui")
{
	$service2 = 0;
	$tot_cla = $s2nbclas + $s2nbclassd;
// Calcul UTM
        if ($utmeco > 0)
        {
            $tot_utmeco = $utmeco*$sx_utm;
        }

	$adm_ser2_hr = ($s2_mat * ($s2nbclas + $s2nbclassd));
	$service2 = number_format(($s2_mat * ($s2nbclas + $s2nbclassd)) + $tot_utmeco,2,'.','');
 	
//*************************************
//Lignes de facturation adhésion pour intégration dans le CRM
//*************************************
	$texte0 = "Assistance technique Écoles";
    if ($s2nbclas > 0) {
        $calrems21 = 0;
	$calrems21 = $s2_mat * $s2nbclas ;
        $phase2[$ligne_article]="A|".$texte0."|Gestion du parc informatique|Forfait de $s2_mat EUR par classe en mode Poste à Poste|$s2nbclas|$s2_mat|$calrems21|";
		$ligne_article = $ligne_article + 1;
    }
    if ($s2nbclassd > 0) {
        $calrems22 = 0;
	$calrems22 = $s2_mat * $s2nbclassd ;
        $phase2[$ligne_article]="A|".$texte0."|Gestion du parc informatique|Forfait de $s2_mat EUR par classe avec Serveur dédié|$s2nbclassd|$s2_mat|$calrems22|";
        $ligne_article = $ligne_article + 1;
    }
    if ($utmeco > 0)
    {
            $phase2[$ligne_article]="A|".$texte0."|UTM (Sécurisation accès réseau)|Forfait $sx_utm EUR par UTM|$utmeco|$sx_utm|".$utmeco*$sx_utm."|";
            $ligne_article = $ligne_article + 1;
    }

    $phase2[$ligne_article]="G|".$texte0."|Total du Service|||||$service2";
    $ligne_article = $ligne_article + 1;
}
//*************************************

//****************************************
// Service Assistance aux logiciels Métier
//****************************************
if ($s3metier == "adhesion_oui")
{
	$service3metier = 0;
	$service3metierqte=0;
	$service3metierunit=0;

	if ($type == "Commune") {
		if ($population < 500) {
			$service3metier = $s3_metier_forfait;
			$s3metierdesignation = "Assistance aux logiciels métiers \nForfait de ".$s3_metier_forfait." EUR (Commune < 500 hab.)";
			$service3metierqte=1;
			$service3metierunit=$s3_metier_forfait;
		}
		else 
		{
			$service3metier = $s3_metier_type_1 * $population;
			if ($service3metier > $s3_metier_plafond) {
				$service3metier = $s3_metier_plafond;
			}
			$s3metierdesignation = "Assistance aux logiciels métiers \nForfait de ".$s3_metier_type_1." EUR / hab. plafonné à ".$s3_metier_plafond." EUR";
			$service3metierqte=$population;
			$service3metierunit=$s3_metier_type_1;
		}
	}
	if ($type == "Communaute communes") {
		$service3metier = $s3_metier_type_2;
		$service3metierqte=1;
		$service3metierunit=$s3_metier_type_2;
		$s3metierdesignation = "Assistance aux logiciels métiers \nForfait de ".$s3_metier_type_2." EUR";
	}
	if (($type == "Syndicat") || ($type == "EPAD-CCAS") || ($type == "ehpad"))
	{
		if ($nbagents < 11) {
			$service3metier = $s3_metier_type_3_0;
			$s3metierdesignation = "Assistance aux logiciels métiers \nForfait annuel tranche 0 à 10 ETP : ".$s3_metier_type_3_0." EUR";
			$texte = "Total du Service";
			$service3metierqte=1;
			$service3metierunit=$s3_metier_type_3_0;
		}
		if (($nbagents > 10) && ($nbagents < 31)){
			$service3metier = $s3_metier_type_3_1;
			$s3metierdesignation = "Assistance aux logiciels métiers \nForfait annuel tranche 11 à 30 ETP : ".$s3_metier_type_3_1." EUR";
			$service3metierqte=1;
			$service3metierunit=$s3_metier_type_3_1;
		}
		if ($nbagents > 30){
			$service3metier = $s3_metier_type_3_2;
			$s3metierdesignation = "Assistance aux logiciels métiers \nForfait annuel tranche > 30 ETP : ".$s3_metier_type_3_2." EUR";
			$service3metierqte=1;
			$service3metierunit=$s3_metier_type_3_2;
		}
	}

//*************************************
//Lignes de facturation adhésion pour intégration dans le CRM
//*************************************	
	$texte0 = "Assistance aux logiciels métiers";
	$s3desig = explode("\n", $s3metierdesignation);

	$phase2[$ligne_article]="A|".$texte0."|".$s3desig[0]."|".$s3desig[1]."|$service3metierqte|$service3metierunit|$service3metier|";
	$ligne_article = $ligne_article + 1;

	$phase2[$ligne_article]="A|".$texte0."|Accompagnement des utilisateurs aux logiciels métiers|Accompagnement de $numconts3mu usager(s)|$numconts3mu|$s3_metier_user|". $s3_metier_user*$numconts3mu ."|";
	$ligne_article = $ligne_article + 1;

	$service3metier = $service3metier + ($s3_metier_user*$numconts3mu);

	$phase2[$ligne_article]="G|".$texte0."|Total du Service|||||$service3metier";
	$ligne_article = $ligne_article + 1;
	
}
//*************************************

//*************************************
// Service Assistance aux logiciels Métier : logiciel unique
//*************************************
if ($s3metier == "adhesion_partielle")
{
	$service3metier = 0;
	$service3metierqte=1;
	$service3metierunit=0;

	$service3metier = $s3_metier_forfait;
	
	$service3metierunit=$s3_metier_forfait;
	$s3metierdesignation = "Assistance aux logiciels métiers : Logiciel métier Unique";
//*************************************
//Lignes de facturation adhésion pour intégration dans le CRM
//*************************************	
	$s3desig = explode("\n", $s3metierdesignation);
	$texte0 = "Assistance aux logiciels métiers";
	$phase2[$ligne_article]="A|".$texte0."|".$s3desig[0]."|".$s3desig[1]."|$service3metierqte|$service3metierunit|$service3metier|";
	$ligne_article = $ligne_article + 1;

	$phase2[$ligne_article]="A|".$texte0."|Logiciels métiers - Accompagnement des utilisateurs|Accompagnement de $numconts3mu usager(s)|$numconts3mu|$s3_metier_user|". $s3_metier_user*$numconts3mu ."|";
    $ligne_article = $ligne_article + 1;

	$service3metier = $service3metier + ($s3_metier_user*$numconts3mu);

	$phase2[$ligne_article]="G|".$texte0."|Total du Service|||||$service3metier";
	$ligne_article = $ligne_article + 1;
}
//*************************************

//*************************************
// Dématérialisation des procédures
//*************************************
if ($s3demat == "adhesion_oui") 
{
	$service3demat = 0;
	$service3dematqte=0;
	$service3dematunit=0;

	if ($type == "Commune") {
		$service3demat = $s3_demat_type_1 * $population;
		if ($service3demat > $s3_demat_plafond) {
			$service3demat = $s3_demat_plafond;
		}
		$s3dematdesignation = "Forfait de ".$s3_demat_type_1." EUR / hab. plafonné à ".$s3_demat_plafond." EUR";
		$service3dematqte=$population;
		$service3dematunit=$s3_demat_type_1;
	}
	if ($type == "Communaute communes") {
		$service3demat = $s3_demat_type_2;
		
		$service3dematqte=1;
		$service3dematunit=$s3_demat_type_2;
		$s3dematdesignation = "Forfait de ".$s3_demat_type_2." EUR";
	}
	if (($type == "Syndicat") || ($type == "EPAD-CCAS") || ($type == "ehpad"))
	{
		if ($nbagents < 11) {
			$service3demat = $s3_demat_type_3_0;
			$s3dematdesignation = "Dématérialisation des procédures \nForfait annuel tranche 0 à 10 ETP : ".$s3_demat_type_3_0." EUR";
			$service3dematqte=1;
			$service3dematunit=$s3_demat_type_3_0;
		}
		if (($nbagents > 10) && ($nbagents < 31)){
			$service3demat = $s3_demat_type_3_1;
			$s3dematdesignation = "Dématérialisation des procédures \nForfait annuel tranche 11 à 30 ETP : ".$s3_demat_type_3_1." EUR";
			$service3dematqte=1;
			$service3dematunit=$s3_demat_type_3_1;
		}
		if ($nbagents > 30) {
			$service3demat = $s3_demat_type_3_2;
			$s3dematdesignation = "Dématérialisation des procédures \nForfait annuel tranche > 30 ETP : ".$s3_demat_type_3_2." EUR";
			$service3dematqte=1;
			$service3dematunit=$s3_demat_type_3_2;
		}
	}

//*************************************
//Lignes de facturation adhésion pour intégration dans le CRM
//*************************************
    $order = array("\r\n", "\n", "\r");
    $replace = '';
    $s3desigact = str_replace($order, $replace, $s3dematdesignation);
    $texte0 = "Tiers de télétransmissions";
    $phase2[$ligne_article]="A|".$texte0."|Dématérialisation des procédures|".$s3desigact."|$service3dematqte|$service3dematunit|$service3demat|";
    $ligne_article = $ligne_article + 1;

	$phase2[$ligne_article]="A|".$texte0."|Dématérialisation des procédures - Accompagnement des utilisateurs|Accompagnement de $numconts3deu usager(s)|$numconts3deu|$s3_demat_user|". $s3_demat_user*$numconts3deu ."|";
    $ligne_article = $ligne_article + 1;

	//Cout global du S3 DEMAT
	$service3demat = $service3demat + ($s3_demat_user*$numconts3deu);
    $phase2[$ligne_article]="G|".$texte0."|Total du Service|||||$service3demat";
    $ligne_article = $ligne_article + 1;
}
//*************************************

//*************************************
// Dématérialisation des Marchés Publics
//*************************************
if (($s3demmp == "adhesion_s4_mp_oui") || ($s3demmp == "adhesion_s4_mp_rattache"))
{
	$service3mp = 0;
	$service3mpqte=0;
	$service3mpunit=0;

	if ($type == "Commune") {
		$service3mp = $s3_mp_type_1 * $population;
		if ($service3mp > $s3_mp_plafond) {
			$service3mp = $s3_mp_plafond;
		}
		if ($service3mp < $s3_mp_plancher) {
			$service3mp = $s3_mp_plancher;
		}
		$s3mpdesignation = "Forfait de ".$s3_mp_type_1." EUR / hab. plancher à ".$s3_mp_plancher." EUR et plafonné à ".$s3_mp_plafond." EUR";
		$service3mpqte=$population;
		$service3mpunit=$s3_mp_type_1;
	}
	if ($type == "Communaute communes") {
		$service3mp = $s3_mp_type_2;
		$service3mpqte=1;
		$service3mpunit=$s3_mp_type_2;
		$s3mpdesignation = "Forfait de ".$s3_mp_type_2." EUR";
	}
	if (($type == "Syndicat") || ($type == "EPAD-CCAS") || ($type == "ehpad"))
	{
		if ($nbagents < 11) {
			$service3mp = $s3_mp_type_3_0;
			$s3mpdesignation = "Dématérialisation des Marchés Publics, forfait annuel tranche 0 à 10 ETP : ".$s3_mp_type_3_0." EUR";

			$service3mpqte=1;
			$service3mpunit=$s3_mp_type_3_0;
		}
		if (($nbagents > 10) && ($nbagents < 31)){
			$service3mp = $s3_mp_type_3_1;
			$s3mpdesignation = "Dématérialisation des Marchés Publics, forfait annuel tranche 11 à 30 ETP : ".$s3_mp_type_3_1." EUR";
			$service3mpqte=1;
			$service3mpunit=$s3_mp_type_3_1;
		}
		if ($nbagents > 30) {
			$service3mp = $s3_mp_type_3_2;
			$s3mpdesignation = "Dématérialisation des Marchés Publics, forfait annuel tranche > 30 ETP : ".$s3_mp_type_3_2." EUR";
			$service3mpqte=1;
			$service3mpunit=$s3_mp_type_3_2;
		}
	}
//*************************************
//Lignes de facturation adhésion pour intégration dans le CRM
//*************************************
	$texte0 = "Dématérialisation des marchés publics";
    $phase2[$ligne_article]="A|".$texte0."|Dématérialisation des Marchés Publics|".$s3mpdesignation."|$service3mpqte|$service3mpunit|$service3mp|";
    $ligne_article = $ligne_article + 1;

    $phase2[$ligne_article]="A|".$texte0."|Dématérialisation des Marchés Publics - Accompagnement des utilisateurs|Accompagnement de $numconts3mpu usager(s)|$numconts3mpu|$s3_mp_user|". $s3_mp_user*$numconts3mpu ."|";
    $ligne_article = $ligne_article + 1;

	//Cout global du S3 MP
	$service3mp = $service3mp + ($s3_mp_user*$numconts3mpu);
    $phase2[$ligne_article]="G|".$texte0."|Total du Service|||||$service3mp";
    $ligne_article = $ligne_article + 1;
}
//*************************************

//*************************************
// Site Internet
//*************************************
if ($s3siint == "adhesion_oui")
{
	$service3si = 0;
	$service3siqte=0;
	$service3siunit=0;

	if ($type == "Commune") {
		$service3si = $s3_si_type_1 * $population;
		if ($service3si > $s3_si_plafond) {
			$service3si = $s3_si_plafond;
		}
		$s3sidesignation = "Site Internet \nForfait de ".$s3_si_type_1." EUR / hab. plafonné à ".$s3_si_plafond." EUR";
		$service3siqte=$population;
		$service3siunit=$s3_si_type_1;
	}
	if ($type == "Communaute communes") {
		$service3si = $s3_si_type_2;
		$service3siqte=$population;
		$service3siunit=$s3_si_type_2;
		$s3sidesignation = "Site Internet \nForfait de ".$s3_si_type_2." EUR";
	}
	if (($type == "Syndicat") || ($type == "EPAD-CCAS") || ($type == "ehpad"))
	{
		if ($nbagents < 11) 
		{
			$service3si = $s3_si_type_3_0;
			$s3sidesignation = "Site Internet \nForfait annuel tranche 0 à 10 ETP : ".$s3_si_type_3_0." EUR";
			$service3siqte=1;
			$service3siunit=$s3_si_type_3_0;
		}
		if (($nbagents > 10) && ($nbagents < 31))
		{
			$service3si = $s3_si_type_3_1;
			$s3sidesignation = "Site Internet \nForfait annuel tranche 11 à 30 ETP : ".$s3_si_type_3_1." EUR";
			$service3siqte=1;
			$service3siunit=$s3_si_type_3_1;
		}
		if ($nbagents > 30) 
		{
			$service3si = $s3_si_type_3_2;
			$s3sidesignation = "Site Internet \nForfait annuel tranche > 30 ETP : ".$s3_si_type_3_2." EUR";
			$service3siqte=1;
			$service3siunit=$s3_si_type_3_2;
		}
	}
//*************************************
//Lignes de facturation adhésion pour intégration dans le CRM
//*************************************
	$order = array("\r\n", "\n", "\r");
	$replace = '';
	$s3desigsi = str_replace($order, $replace, $s3sidesignation);
	$texte0 = "Sites internet abonnement";
	$phase2[$ligne_article]="A|".$texte0."|Site Internet|".$s3desigsi."|$service3siqte|$service3siunit|$service3si|";
	$ligne_article = $ligne_article + 1;

	$phase2[$ligne_article]="A|".$texte0."|Site Internet - Accompagnement des utilisateurs|Accompagnement de $numconts3siu usager(s) à l'ensemble des fonctionnalités|$numconts3siu|$s3_si_user|". $s3_si_user*$numconts3siu ."|";
    $ligne_article = $ligne_article + 1;

	//Cout global du S3 SI
	$service3si = $service3si + ($s3_si_user*$numconts3siu);
	$phase2[$ligne_article]="G|".$texte0."|Total du Service|||||$service3si";
	$ligne_article = $ligne_article + 1;
}
//*************************************

//*************************************
// Saisine par Voie électronique
//*************************************
if ($s3sve == "adhesion_oui")
{
	$service3sve = 0;
	$service3sveqte=0;
	$service3sveunit=0;

	if ($type == "Commune") {
		$service3sve = $s3_sve_type_1 * $population;
		if ($service3sve > $s3_sve_plafond) {
			$service3sve = $s3_sve_plafond;
		}
		$s3svedesignation = "Saisine par Voie électronique \nForfait de ".$s3_sve_type_1." EUR / hab. plafonné à ".$s3_sve_plafond." EUR";
		$service3sveqte=$population;
		$service3sveunit=$s3_sve_type_1;
	}
	if ($type == "Communaute communes") {
		$service3sve = $s3_sve_type_2;
		$service3sveqte=$population;
		$service3sveunit=$s3_sve_type_2;
		$s3svedesignation = "Saisine par Voie électronique \nForfait de ".$s3_sve_type_2." EUR";
	}
	if (($type == "Syndicat") || ($type == "EPAD-CCAS") || ($type == "ehpad"))
	{
		if ($nbagents < 11) 
		{
			$service3sve = $s3_sve_type_3_0;
			$s3svedesignation = "Saisine par Voie électronique \nForfait annuel tranche 0 à 10 ETP : ".$s3_sve_type_3_0." EUR";
			$service3sveqte=1;
			$service3sveunit=$s3_sve_type_3_0;
		}
		if (($nbagents > 10) && ($nbagents < 31))
		{
			$service3sve = $s3_sve_type_3_1;
			$s3svedesignation = "Saisine par Voie électronique \nForfait annuel tranche 11 à 30 ETP : ".$s3_sve_type_3_1." EUR";
			$service3sveqte=1;
			$service3sveunit=$s3_sve_type_3_1;
		}
		if ($nbagents > 30) 
		{
			$service3sve = $s3_sve_type_3_2;
			$s3svedesignation = "Saisine par Voie électronique \nForfait annuel tranche > 30 ETP : ".$s3_sve_type_3_2." EUR";
			$service3sveqte=1;
			$service3sveunit=$s3_sve_type_3_2;
		}
	}
//*************************************
//Lignes de facturation adhésion pour intégration dans le CRM
//*************************************
	$order = array("\r\n", "\n", "\r");
	$replace = '';
	$s3desigsve = str_replace($order, $replace, $s3svedesignation);
	$texte0 = "Saisine par voie électronique (SVE)";
	$phase2[$ligne_article]="A|".$texte0."|Saisine par voie électronique|".$s3desigsve."|$service3sveqte|$service3sveunit|$service3sve|";
	$ligne_article = $ligne_article + 1;

	$phase2[$ligne_article]="A|".$texte0."|SVE - Accompagnement des utilisateurs|Accompagnement de $numconts3sveu usager(s)|$numconts3sveu|$s3_sve_user|". $s3_sve_user*$numconts3sveu ."|";
    $ligne_article = $ligne_article + 1;

	//Cout global du S3 SI
	$service3sve = $service3sve + ($s3_sve_user*$numconts3sveu);
	$phase2[$ligne_article]="G|".$texte0."|Total du Service|||||$service3sve";
	$ligne_article = $ligne_article + 1;
}
//*************************************
// DPD
//*************************************
if ($dpd_adhesion == "adhesion_oui")
{
$cotisationdpd = 0;
if ($type == 'Commune')
{
        $cotisationdpd = $dpd_base * $population;
        if ($cotisationdpd > $dpd_plafond) {
                $cotisationdpd = $dpd_plafond;
        }
        if ($cotisationdpd < $dpd_plancher) {
                $cotisationdpd = $dpd_plancher;
        }
        $dpdqte = $population;
        $dpdunit = $dpd_base;
        $dpddesignation = "Forfait de ".$dpd_base." EUR / hab. plancher à ".$dpd_plancher." EUR et plafonné à ".$dpd_plafond." EUR";
}
if ($type == 'Communaute communes')
{
        $cotisationdpd = $dpd_epci;
        $dpdqte = 1;
        $dpdunit = $dpd_epci;
        $dpddesignation = "Forfait EPCI de ".$dpd_epci." EUR";
}
if (($type <> 'Commune') && ($type <> 'Communaute communes'))
{
        if ($nbagents < 11)
        {
                $cotisationdpd = $dpd_etp_0;
		$dpdqte = 1;
	        $dpdunit = $dpd_dpd_etp_0;
                $dpddesignation = "Forfait annuel tranche 0 à 10 ETP de ".$dpd_dpd_etp_0." EUR";
        }
        if (($nbagents > 10) && ($nbagents <31))
        {
                $cotisationdpd = $dpd_etp_11;
		$dpdqte = 1;
	        $dpdunit = $dpd_dpd_etp_11;
                $dpddesignation = "Forfait annuel tranche 11 à 30 ETP de ".$dpd_dpd_etp_11." EUR";

        }
        if ($nbagents > 30)
        {
                $cotisationdpd = $dpd_etp_31;
		$dpdqte = 1;
	        $dpdunit = $dpd_dpd_etp_31;
                $dpddesignation = "Forfait annuel tranche > 30 ETP de ".$dpd_dpd_etp_31." EUR";
        }
}

//*************************************
//Lignes de facturation adhésion pour intégration dans le CRM
//*************************************
        $order = array("\r\n", "\n", "\r");
        $replace = '';
        $desigdpd = str_replace($order, $replace, $dpddesignation);
	$texte0 = "Délégué à la protection des données personnelles";
        $phase2[$ligne_article]="A|".$texte0."|Délégué à la protection des Données|".$desigdpd."|$dpdqte|$dpdunit|$cotisationdpd|";
        $ligne_article = $ligne_article + 1;

        //Cout global du Service DPD
        $servicedpd = $servicedpd;
        $phase2[$ligne_article]="G|".$texte0."|Total du Service|||||$cotisationdpd";
        $ligne_article = $ligne_article + 1;
}

//*************************************

//*************************************
// Messagerie - Nom de domaine
//*************************************
// Nom de domaine
//*************************************
$nb_lignes_dns = count (explode ("\n", $s3afnic));
$service3com = 0;
$texte0 = "Outils de messagerie électronique";
if (($nb_lignes_dns-1) > 0)
{
	$service3dns = 0;
	$service3dns = $s3_dns * $nb_lignes_dns;

	$s3dnsdesignation = "Nom de domaine - Tarif annuel par nom de domaine en sus de celui intégré dans l'adhésion";
	$service3dnsqte=($nb_lignes_dns-1);
	$service3dnsunit=$s3_dns;
	$service3dns = $service3dnsqte * $service3dnsunit;

	$s3dnsdesig = explode("\n", $s3dnsdesignation);

	$phase2[$ligne_article]="A|".$texte0."|Nom de domaine|".$s3dnsdesig[0]."|$service3dnsqte|$service3dnsunit|$service3dns|";
	$ligne_article = $ligne_article + 1;
	$service3com = $service3com + $service3dns;
}
//*************************************
// Service 3 Outils Collaboratifs et BAL VS
//*************************************
if (($s3outcol == "adhesion_oui") || ($s3balvs == "Oui"))
{
		if ($s3outcol == "adhesion_oui")
		{
			// Zimbra 1Go
			if ($numz1 > 0)
			{
				$phase2[$ligne_article]="A|".$texte0."|Outils Collaboratifs|Boites courriels Zimbra type 1Go|$numz1|$s3_outcol_bal_1go|".($s3_outcol_bal_1go * $numz1)."|";
				$ligne_article = $ligne_article + 1;
				$service3com = $service3com + ($numz1 * $s3_outcol_bal_1go);
			}
			// Zimbra 5Go
			if ($numz5 > 0)
			{
				$phase2[$ligne_article]="A|".$texte0."|Outils Collaboratifs|Boites courriels Zimbra type 5Go|$numz5|$s3_outcol_bal_5go|".($s3_outcol_bal_5go * $numz5)."|";
				$ligne_article = $ligne_article + 1;
				$service3com = $service3com + ($numz5 * $s3_outcol_bal_5go);
			}
			// Zimbra 10Go
			if ($numz10 > 0)
			{
				$phase2[$ligne_article]="A|".$texte0."|Outils Collaboratifs|Boites courriels Zimbra type 10Go|$numz10|$s3_outcol_bal_10go|".($s3_outcol_bal_10go * $numz10)."|";
				$ligne_article = $ligne_article + 1;
				$service3com = $service3com + ($numz10 * $s3_outcol_bal_10go);
			}
			// Zimbra 10Go Contact
			if ($numz10c > 0)
			{
				$phase2[$ligne_article]="A|".$texte0."|Outils Collaboratifs|Dépassement boite institutionnelle Zimbra type 10Go|$numz10c|$s3_outcol_depass|".($s3_outcol_depass * $numz10c)."|";
				$ligne_article = $ligne_article + 1;
				$service3com = $service3com + ($numz10c * $s3_outcol_depass);
			}
		}
		$service3balu = $numconts3balu * $s3_bal;
		if ($s3balvs == "Oui")
		{
			//BAL 2Go
                        if ($numb2 > 0)
			{
				$phase2[$ligne_article]="A|".$texte0."|Outil de Messagerie BALs simples|Boites courriels simples type 2Go|$numb2|$s3_bal_2go|".($s3_bal_2go * $numb2)."|";
				$ligne_article = $ligne_article + 1;
				$service3com = $service3com + ($numb2 * $s3_bal_2go);
			}
			//BAL 5Go
			if ($numb5 > 0)
			{
				$phase2[$ligne_article]="A|".$texte0."|Outil de Messagerie BALs simples|Boites courriels simples type 5Go|$numb5|$s3_bal_5go|".($s3_bal_5go * $numb5)."|";
				$ligne_article = $ligne_article + 1;
				$service3com = $service3com + ($numb5 * $s3_bal_5go);
			}
			//BAL 5Go Contact
			if ($numb5c > 0)
			{
				$phase2[$ligne_article]="A|".$texte0."|Outil de Messagerie BALs simples|Dépassement boite institutionnelle type 5Go|$numb5c|$s3_bal_depass|".($s3_bal_depass * $numb5c)."|";
				$ligne_article = $ligne_article + 1;
				$service3com = $service3com + ($numb5c * $s3_bal_depass);
			}
			//BAL 10Go
			if ($numb10 > 0)
			{
				$phase2[$ligne_article]="A|".$texte0."|Outil de Messagerie BALs simples|Boites courriels simples type 10Go|$numb10|$s3_bal_10go|".($s3_bal_10go * $numb10)."|";
				$ligne_article = $ligne_article + 1;
				$service3com = $service3com + ($numb10 * $s3_bal_10go);
			}
			//BAL 10Go Contact
			if ($numb10c > 0)
			{
				$phase2[$ligne_article]="A|".$texte0."|Outil de Messagerie BALs simples|Dépassement boite institutionnelle type 10Go|$numb10c|".($s3_bal_depass * 2)."|".($s3_bal_depass * $numb10c * 2)."|";
				$ligne_article = $ligne_article + 1;
				$service3com = $service3com + ($numb10c * $s3_bal_depass * 2);
			}
			//BAL 15Go
			if ($numb15 > 0)
			{
				$phase2[$ligne_article]="A|".$texte0."|Outil de Messagerie BALs simples|Boites courriels simples type 15Go|$numb15|$s3_bal_15go|".($s3_bal_15go * $numb15)."|";
				$ligne_article = $ligne_article + 1;
				$service3com = $service3com + ($numb15 * $s3_bal_15go);
			}
			//BAL 15Go Contact
			if ($numb15c > 0)
			{
				$phase2[$ligne_article]="A|".$texte0."|Outil de Messagerie BALs simples|Dépassement boite institutionnelle type 15Go|$numb15c|".($s3_bal_depass * 3)."|".($s3_bal_depass * $numb15c * 3)."|";
				$ligne_article = $ligne_article + 1;
				$service3com = $service3com + ($numb15c * $s3_bal_depass * 3);
			}
			//BAL 20Go
			if ($numb20 > 0)
			{
				$phase2[$ligne_article]="A|".$texte0."|Outil de Messagerie BALs simples|Boites courriels simples type 20Go|$numb20|$s3_bal_20go|".($s3_bal_20go * $numb20)."|";
				$ligne_article = $ligne_article + 1;
				$service3com = $service3com + ($numb20 * $s3_bal_20go);
			}
			//BAL 20Go Contact
			if ($numb20c > 0)
			{
				$phase2[$ligne_article]="A|".$texte0."|Outil de Messagerie BALs simples|Dépassement boite institutionnelle type 20Go|$numb20c|".($s3_bal_depass * 4)."|".($s3_bal_depass * $numb20c * 4)."|";
				$ligne_article = $ligne_article + 1;
				$service3com = $service3com + ($numb20c * $s3_bal_depass * 4);
			}
		}
}
//*************************************
// Total du Groupement Messagerie et DNS
//*************************************
if ($service3com > 0)
{
	$phase2[$ligne_article]="G|".$texte0."|Total du Service|||||$service3com";
	$ligne_article = $ligne_article + 1;
}
//*************************************

//*************************************
// Régularisation
//*************************************
if ($regul_mon <> 0)
{
	$texte0 = "Régularisation";
	//Lignes de facturation REGUL pour intégration dans le CRM
	$phase2[$ligne_article]="A|".$texte0."|Régularisation Facturation|$regul_txt|1|$regul_mon|$regul_mon|";
	$ligne_article = $ligne_article + 1;

	$phase2[$ligne_article]="G|".$texte0."|Régularisation Facturation|||||$regul_mon";
	$ligne_article = $ligne_article + 1;
}
else
{
	$regul_mon =0;
}
//*************************************

//*************************************
// ligne des totaux
//*************************************
$totalvs = $cotisation + $service1 + $service2 + $service3metier + $service3demat + $service3mp +  $service3sve + $cotisationdpd +$service3si + $service3com + $regul_mon;
$phase2[$ligne_article]="T|Total|Total Facturation|||||".number_format($totalvs, 2, ',', '')."";
$ligne_article = $ligne_article + 1;
//*************************************


$fprecap = fopen ("$ficrecap","a+"); 
$chainerecap=$insee."|".$compte."|".$type."|".number_format($cotisation,2,'.','')."|".number_format($service1,2,'.','')."|".number_format($service2,2,'.','')."|".number_format($service3metier,2,'.','')."|".number_format($service3demat,2,'.','')."|".number_format($service3mp,2,'.','')."|".number_format($service3si,2,'.','')."|".number_format($service3usersmescol,2,'.','')."|".number_format($service3sve,2,'.','')."|".number_format($cotisationdpd,2,'.','')."|".number_format($service3dns,2,'.','')."|".number_format($service4bal,2,'.','')."|".number_format($regul_mon,2,'.','')."|".number_format($totalvs,2,'.','');
$lignerecap = fputs($fprecap,iconv("UTF-8", "WINDOWS-1252", $chainerecap));
fclose($fprecap);

// On détermine la date du jour de la facturation
$now   = new DateTime;
$clone = clone $now;    
// On met la valeur de paiement à 30 jours
$clone->modify( '+30 day' );  
  
$date=date("d/m/Y");
$str = $adresse1;
// On fait le ménage dans le champ adresse1 qui comporte parfois des retours chariot
$order = array("\r\n", "\n", "\r");
$replace = ',';
$adresse1 = str_replace($order, $replace, $str);

$fp2 = fopen ("$ficlog","a+"); 
for ($calcul2=0; $calcul2<$ligne_article; $calcul2++)
{
	$chaine2="$code_adh|$compte|".$adresse1."|$cp|".$commune."|".$now->format( 'd-m-Y' )."|".$clone->format( 'd-m-Y' )."|$totalvs||1|$phase2[$calcul2]\r\n";
	// On écrit dans le fichier d'export la chaine
    $ligne2 = fputs($fp2,iconv("UTF-8", "WINDOWS-1252", $chaine2));
	echo $chaine2;
}
fclose($fp2);

?>
</pre>
<script type="text/javascript">
  <!-- Début
     window.parent.opener.location.reload();
	 self.close();
  // Fin --> 
</script> 				
				
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
     <?php include('include/footer.php'); ?>
