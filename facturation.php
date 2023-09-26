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
           <i class="fa fa-cubes"></i> <?php echo $lang['invoicing']; ?>
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
if (isset($_GET["unlink"])) {
        $supp_fichier = $_GET["unlink"];
        $ficpdf = substr($suppr_fichier,6,9) ;

        //Suppresion des fichiers de génération de facture.
        if (is_file("corail-export-intervention/".$ficpdf.".pdf"))
        { 
            unlink("corail-export-intervention/".$ficpdf.".pdf");
        }
        if (is_file("corail-export-intervention/".$supp_fichier))
        {
            unlink("corail-export-intervention/".$supp_fichier);
        }
//      header('Location: facturationat86.php');
    echo "<script type='text/javascript'>document.location.replace('facturation.php');</script>";
}


$desired_extension = 'xml'; 
$dirname = "corail-export-intervention/";
$dir = opendir($dirname);
echo "<table>";
while(false != ($file = readdir($dir))) {
    if(($file != ".") and ($file != "..")) {
        $fileChunks = explode(".", $file);
        if($fileChunks[1] == $desired_extension){

            echo "<tr><td align=center>Fichier Facture : $file </td>";

            foreach (glob("corail-export-intervention/".$file) as $filename) {
            //echo "$filename occupe " . filesize($filename) . "\n";
            }
            if(is_file($filename))
            {
                echo "<td align=center><a href=$filename download=$file>&nbsp;XML&nbsp;</a></td>";
            }
            else
            {
                echo "<td>&nbsp;</td>";
            }

            $filenamepdf = substr($file, 11, strpos($file, ".xml")-strlen($file));
            $nomfichier = $annee."-".$filenamepdf;
            $filenamepdf = $annee."-".$filenamepdf.".pdf";
            unset($filename);
            if(is_file("corail-export-intervention/".$filenamepdf))
            {
                echo "<td align=center><a href=corail-export-intervention/$filenamepdf download=$filenamepdf>&nbsp;PJ&nbsp;</a></td>";
            }    
            else
            {
                echo "<td>&nbsp;</td>";
            }
            echo "<td align=center>&nbsp;<button type=\"button\" class=\"btn btn-xs btn-danger\" data-toggle=\"modal\" data-target=\"#Modalouinon\" data-suppr=\"".$file."\">X</button></td>";
            echo "</tr>";
        }
    }
}
echo "</table>";
closedir($dir);


?>
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

<script>
$('#Modalouinon').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget)
  var recipient = button.data('suppr')
  var modal = $(this)
  modal.find('.modal-title').text('Suppression des fichiers : ' + recipient)
  modal.find('.modal-body').text('Voulez-vous supprimer les fichiers de factures générées : ' + recipient + ' ?')
  $('#myStateButton').on("click",function(){
		var button = $(event.relatedTarget)
		var liensuppr = button.data('suppr')
		document.location.href='facturation.php?unlink='+liensuppr;
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
