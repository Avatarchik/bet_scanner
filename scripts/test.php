<?php
require('config/config.php');
require('scripts/modules/HTTP.php');
require('scripts/modules/DB.php');
require('scripts/modules/Tools.php');
require('scripts/modules/Errors.php');

class Test {
	function __construct() {
		$this->http = new HTTP();
		$this->db = new DB();
		$this->tools = new Tools();

	}

	public function DBTest($params) {
		$this->db->connect_db($params);
	}
	
	public function TreeToPlain() {
		$tree = json_decode('[{"id":1,"num":"1","tree":[{"id":4,"num":"1.1"},{"id":5,"num":"1.2"},{"id":6,"num":"1.3","tree":[{"id":9,"num":"1.3.1"},{"id":10,"num":"1.3.2"}]}]},{"id":2,"num":"2","tree":[{"id":7,"num":"2.1"},{"id":8,"num":"2.2"}]},{"id":3,"num":"3"}]', true);
		$output = [];
		$this->tools->TreeToPlain($tree, $output);
		
		foreach ($output as $item) {
			print(implode(',', $item['tags']) . ': ' . $item['value']. "\n");
		}
	}


}

$test = new Test();

// --------- DB test
$params = [
	'host' => '127.0.0.1',
	'db' => 'testdb',
	'usr' => 'root',
	'pass' => 'root',
	'charset' => 'utf8'
];
//$test->DBTest($params);

// ---------- TreeToPlain test

$test->TreeToPlain();