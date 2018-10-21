<?php
require('config/config.php');
require('scripts/modules/HTTP.php');
require('scripts/modules/DB.php');
require('scripts/modules/Tools.php');
require('scripts/modules/Errors.php');
require('scripts/modules/Parsers.php');

class Scanner {
	function __construct() {
		$this->http = new HTTP();
		$this->db = new DB();
		$this->tools = new Tools();
		$this->errors = new Errors();
		$this->parsers = new Parsers();
	}

	public function Run() {
		$data = [];
		$bet_sites = json_decode(BET_SITES, true);
		$bookmakers = $bet_sites['bookmakers'];
		if (!isset($bookmakers)) {
			//error: invalid config
			print('error: invalid config' . "\n");
			return null;
		}
		
		foreach ($bookmakers as $bookmaker) {
			$name = $bookmaker['name'];
			$links = $bookmaker['links'];
			$site_data = [];
			foreach ($links as $link) {
				$link_type = $link['type'];
				$params = $link['params'];
				$content = $this->http->GetLinkContent($params);
				$content = $this->parsers->ParseContent($content, $params);
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
		
		$plain_data = [];
		$this->tools->TreeToPlain($data, $plain_data);
		$file_content = $this->tools->PlainToCSV($plain_data);
		file_put_contents('data/test.txt', $file_content);
		
		$file_content = json_encode($data, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		file_put_contents('data/test.json', $file_content);

		print("\nOK\n");
	}

}

$scanner = new Scanner();
$scanner->Run();
