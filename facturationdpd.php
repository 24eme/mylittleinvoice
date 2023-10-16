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
           <i class="fa fa-cubes"></i> <?php echo $lang['dpd_invoicing']; ?>
          </h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><i class="fa fa-cubes"></i> <?php echo $lang['u_invoicing']; ?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-body">

<?php


// REQUETE MODIFIEE
$query = "SELECT A.*, C.* FROM ".$table_accounts. " A inner join ".$table_accounts_cstm." C  on A.id = C.id_c where A.is_supplier!='1' && A.deleted='0' && A.account_type!='Manufacturer' && (C.adhesion_at86_c='adhesion_oui') && (C.dpd_adh_c='adhesion_oui') && (C.dpd_cnil_c<>'') order by name asc";

$result = mysqli_query($link, $query);
$num = mysqli_num_rows($result);
mysqli_close($link);

if (isset($_GET["blocage"])) {
	$bloc_insee = $_GET["blocage"];
	$ficbloc=$grc_config['dpd']['path']."/blocage/facturation".$annee."-".$bloc_insee.".txt";
	if( file_exists ($ficbloc)){
		unlink( $ficbloc ) ;
	} else {
		touch( $ficbloc ) ;
	}
//	header('Location: facturationat86.php');
    echo "<script type='text/javascript'>document.location.replace('facturationdpd.php');</script>";
}
//if (isset($_GET["import"]))
//{
//	$import_insee = $_GET["import"];
//	$nomdefichier = $grc_config['dpd']['path']."/txt/facturation".$annee."-".$import_insee."txt";
//	echo $nomdefichier;
//}

if (isset($_GET["unlink"])) {
	$supp_insee = $_GET["unlink"];
	//Suppresion des fichiers de génération de facture.
	if(is_file($grc_config['dpd']['path']."/Facturation".$annee."-".$supp_insee.".pdf"))
	{
		unlink($grc_config['dpd']['path']."/Facturation".$annee."-".$supp_insee.".pdf");
	}
	$ficlog=$grc_config['dpd']['path']."/txt/facturation".$annee."-".$supp_insee.".txt";
	if( file_exists ($ficlog)){
		unlink( $ficlog ) ;
	}
	$ficrecap=$grc_config['dpd']['path']."/recap/facturation".$annee."-".$supp_insee.".txt";
	if( file_exists ($ficrecap)){
		unlink( $ficrecap ) ;
	}
	$ficmail=$grc_config['dpd']['path']."/mail/mail".$annee."-".$supp_insee.".txt";
	if( file_exists ($ficmail)){
		unlink( $ficmail ) ;
	}
//	header('Location: facturationat86.php');
    echo "<script type='text/javascript'>document.location.replace('facturationdpd.php');</script>";
}
?>
	<div class="jumbotron">
	<h2>&nbsp;Liste des Collectivit&eacute;s</h2>

<?php
	$filename = $grc_config['dpd']['path'].'/dpd.txt';
 	$ligne= file($filename);
	$nbTotalLignes=count($ligne);
	$tab = array();
	for($i=0;$i<$nbTotalLignes;$i++){
		$ligneTab = explode(";", $ligne[$i]);
		// choisissons la 1ère et 3ème colonne:
		$tab[$ligneTab[0]]=$ligneTab[0];
	}
?>

	<div>
    <table class="table table-striped">
    <thead>
		<tr>
			<th>Compte</th>
            <th>Adh&eacute;rent</th>
            <th>Type</th>
            <th>Simulation</th>
			<th><center>Action</center></th>
			<th><center>Facture</center></th>
			<th><center>Blocage</center></th>
			<th><center>Import</center></th>
                        <th>XML</th>
                        <th>PJ</th>
        </tr>
    </thead>
	<tbody>
