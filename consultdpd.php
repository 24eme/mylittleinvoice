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
                <small></small>   <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="btn btn-success btn-xs"><i class="fa fa-user-plus"></i> <?php echo $lang['goback']; ?></a>
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


<?php

if (isset($_GET["consult"])) 
{
	$consult_insee = $_GET["consult"];
	$nomdefichier = $grc_config['dpd']['path']."/txt/facturation".$annee."-".$consult_insee.".txt";
}
else
{
	echo "</body></html>";
    exit;
}

?>
<div class="container theme-showcase" role="main">
	<div class="jumbotron">
	<h2>Consultation de la facture</h2>
	<div>
<?php
$fic=fopen($nomdefichier, "r"); 
$i = 0;
while ($i < 2)
{
    // on recupère la ligne courante
	$ligne= fgets($fic,1024); 
	//echo $ligne . "<br>"; 
	//list($line['Code Adhérent'],$line['Compte'],$line['Adresse'],$line['CP'],$line['Commune'],$line['Date Facture'],$line['Date Paiement'],$line['Montant Total'],$line['Fichier pdf'],$line['Assigné à'],$line['Type Ligne'],$line['Groupe'],$line['Désignation'],$line['Descriptif'],$line['Qté'],$line['PU'],$line['QtéxPU'],$line['Total Groupe']) = explode("|", $ligne);
	$pieces = explode("|", $ligne);
	$i ++; 
}
fclose($fic);
echo "<div class='row'>";
echo "<div class='col-md-4'>Nom de la facture</div>";
echo "<div class='col-md-4'>".utf8_encode($name_facturation_dpd)." - ".utf8_encode($pieces[1])."</div>";
echo "</div>";

echo "<div class='row'>";
echo "<div class='col-md-4'>Compte</div>";
echo "<div class='col-md-4'>".utf8_encode($pieces[1])."</div>";
echo "</div>";

echo "<div class='row'>";
echo "<div class='col-md-4'>N° adhérent</div>";
echo "<div class='col-md-4'>".$pieces[0]."</div>";
echo "</div>";

echo "<div class='row'>";
echo "<div class='col-md-4'>Adresse</div>";
echo "<div class='col-md-4'>".utf8_encode($pieces[2])."</div>";
echo "</div>";

echo "<div class='row'>";
echo "<div class='col-md-4'>CP - Ville</div>";
echo "<div class='col-md-4'>".$pieces[3]." ".utf8_encode($pieces[4])."</div>";
echo "</div>";

echo "<div class='row'>";
echo "<div class='col-md-4'>Date de facture</div>";
echo "<div class='col-md-4'>".$pieces[5]."</div>";
echo "</div>";

echo "<div class='row'>";
echo "<div class='col-md-4'>Date de paiement</div>";
echo "<div class='col-md-4'>".$pieces[6]."</div>";
echo "</div>";

echo "<div class='row'>";
echo "<div class='col-md-4'>Montant total</div>";
echo "<div class='col-md-4'>".$pieces[7]."</div>";
echo "</div>";

echo"<h2>Corps de la facture</h2>";

echo "<div class='container-fluid'>";

echo "<div class='row'>";
echo "<div class='col-md-8'>Désignation</div>";
echo "<div class='col-md-1'>&nbsp;</div>";
echo "<div class='col-md-1'>Qté</div>";
echo "<div class='col-md-1'>Prix U.</div>";
echo "<div class='col-md-1'>Total</div>";
echo "</div>";
echo "<br>";
 
//$contenu_fichier = file_get_contents($nomdefichier);
//echo substr_count($contenu_fichier, "\n");
$lignes='';
$fichier=fopen($nomdefichier, "r"); 
while(!feof($fichier)) 
{ 
	$lignes= fgets($fichier,1024); 
	//echo $ligne . "<br>"; 
	list($line['Code Adhérent'],$line['Compte'],$line['Adresse'],$line['CP'],$line['Commune'],$line['Date Facture'],$line['Date Paiement'],$line['Montant Total'],$line['Fichier pdf'],$line['Assigné à'],$line['Type Ligne'],$line['Groupe'],$line['Désignation'],$line['Descriptif'],$line['Qté'],$line['PU'],$line['QtéxPU'],$line['Total Groupe']) = explode("|", $lignes);
	
	if ($line['Type Ligne'] == 'G') 
    {
		echo "<div class='row'>";
		echo "<div class='col-md-8'>".utf8_encode($line['Groupe'])."</div>";
		echo "<div class='col-md-1'>&nbsp;</div>";
		echo "<div class='col-md-1'>&nbsp;</div>";
		echo "<div class='col-md-1'>&nbsp;</div>";
		echo "<div class='col-md-1'>".$line['Total Groupe']."</div>";
		echo "</div>";
		echo "<br>";
    }

	if ($line['Type Ligne'] == 'A') 
    {
		// On consulte la ligne de produit
		echo "<div class='row'>";
		echo "<div class='col-md-8'>".utf8_encode($line['Désignation'])."</div>";
		echo "<div class='col-md-1'>&nbsp;</div>";
		echo "<div class='col-md-1'>".$line['Qté']."</div>";
		echo "<div class='col-md-1'>".$line['PU']."</div>";
		echo "<div class='col-md-1'>".$line['QtéxPU']."</div>";
		echo "</div>";
		echo "<div class='row'>";
		echo "<div class='col-md-8'>".utf8_encode($line['Descriptif'])."</div>";
		echo "<div class='col-md-1'>&nbsp;</div>";
		echo "<div class='col-md-1'>&nbsp;</div>";
		echo "<div class='col-md-1'>&nbsp;</div>";
		echo "</div>";	
	}
	
	if ($line['Type Ligne'] == 'T') 
    {
		// On consulte la ligne de total
		echo "<div class='row'>";
		echo "<div class='col-md-8'>".utf8_encode($line['Désignation'])."</div>";
		echo "<div class='col-md-1'>&nbsp;</div>";
		echo "<div class='col-md-1'>&nbsp;</div>";
		echo "<div class='col-md-1'>&nbsp;</div>";
		echo "<div class='col-md-1'>".$line['Total Groupe']."</div>";
		echo "</div>";	
	}

} 
fclose($fichier) ;
?>
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
