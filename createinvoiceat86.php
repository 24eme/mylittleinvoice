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
           <i class="fa fa-cubes"></i> <?php echo $lang['urba_invoicing']; ?>
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

if (isset($_GET["import"]))
{
	$import_insee = $_GET["import"];
	$nomdefichier = $grc_config['at86']['path']."/txt/facturation".$annee."-".$import_insee.".txt";
}
else
{
	echo "</body></html>";
    exit;
}

$url = $grc_config['at86']['soap_url'];
$username =  $grc_config['at86']['soap_user'];
$password =  $grc_config['at86']['soap_pass'];

?>
<div class="container theme-showcase" role="main">
	<div class="jumbotron">
	<h2>Import dans la GRC</h2>
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
fclose($fic) ;

echo "Compte : ".$pieces[1]."<br>";
echo "N° adhérent : ".$pieces[0]."<br>";
echo "Adresse : ".$pieces[2]."<br>";
echo "CP - Ville : ".$pieces[3]." ".$pieces[4]."<br>";
echo "Date de facture : ".$pieces[5]."<br>";
echo "Date limite de paiement : ".$pieces[6]."<br>";
echo "Montant total : ".$pieces[7]."<br>";

function create_guid_section($characters)
{
	$return = "";
	for($i=0; $i<$characters; $i++)
	{
		$return .= sprintf("%x", mt_rand(0,15));
	}
	return $return;
}

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

function ensure_length(&$string, $length)
{
	$strlen = strlen($string);
	if($strlen < $length)
	{
		$string = str_pad($string,$length,"0");
	}
	else if($strlen > $length)
	{
		$string = substr($string, 0, $length);
	}
}

function create_guid()
{
    $microTime = microtime();
	list($a_dec, $a_sec) = explode(" ", $microTime);

	$dec_hex = sprintf("%x", $a_dec* 1000000);
	$sec_hex = sprintf("%x", $a_sec);

	ensure_length($dec_hex, 5);
	ensure_length($sec_hex, 6);

	$guid = "";
	$guid .= $dec_hex;
	$guid .= create_guid_section(3);
	$guid .= '-';
	$guid .= create_guid_section(4);
	$guid .= '-';
	$guid .= create_guid_section(4);
	$guid .= '-';
	$guid .= create_guid_section(4);
	$guid .= '-';
	$guid .= $sec_hex;
	$guid .= create_guid_section(6);

	return $guid;

}

function formatString($s)
{
	if (is_null($s)) {
		return 'NULL';
	}
	return '\'' . addslashes($s) . '\'';
}

function createUpdateQuery($fields, $table, $where)
{
	$sql = 'UPDATE ' . $table . ' SET ';
	$updates = array();
	foreach ($fields as $f => $v) {
		$updates[] = $f . ' = ' . $this->formatString($v);
	}
	$sql .= join(', ', $updates);
	$sql .= ' WHERE ' . $where;
	return $sql . ';';
}

function createInsertQuery($fields, $table)
{
	$names = array_keys($fields);
	$values = array();
	foreach ($fields as $k => $v) {
		$values[] = formatString($v);
	}
	$sql = 'INSERT INTO ' . $table . '(' . join(', ', $names) . ') VALUES (' . join(', ', $values) . ');';
	return $sql;
}
//require NuSOAP
require_once("nusoap/lib/nusoap.php");
$client = new nusoap_client($url, 'wsdl');
$err = $client->getError();
if ($err)
{
    echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
    echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
    exit();
}

$login_parameters = array(
    'user_auth' => array(
    'user_name' => $username,
    'password' => $password,
    'version' => '1'
    ),
    'application_name' => 'SoapTest',
    'name_value_list' => array(
    ),
);

$login_result = $client->call('login', $login_parameters);

// On réupère le n° unique de la collectivité
$sql = 'SELECT id_c FROM accounts_cstm WHERE numero_adherent_collectivite_c = \'' . ($pieces[0]) . '\'';
$result = mysqli_query($link, $sql);
if (!$result) {
   echo "Impossible d'exécuter la requête 1 ($sql) dans la base : " . mysqli_error($link);
   exit;
}
if (mysqli_num_rows($result) == 0) {
   echo "Aucune ligne trouvée, rien à afficher.";
   exit;
}
if ($row = mysqli_fetch_assoc($result)) {
    $numeroid = $row['id_c'];
}
$pieces[5] = implode('-', array_reverse(explode('-', $pieces[5])));
$pieces[6] = implode('-', array_reverse(explode('-', $pieces[6])));
//echo "<pre>";
//print_r($pieces);
//echo "</pre>";
//exit;

$session_id =  $login_result['id'];
$set_entry_parameters = array(
    "session" => $session_id,
    "module_name" => "Invoice",
    "name_value_list" => array(
		array("name" => "assigned_user_id", "value" => "1"),
		array("name" => "billing_account_id", "value" => $numeroid),
		array("name" => "shipping_account_id", "value" => $numeroid),
		array("name" => "name", "value" => "Facturation 2023 Cotisation et Services"),
		array("name" => "billing_address_street", "value" => $pieces[2]),
		array("name" => "billing_address_city", "value" => $pieces[4]),
		array("name" => "billing_address_postalcode", "value" => $pieces[3]),
		array("name" => "shipping_address_street", "value" => $pieces[2]),
		array("name" => "shipping_address_city", "value" => $pieces[4]),
		array("name" => "shipping_address_postalcode", "value" => $pieces[3]),
		array("name" => "shipping_stage", "value" => "Shipped"),
		array("name" => "invoice_date", "value" => $pieces[5]),
		array("name" => "due_date", "value" => $pieces[6]),
		array("name" => "amount", "value" => $pieces[7]),
		array("name" => "amount_usdollar", "value" => $pieces[7]),
		array("name" => "amount_due", "value" => $pieces[7]),
		array("name" => "amount_due_usdollar", "value" => $pieces[7]),
		//array("name" => "gross_profit", "value" => "0.00"),
		//array("name" => "gross_profit_usdoller", "value" => "0.00"),
		//array("name" => "pretax", "value" => $pieces[7]),
		//array("name" => "pretax_usd", "value" => $pieces[7]),
		array("name" => "subtotal", "value" => $pieces[7]),
		array("name" => "subtotal_usd", "value" => $pieces[7]),
    ),
);
$set_entry_result = $client->call("set_entry", $set_entry_parameters);

