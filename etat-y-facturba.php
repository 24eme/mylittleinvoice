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
           <i class="fa fa-cubes"></i> <?php echo $lang['statu_invoicing']; ?>
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
	$total=array();
	$dir = $grc_config['urba']['path']."/recap/";
?>

<div class="container theme-showcase" role="main">
	<div class="jumbotron">
	<h2><?php echo $name_facturation_urba; ?></h2>
	<div>
    <table class="table table-striped">
    <thead>
		<tr>
			<th>Insee</th>
            <th>Collectivit&eacute;</th>
            <th>Total</th>
        </tr>
    </thead>
	<tbody>
<?php
  $dp = opendir($dir);
  $i=0;
  while ( $file = readdir($dp) )
  {
    // enleve les fichiers . et ..
    if ($file != '.' && $file != '..' && substr($file,11,4) == $annee)
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
// affiche les fichiers par ordre alphabetique decroissant
  $i=0;
  while ( $i < count($ListFiles))
  {
	  		$ficlog = $dir.$ListFiles[$i];
			$fp = fopen ("$ficlog","r");
			while(!feof($fp)) {
				$ligne = fgets($fp);
				$pieces = explode("|", $ligne);
				echo "<tr>";
				echo "<td>".$pieces[0]."</td>";
				echo "<td>".$pieces[1]."</td>";
				echo "<td>".$pieces[3]."</td>";
				$total[3] = $total[3] + $pieces[3];
				echo "</tr>";
			}
			fclose($fp);
      $i++;
  }
  	echo "<tr>";
	echo "<td>&nbsp;</td>";
	echo "<td>TOTAL</td>";
	echo "<td>".number_format($total[3],2,'.','')."</td>";
	echo "</tr>";
?>
	</tbody>
	</table>
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