<?php
require('config/config.php'); 
require('scripts/modules/HTTP.php'); 
require('scripts/modules/DB.php'); 
require('scripts/modules/Tools.php'); 
require('scripts/modules/Errors.php'); 

class Scanner {
	function __construct() {
		$this->http = new HTTP();
	}
	
	public function Run() {
		$params = []; 
		$content = $this->http->GetContent($params);
		print_r($content);
		print("\nOK\n");
	}
}

$scanner = new Scanner();
$scanner->Run();

