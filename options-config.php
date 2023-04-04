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

<div class="container theme-showcase" role="main">
	<div class="jumbotron">
		<h2>Edition Configuration N</h2>
    <div id="form_container">
<?php
	// le chemin de votre fichier
	$chemin = 'config/config.ini';

if ($_POST['text']<>"")
{
	file_put_contents($chemin, $_POST['text']);
        echo "<p>fichier enregistré !</p>";
}

	// lit le contenu du fichier
	$contenu = file_get_contents($chemin, FILE_USE_INCLUDE_PATH);
?>

<form method="post" action="<?php echo basename($PHP_SELF);?>">
   <p>
       <h3>Editer le fichier de configuration</h3><br />
       <textarea name="text" cols="80" rows="35"><?php echo $contenu; ?></textarea>
   </p>
   <p><input type="submit" value="Enregistrer"/></p>
</form>

	</div>
    </div>
</div>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  
                 </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
     <?php include('include/footer.php'); ?>