//echo "<pre>";
//print_r($set_entry_parameters);
//echo "</pre>";
//echo "<pre>";
//print_r($set_entry_result);
//echo "</pre>";

$line['id'] = $set_entry_result['id'];
print_r($line['id']);
$sqla='';
$position=0;
$group['id'] = '';

$fichier=fopen($nomdefichier, "r");
$j=1 ;//Compteur de ligne
while(!feof($fichier))
{
	$lignes= fgets($fichier,1024);
	//echo $ligne . "<br>";
	list($line['Code Adhérent'],$line['Compte'],$line['Adresse'],$line['CP'],$line['Commune'],$line['Date Facture'],$line['Date Paiement'],$line['Montant Total'],$line['Fichier pdf'],$line['Assigné à'],$line['Type Ligne'],$line['Groupe'],$line['Désignation'],$line['Descriptif'],$line['Qté'],$line['PU'],$line['QtéxPU'],$line['Total Groupe']) = explode("|", $lignes);
	$j ++;
    if ($group['id'] == '')
	{
	    $group['id'] = create_guid();
    }

	if ($line['Type Ligne'] == 'G')
    {
		// On crée le groupe
		$data = array(
        'id' => $group['id'],
        'status' => 'Delivered',
        'name' => $line['Groupe'],
        'date_entered' => date('Y-m-d H:i:s'),
        'date_modified' => date('Y-m-d H:i:s'),
		'parent_id' => $line['id'],
		'subtotal' => $line['Total Groupe'],
		'subtotal_usd' => $line['Total Groupe'],
		'total' => $line['Total Groupe'],
		'total_usd' => $line['Total Groupe']
		);
		$sql = createInsertQuery($data, 'invoice_line_groups');
		$result = mysqli_query($link, $sql);
		if (!$result) {
			echo "Impossible d'exécuter la requête 2 ($sql) dans la base : " . mysqli_error($link);
			exit;
		}
		//echo "<p>". $sql . "</p>";
		$group['id'] = '';
		$position=0;
    }

	if ($line['Type Ligne'] == 'A')
    {
	    $position ++;
		// On crée la ligne de produit
		$data2 = array(
		'id' => create_guid(),
		'invoice_id' => $line['id'],
		'line_group_id' => $group['id'],
		'date_entered' => date('Y-m-d H:i:s'),
		'date_modified' => date('Y-m-d H:i:s'),
		'related_type' => 'ProductCatalog',
		'related_id' => null,
		'name' => $line['Désignation'],
		'quantity' => $line['Qté'],
		'ext_quantity' => $line['Qté'],
		'list_price' => $line['PU'],
		'list_price_usd' => $line['PU'],
		'cost_price' => $line['PU'],
		'cost_price_usd' => $line['PU'],
		'unit_price' => $line['PU'],
		'unit_price_usd' => $line['PU'],
		'ext_price' => $line['QtéxPU'],
		'ext_price_usd' => $line['QtéxPU'],
		'position' => $position,
		);
		$sqla = createInsertQuery($data2, 'invoice_lines');
		$result2 = mysqli_query($link, $sqla);
		if (!$result2)
		{
			echo "Impossible d'exécuter la requête 3 ($sqla) dans la base : " . mysqli_error($link);
			exit;
		}
//		echo "<p>". $sqla . "</p>";
		$sqla='';

		// On crée le commentaire
		$data3 = array(
		'id' => create_guid(),
		'invoice_id' => $line['id'],
		'line_group_id' => $group['id'],
		'date_entered' => date('Y-m-d H:i:s'),
		'date_modified' => date('Y-m-d H:i:s'),
		'position' => $position,
		'body' => $line['Descriptif'],
		);
		$sqlc = createInsertQuery($data3, 'invoice_comments');
		$result3 = mysqli_query($link, $sqlc);
		if (!$result3)
		{
			echo "Impossible d'exécuter la requête 4 ($sqlc) dans la base : " . mysqli_error($link);
			exit;
		}
//		echo "<p>". $sqlc . "</p>";
		$sqlc='';
	}
}
fclose($fichier) ;
if (($set_entry_result['id'] <> '') && ($result == 1) && ($result2 == 1) && ($result3 == 1))
{
	echo "L'import de la facture pour la collectivité ".$pieces[1]. " N° d'adhérent ".$pieces[0]." s'est bien déroulé !";
}
else
{
	echo "L'import de la facture pour la collectivité ".$pieces[1]. " N° d'adhérent ".$pieces[0]." ne s'est pas bien déroulé !";
        echo '<br>[';
        print_r($set_entry_result['id']);
        echo ']-';
        print_r($result);
        echo '-';
        print_r($result2);
        echo '-';
        print_r($result3);
}
?>
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
