<?php

/*
Gestion de base de donnée
Date : 12/2009
Dernière modification : 06/2015 -> Ajout de la config
Auteur: Jonathan RAVIX
Cette classe à pour but de simplifier l'accès à une base de donnée en utilisant une méthode de programmation proche de celle de wordpress.
Comment l'utiliser :
Avant de l'utiliser, il convient de définir les variables $host, $login, $password et basename en fonction de votre serveur et base de donnée.

Script d'exmple :
$mybd = new Bd;
$mybd->config('hote','utilisateur','mot de passe','base de donnée');
$mybd->update('matable', array('info'=>'Mon titre d\'information'), array('info'=>'My info'));
$tables = $mybd->get_results('SELECT * FROM matable');
print_r($tables);

echo '<br /><br />' . $tables[0]->info;

*/

class Bd{
	private $host = 'localhost';
	private $login = 'root';
	private $password = '';
	private $basename = 'test_poo';
	public $prefix = '';
	public $option_table = '';
	public function insert($table_name, $data){
		$insert_id = '';
		$insert_value = '';
		$mysqli = new mysqli($this->host, $this->login, $this->password, $this->basename);
		if ($mysqli->connect_errno) {
			throw new Exception("Failed to connect to MySQL: " . $mysqli -> connect_error);
		}
		foreach($data as $id => $value){
			$id = addslashes($id);
			$value = addslashes($value);
			if($insert_id == ''){$insert_id .= '' . $id . '';}else{$insert_id .= ', ' . $id . '';}
			if($insert_value == ''){$insert_value .= '"' . $value . '"';}else{$insert_value .= ' ,"' . $value . '"';}
		}
		$mysqli_query = 'INSERT INTO ' . $table_name. '(' . $insert_id . ') VALUES(' . $insert_value . ')';
		if($mysqli->query($mysqli_query)){
			$mysqli->close();
			return true;
		}else{
			$mysqli->close();
			return false;
		}
	}
	public function update($table_name, $data, $where){
		$mysqli = new mysqli($this->host, $this->login, $this->password, $this->basename);
		if ($mysqli->connect_errno) {
			throw new Exception("Failed to connect to MySQL: " . $mysqli -> connect_error);
		}
		$insert_value = '';
		foreach($data as $id => $value){
			$id = addslashes($id);
			$value = addslashes($value);
			if($insert_value == ''){$insert_value .= $id . '="' . $value . '"';}else{$insert_value .= ', ' . $id . '="' . $value . '"';}
		}
		$where_insert = '';
		foreach($where as $id => $value){
			$id = addslashes($id);
			$value = addslashes($value);
			if($where_insert == ''){$where_insert .= $id . '="' . $value . '"';}else{$where_insert .= ' AND ' . $id . '="' . $value . '"';}
		}
		$mysqli_query = 'UPDATE ' . $table_name. ' SET ' . $insert_value . ' WHERE ' . $where_insert . '';

		if($mysqli->query($mysqli_query)){
			$mysqli->close();
			return true;
		}else{
			$mysqli->close();
			return false;
		}
	}
	public function get_results($request){
		$mysqli = new mysqli($this->host, $this->login, $this->password, $this->basename);
		if ($mysqli->connect_errno) {
			throw new Exception("Failed to connect to MySQL: " . $mysqli -> connect_error);
		}
		$results = $mysqli->query($request);
		if (!$results) {
			throw new Exception("Error description: " . $mysqli -> error." ($request)");
		}
		$reponse = array();
		$counter = 0;
		while ($result = $results->fetch_array()){
			$reponse[$counter] = new stdClass;
			foreach($result as $key => $value){
				$reponse[$counter]->$key = stripcslashes($value);
			}
			$counter++;
		}
		$mysqli->close();
		return $reponse;
	}
	public function query($query = ''){
		$mysqli = new mysqli($this->host, $this->login, $this->password, $this->basename);
		if ($mysqli->connect_errno) {
			throw new Exception("Failed to connect to MySQL: " . $mysqli -> connect_error);
		}
		$results = $mysqli->query($query);
		if (!$results) {
			throw new Exception("Error description: " . $mysqli -> error." ($request)");
		}
		$mysqli->close();
		return true;
	}
	public function get_option($name='', $default=null){
		if (!$this->option_table) {
			throw new Exception('option_table missing in configuration');
        }
		$r = $this->get_results('SELECT * FROM ' . $this->option_table . ' WHERE option_name="' . $name . '"');
		if(is_array($r) AND !empty($r)){
			$reponse = $r[0]->option_value;
		}elseif($default) {
			$reponse = $default;
		}elseif(isset($r)){
			$reponse = true;
		}else{
			$reponse = false;
		}
		return $reponse;
	}
	public function set_option($name='',$value=''){
		if (!$this->option_table) {
			throw new Exception('option_table missing in configuration');
		}
		$r = $this->get_results('SELECT * FROM ' . $this->option_table . ' WHERE option_name="' . $name . '"');
		if(is_array($r) AND !empty($r)){
			if($this->update($this->option_table,array('option_value'=>$value),array('option_name'=>$name))){
				return true;
			}else{
				return false;
			}
		}else{
			if($this->insert($this->option_table,array('option_name'=>$name,'option_value'=>$value))){
				return true;
			}else{
				return false;
			}	
		}
	}
	public function test_connect($host = '', $login = '', $password = '', $basename = ''){
		if(mysqli_connect($host, $login, $password)){
			$mysqli = new mysqli($host, $login, $password, $basename);
			if($mysqli){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	public function config($host,$login,$password,$basename,$prefix='',$option_table=''){
		$this->host = $host;
		$this->login = $login;
		$this->password = $password;
		$this->basename = $basename;
		$this->prefix = $prefix;
		$this->option_table = $option_table;
	}
}
