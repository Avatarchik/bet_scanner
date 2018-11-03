<?php
require('config/config.php');
require('scripts/modules/HTTP.php');
require('scripts/modules/DB.php');
require('scripts/modules/Tools.php');
require('scripts/modules/Errors.php');
require('scripts/modules/Parser.php');

class Scanner {
	function __construct() {
		$this->http = new HTTP();
		$this->db = new DB();
		$this->tools = new Tools();
		$this->errors = new Errors();
		$this->parser = new Parser();
		
		date_default_timezone_set('Asia/Karachi');
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
				$source_content = $this->http->GetLinkContent($params);
				$tree_content = $this->parser->ParseContent($source_content, $params);
				$site_data['_tree'][] = [
					'link_type' => $link_type,
					'content' => $tree_content
				];
				$site_data['_source'][] = [
					'link_type' => $link_type,
					'content' => $source_content
				];
			}
			$data[] = [
				'name' => $name,
				'data' => $site_data
			];
		}
		
		// $plain_data = [];
		// $this->tools->TreeToPlain($data, $plain_data);
		// $file_content = $this->tools->PlainToCSV($plain_data);
		// file_put_contents('data/test.txt', $file_content);
		
		foreach ($data as $site) {
			$file_content = json_encode($site['data']['_source'], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
			file_put_contents('data/' . $site['name'] . '_source.json', $file_content);
			
			$file_content = json_encode($site['data']['_tree'], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
			file_put_contents('data/' . $site['name'] . '_tree.json', $file_content);
		}
		
		print("\nOK\n");
	}

}

$scanner = new Scanner();
$scanner->Run();
