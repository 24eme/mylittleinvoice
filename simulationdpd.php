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
$service4dns = 0;
$service4bal = 0;
$regul_mon = 0;
$reference = '';
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
$etablissement = mysqli_result($result,$i,"nomcomplet_c");
$adresse1 = mysqli_result($result,$i,"billing_address_street");
$cp = mysqli_result($result,$i,"billing_address_postalcode");
$commune = mysqli_result($result,$i,"billing_address_city");
$tel = mysqli_result($result,$i,"phone_office");
$fax = mysqli_result($result,$i,"phone_fax");
$email = mysqli_result($result,$i,"email1");
$site = mysqli_result($result,$i,"website");
$population = mysqli_result($result,$i,intval(date('Y'))% 2 == 0 ?"population_pair":"population_impair");
$nbcommb = mysqli_result($result,$i,"nbr_communes_membres_c");
$synccascias = mysqli_result($result,$i,"syndicat_ccas_cias_c");
$nbagents = mysqli_result($result,$i,"nombre_agents_c");
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
$regul_txt = mysqli_result($result,$i,"adherent_regul_def");
$regul_mon = mysqli_result($result,$i,"adherent_regul_mon");
$quota_teles_q = mysqli_result($result,$i,"quota_teles_q");
$quota_teles_u = mysqli_result($result,$i,"quota_teles_u");
$dpd_cnil = mysqli_result($result,$i,"dpd_cnil_c");

$tabDate = explode('-' , $dpd_cnil);
$date_conv  = $tabDate[2].'-'.$tabDate[1].'-'.$tabDate[0];
if (($tabDate[1] <  7) && ($tabDate[1] > 0))
{
    $taux_dpd = 6/12 ;
    $txttauxdpd='6/12';
}
if ($tabDate[1] == 7)
{
    $taux_dpd = 5/12;
    $txttauxdpd='5/12';
}
if ($tabDate[1] == 8)
{
    $taux_dpd = 4/12;
    $txttauxdpd='4/12';
}
if ($tabDate[1] == 9)
{
    $taux_dpd = 3/12;
    $txttauxdpd='3/12';
}
if ($tabDate[1] == 10)
{
    $taux_dpd = 2/12;
    $txttauxdpd='2/12';
}
if ($tabDate[1] == 11)
{
    $taux_dpd = 1/12;
    $txttauxdpd='1/12';
}

// On réinitialise le fichier pour l'import dans le SI en le supprimant
//$ficlog="../custom/import/invoices/facturation".$annee."-".$insee.".txt";
$ficlog=$grc_config['dpd']['path']."/txt/facturation".$annee."-".$insee.".txt";
$ficrecap=$grc_config['dpd']['path']."/recap/facturation".$annee."-".$insee.".txt";

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

//*************************************
// DPD
//*************************************
$cotisation = 0;
$texte = '';

//$filename = $grc_config['dpd']['path'].'/dpd.txt';
//$ligne= file($filename);
//$nbTotalLignes=count($ligne);
//$tab = array();
//for($ii=0;$ii<$nbTotalLignes;$ii++){
//	$ligneTab = explode(";", $ligne[$ii]);
//	// choisissons les lignes avec le code insee
//	if ($ligneTab[0] == $insee)
//	{
//		$texteA = "Dossier type ".$ligneTab[1];
//		$texte = "Référence : ".$ligneTab[2]." en date du ".$ligneTab[3];
//		$order = array("\r\n", "\n", "\r");
//		$replace = '';
//		$texte = str_replace($order, $replace, $texte);
//		$cotisation = $cotisation + $ligneTab[6];
//		$phase2[$ligne_article]="A|".iconv("UTF-8", "WINDOWS-1252","Mission de délégué à la protection des données")."|".iconv("UTF-8", "WINDOWS-1252",$texteA)."|".iconv("UTF-8", "WINDOWS-1252", $texte)."|$ligneTab[5]|$ligneTab[4]|$ligneTab[6]|";
//		$ligne_article = $ligne_article + 1;
//	}	
//}

if ($type == 'Commune') 
{
	$cotisation = $dpd_base * $population;
	if ($cotisation > $dpd_plafond) {
		$cotisation = $dpd_plafond;
	}
	if ($cotisation < $dpd_plancher) {
		$cotisation = $dpd_plancher;
	}
	$texte = "Population INSEE au 01/01/".$annee." : ".strval($population)." habitants. Soit ".number_format($dpd_base,2,'.','')." EUR par habitant plafonné à ".number_format($dpd_plafond,2,'.','')." EUR avec un plancher annuel de ".number_format($dpd_plancher,2,'.','')." EUR. "."La tarification annuelle est de ".number_format($cotisation,2,'.','')." EUR, avec un prorata temporis sur 2018 de ".$txttauxdpd;
	$dpdunit = $cotisation * $taux_dpd;
        $cotisation = $dpdunit;
	$quantite = 1;
}
if ($type == 'Communaute communes') 
{
	$cotisation = $dpd_epci;
	$texte = "Forfait annuel EPCI. La tarification annuelle est de ".number_format($cotisation,2,'.','')." EUR, avec un prorata temporis sur 2018 de ".$txttauxdpd;
	$dpdunit = $cotisation * $taux_dpd;
        $cotisation = $dpdunit;
	$quantite = 1;
}
if (($type <> 'Commune') && ($type <> 'Communaute communes'))
{
	if ($nbagents < 11)
	{
		$cotisation = $dpd_etp_0;
	}
	if (($nbagents > 10) && ($nbagents <31))
	{
		$cotisation = $dpd_etp_11;
	}
	if ($nbagents > 30)
	{
		$cotisation = $dpd_etp_31;
	}
	$texte = "Forfait annuel Syndicat/CCAS/CIAS selon ".$nbagents." ETP. La tarification annuelle est de ".number_format($cotisation,2,'.','')." EUR, avec un prorata temporis sur 2018 de ".$txttauxdpd;
	$dpdunit = $cotisation * $taux_dpd;
        $cotisation = $dpdunit;
	$quantite = 1;
}
//*************************************
//Lignes de facturation DPD pour intégration dans le CRM
//*************************************

$order = array("\r\n", "\n", "\r");
$replace = '';
$texte = str_replace($order, $replace, $texte);
$phase2[$ligne_article]="A|Mission de délégué à la protection des données|Accompagnement Service DPD mutualisé. Déclaration CNIL faite le ".$date_conv."|".$texte."|$quantite|".number_format($dpdunit,2,'.','')."|".number_format($cotisation,2,'.','')."|";
$ligne_article = $ligne_article + 1;

$phase2[$ligne_article]="G|Mission de délégué à la protection des données|Total|||||".number_format($cotisation,2,'.','');
$ligne_article = $ligne_article + 1;
//*************************************

//*************************************
// ligne des totaux
//*************************************
$totalvs = $cotisation;
$phase2[$ligne_article]="T|Total|Total Facturation|||||".number_format($totalvs, 2, ',', '')."";
$ligne_article = $ligne_article + 1;
//*************************************


$fprecap = fopen ("$ficrecap","a+"); 
$chainerecap=$insee."|".$compte."|".$type."|".number_format($cotisation,2,'.','');
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
