<?php
require('config/config.php');
require('scripts/modules/HTTP.php');
require('scripts/modules/DB.php');
require('scripts/modules/Tools.php');
require('scripts/modules/Errors.php');

class Scanner {
	function __construct() {
		$this->http = new HTTP();
		$this->db = new DB();
		$this->tools = new Tools();

	}

	public function Run() {
		$data = [];
		$bet_sites = json_decode(BET_SITES, true);
		$providers = $bet_sites['providers'];
		foreach ($providers as $provider) {
			$name = $provider['name'];
			$links = $provider['links'];
			$site_data = [];
			foreach ($links as $link) {
				$link_type = $link['type'];
				$params = [
					'url' => $link['url'],
					'method' => $link['method'],
					'body' => $link['body'],
					'content_type' => $link['content_type']
				];
				$content = $this->http->GetContent($params);
				$site_data[] = [
					$link_type => [
						'content' => $content
					]
				];
			}
			$data[] = [
				$name => $site_data
			];
		}

		print_r($data);
		// print(getcwd() . "\n");
		print("\nOK\n");
	}


public function test($arr) {
	//	$this->db->connect_db($params);
	$line = '';
	$line = $this->tools->tree_plaine($arr, $line);
	return $line;

	}
}

$scanner = new Scanner();

$params = [

	'host' => '127.0.0.1',
	'db' => 'testdb',
	'usr' => 'root',
	'pass' => 'root',
	'charset' => 'utf8'
];
$arr = [
	'one' => [
		'one_one' => '1',
		'one_two' => '2',
	],
	'three' => '3'
];
$scanner->test($arr);

//$scanner->Run();
