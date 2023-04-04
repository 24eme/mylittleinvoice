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
           <i class="fa fa-cubes"></i> <?php echo $lang['state_invoicing']; ?>
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

<div class="jumbotron">
<h2>Etat de facturation des Collectivit&eacute;s <?php echo $annee; ?></h2>
<div>
<table class="table table-striped">
<thead>
	<tr>
        	<th>Insee</th>
		<th>Collectivit&eacute;</th>
		<th>Adh</th>
		<th>S1</th>
		<th>S2</th>
		<th>S3 M&eacute;t</th>
		<th>S3 D&eacute;m</th>
		<th>S3 MP</th>
		<th>S3 SI</th>
		<th>S3 MESS</th>
		<th>S3 SVE</th>
		<th>DPD</th>
		<th>R&eacute;gul</th>
		<th>TOTAL</th>
	</tr>
</thead>
<tbody>
<?php
$total=array();
$dir = $grc_config['at86']['path']."/txt/";
$Rtotadhesion=0;
$Rtots1=0;
$Rtots2=0;
$Rtots3MET=0;
$Rtots3DEMAT=0;
$Rtots2MP=0;
$Rtots3SI=0;
$Rtots3MESS=0;
$Rtots3SVE=0;
$RtotDPD=0;
$Rtotregul=0;
$Rtottotal=0;

$dp = opendir($dir);
$i=0;
while ( $file = readdir($dp) )
 {
   // enleve les fichiers . et ..
   if ($file != '.' && $file != '..' && substr($file,11,4) == "2022")
   {
           // on passe les datas dans un tableau
           $ListFiles[$i]=$file;
           $i++;
   }
}
closedir($dp);

// tri par ordre decroissant
if(count($ListFiles)!=0)
{
   if($list_tri == 'DESC')
   {
       rsort($ListFiles);
   }
   else
   {
       sort($ListFiles);
   }
}
$i=0;
while ( $i < count($ListFiles))
{
	$file = $dir.$ListFiles[$i];
	$mip = array();
	$Radhesion = "";
	$Rs1="";
	$Rs2="";
	$Rs3MET="";
	$Rs3DEMAT="";
	$Rs2MP="";
	$Rs3SI="";
	$Rs3MESS="";
	$Rs3SVE="";
	$RDPD="";
	$Rregul="";
	$Rtotal="";
	$searchfor = "|G|";

$contents = file_get_contents($file);
$pattern = preg_quote($searchfor, '/');
$pattern = "/^.*$pattern.*\$/m";

if(preg_match_all($pattern, $contents, $matches)){
//    $test = implode("<br />", $matches[0]);
//    echo $test;
            //echo "Taille : ".sizeof($matches[0])."<br />";
            for ($mi = 0; $mi <sizeof($matches[0]) ; ++$mi)
            {
		$mip = explode("|",utf8_encode($matches[0][$mi]));
        	$Rinsee = $mip[0];
		$Rcollectivite = $mip[1];
                if ($mip[11] == "Cotisation d'adhésion AT86")
                {
 			$Radhesion = number_format($mip[17],2,'.','');
                }
                if ($mip[11] == "Assistance technique Collectivités")
                {
 			$Rs1 = number_format($mip[17],2,'.','');
                }
                if ($mip[11] == "Assistance technique Écoles")
                {
 			$Rs2 = number_format($mip[17],2,'.','');
                }
                if ($mip[11] == "Assistance aux logiciels métiers")
                {
 			$Rs3MET = number_format($mip[17],2,'.','');
                }
                if ($mip[11] == "Tiers de télétransmissions")
                {
 			$Rs3DEMAT = number_format($mip[17],2,'.','');
                }
                if ($mip[11] == "Dématérialisation des marchés publics")
                {
 			$Rs2MP = number_format($mip[17],2,'.','');
                }
                if ($mip[11] == "Sites internet abonnement")
                {
 			$Rs3SI = number_format($mip[17],2,'.','');
                }
                if ($mip[11] == "Outils de messagerie électronique")
                {
 			$Rs3MESS = number_format($mip[17],2,'.','');
                }
                if ($mip[11] == "Saisine par voie électronique (SVE)")
                {
 			$Rs3SVE = number_format($mip[17],2,'.','');
                }
                if ($mip[11] == "Délégué à la protection des données personnelles")
                {
 			$RDPD = number_format($mip[17],2,'.','');
                }
                if ($mip[11] == "Régularisation")
                {
 			$Rregul = number_format($mip[17],2,'.','');
                }
		$Rtotal = number_format($mip[7],2,'.','');
                //echo utf8_encode($matches[0][$mi])."<br />";
            }
            $Rtotal= number_format($Rtotal,2,'.','');
            echo "<tr><td>".$mip[0]."</td><td>".$mip[1]."</td><td>".$Radhesion."</td><td>".$Rs1."</td><td>".$Rs2."</td><td>".$Rs3MET."</td><td>".$Rs3DEMAT."</td><td>".$Rs2MP."</td><td>".$Rs3SI."</td><td>".$Rs3MESS."</td><td>".$Rs3SVE."</td><td>".$RDPD."</td><td>".$Rregul."</td><td>".$Rtotal."</td></tr>";

	$Rtotadhesion+=$Radhesion;
	$Rtots1+=$Rs1;
	$Rtots2+=$Rs2;
	$Rtots3MET+=$Rs3MET;
	$Rtots3DEMAT+=$Rs3DEMAT;
	$Rtots2MP+=$Rs2MP;
	$Rtots3SI+=$Rs3SI;
	$Rtots3MESS+=$Rs3MESS;
	$Rtots3SVE+=$Rs3SVE;
	$RtotDPD+=$RDPD;
	$Rtotregul+=$Rregul;
	$Rtottotal+=$Rtotal;

//flush();
}
$i++;
}
//$Rtottotal = number_format($Rtottotal + $Rdpd,2,'.','');
echo "<tr><td>Total</td><td>&nbsp;</td><td>".$Rtotadhesion."</td><td>".$Rtots1."</td><td>".$Rtots2."</td><td>".$Rtots3MET."</td><td>".$Rtots3DEMAT."</td><td>".$Rtots2MP."</td><td>".$Rtots3SI."</td><td>".$Rtots3MESS."</td><td>".$Rtots3SVE."</td><td>".$RtotDPD."</td><td>".$Rtotregul."</td><td>".$Rtottotal."</td></tr>";

echo "<tr><td><b>Insee</b></td><td><b>Collectivité</b></td><td><b>Adh</b></td><td><b>S1</b></td><td><b>S2</b></td><td><b>S3MET</b></td><td><b>S3DEMAT</b></td><td><b>S3MP</b></td><td><b>S3SI</b></td><td><b>S3MESS</b></td><td><b>S3SVE</b></td><td><b>DPD</b></td><td><b>Régul</b></td><td><b>Total</b></td></tr>";

?>
</tbody>
</table>
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
