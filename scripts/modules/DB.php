<?php
class DB {

	function __construct(){
		$this->db_heandler = null;
	}

	public function connect_db ($params) {
	$dsn = 'mysql:host=' . $params['host'] . ';dbname=' . $params['db'] . ';charset=' . $params['charset'];
	$opt = [
			 PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			 PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			 PDO::ATTR_EMULATE_PREPARES   => false,
	 ];
	$pdo = new PDO($dsn, $params['usr'], $params['pass'], $opt);
		print_r($pdo);
	}
	public function disconnect_db () {
		$this->db_heandler = null;
	}

}
