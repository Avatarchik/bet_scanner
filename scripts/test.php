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

	public function ToolsTest($arr) {
		$output = [];
		$this->tools->TreeToPlain($arr, $output);
		print_r($output);

		print("\nOK\n");
	}

	public function DBTest($params) {
		$this->db->connect_db($params);
	}

	
	public function TreeToPlain($tree, &$plain, $parent_id=null) {
		foreach ($tree as $item) {
			$plain[] = [
				'id' => $item['id'],
				'parent_id' => $parent_id,
				'num' => $item['num']
			];
			if (isset($item['tree'])) {
				$this->TreeToPlain($item['tree'], $plain, $item['id']);
			}	
		}
	}
	
	public function PlainToTree($plain, &$tree) {
		foreach ($plain as $item) {
			
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

// --------- Tools test
$arr = [
	'one' => [
		'one_one' => '1',
		'one_two' => '2',
	],
	'three' => '3'
];
// $test->ToolsTest($arr);

$tree = [
	[
		'id' => 1,
		'num' => '1',
		'tree' => [
			[
				'id' => 4,
				'num' => '1.1'
			],
			[
				'id' => 5,
				'num' => '1.2'
			],
			[
				'id' => 6,
				'num' => '1.3',
				'tree' => [
					[
						'id' => 9,
						'num' => '1.3.1'
					],
					[
						'id' => 10,
						'num' => '1.3.2'
					]
				]
			]
		]
	],
	[
		'id' => 2,
		'num' => "2",
		'tree' => [
			[
				'id' => 7,
				'num' => '2.1'
			],
			[
				'id' => 8,
				'num' => '2.2'
			]
		]
	],
	[
		'id' => 3,
		'num' => "3",
	]
];

$plain = [];

$test-> TreeToPlain($tree, $plain);
// print_r($plain);
 
$tree = [];
$test-> PlainToTree($plain, $tree);
print_r($tree);

