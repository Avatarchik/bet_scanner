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

	public function ToolsTest() {
		$output = [];
		$this->tools->TreeToPlain($arr, $output);
		print_r($output);

		print("\nOK\n");
	}

	public function DBTest($params) {
		$this->db->connect_db($params);
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
$test->DBTest($params);

// --------- Tools test
$arr = [
	'one' => [
		'one_one' => '1',
		'one_two' => '2',
	],
	'three' => '3'
];
$test->ToolsTest($arr);
