<?php
require('config/config.php'); 
require('scripts/modules/HTTP.php'); 
require('scripts/modules/DB.php'); 
require('scripts/modules/Tools.php'); 
require('scripts/modules/Errors.php'); 

class Scanner {
	public function Run() {
		print(TEST_CONST . "\n");
	}
}

$scanner = new Scanner();
$scanner->Run();