<?php
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
$i=0;
while ($i < $num) {
		$name = mysqli_result($result,$i,"name");
		$code_adh = mysqli_result($result,$i,"numero_adherent_collectivite_c");		
		$id = mysqli_result($result,$i,"id");

		$adhesion_vs = mysqli_result($result,$i,"adhesion_vs_c");

		$compte = mysqli_result($result,$i,"name");
		$insee = mysqli_result($result,$i,"numero_adherent_collectivite_c");
		$type = mysqli_result($result,$i,"account_type");
		$etablissement = mysqli_result($result,$i,"name");
		$adresse1 = mysqli_result($result,$i,"billing_address_street");
		$cp = mysqli_result($result,$i,"billing_address_postalcode");
		$commune = mysqli_result($result,$i,"billing_address_city");
		$tel = mysqli_result($result,$i,"phone_office");
		$fax = mysqli_result($result,$i,"phone_fax");
		$email = mysqli_result($result,$i,"email1");
		$site = mysqli_result($result,$i,"website");
		$population = mysqli_result($result,$i,intval(date('Y'))% 2 == 0 ?"population_pair":"population_impair");
		$nbcommb = mysqli_result($result,$i,"nbr_communes_membres_c");
		$s1adh = mysqli_result($result,$i,"s1_compte_c");
		$s2adh = mysqli_result($result,$i,"s2_compte_c");
		$s3adh = mysqli_result($result,$i,"s3_compte_c");
		$s1nbserv = mysqli_result($result,$i,"nbr_serveurs_c");
		$s1nbpc = mysqli_result($result,$i,"nbr_postes_travail_c");
		$s2nbclas = mysqli_result($result,$i,"nbr_classes_c");
		$s3nbutil = mysqli_result($result,$i,"nbr_utilisateurs_logiciel_metier_c");
		$s3partutil = mysqli_result($result,$i,"nbr_utilisateurs_logiciel_partage_c");
		$s4demmp = mysqli_result($result,$i,"adherant_demat_marches_publics_c");
		$s4demact = mysqli_result($result,$i,"contrat_demat_actes_c");
		$s4demint = mysqli_result($result,$i,"site_web_heberge_vs_c");
		$s4mputil = mysqli_result($result,$i,"nbr_utilisateurs_demat_marches_publics_c");
		$s4actutil = mysqli_result($result,$i,"nbr_utilisateurs_demat_actes_c");
		$s4siutil = mysqli_result($result,$i,"nbr_utilisateurs_site_classique_c");
		$s4siminiutil = mysqli_result($result,$i,"nbr_utilisateurs_site_mini_c");
		$s4afnic = mysqli_result($result,$i,"afnic");
		$s4bal = mysqli_result($result,$i,"afnic_balsup");

		$regul_txt = mysqli_result($result,$i,"adherent_regul_def");
		$regul_mon = mysqli_result($result,$i,"adherent_regul_mon");
                $dpd_cnil = mysqli_result($result,$i,"dpd_cnil_c");
		
		$emailtab[$code_adh] = $email;
		
		//if ($tab[$code_adh] <> '') 
		//{
		echo "<tr>";
		echo "<td>".$compte."</td>";
		echo "<td>".$code_adh."</td>";
		echo "<td>".$type."</td>";
		if(is_file($grc_config['dpd']['path']."/txt/facturation".$annee."-".$insee.".txt"))
		{
			$ficbloc=$grc_config['dpd']['path']."/blocage/facturation".$annee."-".$insee.".txt";
			if( file_exists ($ficbloc)){
				echo "<td>Bloqu&eacute;</td>";
			} else {
				echo "<td><a href=simulationdpd.php?id=2&etab=$id name=simul target=_blank><button type=\"button\" class=\"btn btn-xs btn-success\">Simulation</button></a></td>";
			}
			echo "<td align=center><button type=\"button\" class=\"btn btn-xs btn-danger\" data-toggle=\"modal\" data-target=\"#Modalouinon\" data-suppr=\"".$code_adh."\">X</button></td>"; 
			echo "<td align=center><a href=consultdpd.php?consult=".$code_adh.">".$annee."</a></td>";
		}
		else
		{
			$ficbloc=$grc_config['dpd']['path']."/blocage/facturation".$annee."-".$insee.".txt";
			if( file_exists ($ficbloc)){
				echo "<td>Bloqué</td>";
			} else {
				echo "<td><a href=simulationdpd.php?id=2&etab=$id name=simul target=_blank><button type=\"button\" class=\"btn btn-xs btn-primary\">Simulation</button></a></td>";
			}
			echo "<td>&nbsp;</td>";
			echo "<td>&nbsp;</td>";
		}
		echo "<td align=center><button type=\"button\" class=\"btn btn-xs btn-danger\" data-toggle=\"modal\" data-target=\"#Modalbloc\" data-bloc=\"".$code_adh."\">B</button></td>";
		if(is_file($grc_config['dpd']['path']."/txt/facturation".$annee."-".$insee.".txt"))
		{
			echo "<td align=center><button type=\"button\" class=\"btn btn-xs btn-success\" data-toggle=\"modal\" data-target=\"#Modalimport\" data-import=\"".$code_adh."\"> I </button></td>";
		}
		else
		{
			echo "<td>&nbsp;</td>";
		}
        $filenamedpd = null;
                foreach (glob($grc_config['dpd']['exportservice_path']."/".$insee."-".$annee."-*.xml") as $filenamedpd) {
                        //echo "$filename occupe " . filesize($filename) . "\n";
						break;
                }
                if(is_file($filenamedpd))
                {
                        echo "<td align=center><a href=$filenamedpd download=$filenamedpd>XML</a></td>";
                }
                else
                {
                        echo "<td>&nbsp;</td>";
                }
                $filenamepdf = substr($filenamedpd, strpos($filenamedpd, $insee."-".$annee."-")+11, strpos($filenamedpd, ".xml")-strlen($filenamedpd));
                $filenamepdf = $annee."-".$filenamepdf.".pdf";
                unset($filenamedpd);
                if(is_file($grc_config['dpd']['exportservice_path']."/".$filenamepdf))
                {
                        echo "<td align=center><a href=\"".$grc_config['dpd']['exportservice_path']."/$filenamepdf\" download=$filenamepdf>PJ</a></td>";
                }
                else
                {
                        echo "<td>&nbsp;</td>";
                }

		echo "</tr>";
		//}
		$i++;
	}
?>
			</tbody>
          </table>
        </div>
	</div>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="Modalouinon" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Suppression des fichiers</h4>
      </div>
      <div class="modal-body">
          Si ce message s'affiche c'est qu'il y a un problème technique (avec le javascript et/ou bootstrap).
      </div>
      <div class="modal-footer">
		<button type="button" id="myStateButton" data-complete-text="finished!" class="btn btn-primary" autocomplete="off">OUI</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">NON</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="Modalbloc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Blocage Simulation</h4>
      </div>
      <div class="modal-body">
          Si ce message s'affiche c'est qu'il y a un problème technique (avec le javascript et/ou bootstrap).
      </div>
      <div class="modal-footer">
		<button type="button" id="myBlocButton" data-complete-text="finished!" class="btn btn-primary" autocomplete="off">OUI</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">NON</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="Modalimport" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Import des facture dans ma GRC</h4>
      </div>
      <div class="modal-body">
          Si ce message s'affiche c'est qu'il y a un problème technique (avec le javascript et/ou bootstrap).
      </div>
      <div class="modal-footer">
		<button type="button" id="myImportButton" data-complete-text="finished!" class="btn btn-primary" autocomplete="off">OUI</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">NON</button>
      </div>
    </div>
  </div>
</div>

<script>
$('#Modalbloc').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget)
  var recipient = button.data('bloc')
  var modal = $(this)
  modal.find('.modal-title').text('(Dé)Blocage Simulation pour l\'adhérent ' + recipient)
  modal.find('.modal-body').text('Voulez-vous (dé)bloquer la simulation de factures pour l\'adhérent ' + recipient + ' ?')
  $('#myBlocButton').on("click",function(){
		var button = $(event.relatedTarget)
		var liensuppr = button.data('bloc')
		document.location.href='facturationdpd.php?blocage='+liensuppr;
	});
})
</script>
<script>
$('#Modalimport').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget)
  var recipient = button.data('import')
  var modal = $(this)
  modal.find('.modal-title').text('Import de la facturation pour l\'adhérent ' + recipient)
  modal.find('.modal-body').text('Voulez-vous importer la facture dans la GRC pour l\'adhérent ' + recipient + ' ?')
  $('#myImportButton').on("click",function(){
		var button = $(event.relatedTarget)
		var liensuppr = button.data('import')
		document.location.href='createinvoicedpd.php?import='+liensuppr;
	});
})
</script>
<script>
$('#Modalouinon').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget)
  var recipient = button.data('suppr')
  var modal = $(this)
  modal.find('.modal-title').text('Suppression des fichiers pour l\'adhérent ' + recipient)
  modal.find('.modal-body').text('Voulez-vous supprimer les fichiers de factures générées pour l\'adhérent ' + recipient + ' ?')
  $('#myStateButton').on("click",function(){
		var button = $(event.relatedTarget)
		var liensuppr = button.data('suppr')
		document.location.href='facturationdpd.php?unlink='+liensuppr;
	});
})
</script>
				
				
				
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
     <?php include('include/footer.php'); ?>
     <script language="JavaScript">
	     function selection(id){
		    if(jQuery('#'+id).hasClass('selected')){
			    document.location.href="edit-customer.php?id="+id;
			}else{
			    jQuery('tr').removeClass('selected');
			    jQuery('#'+id).addClass('selected');
			    jQuery('.newquote').css('opacity','1').attr('href','new-quote.php?id_customer='+id);
			    jQuery('.newinvoice').css('opacity','1').attr('href','new-invoice.php?id_customer='+id);
			}
	     }
     </script>
