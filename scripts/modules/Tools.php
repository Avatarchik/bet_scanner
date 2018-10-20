<?php
class Tools {
	function __construct() {
	}
	
	public function TreeToPlain($tree, $line, &$output){
		if (is_array($tree)) {
			$mem = $line;
			foreach ($tree as $key => $value) {
				$line = $mem;
				$line[] = $key;
				$this->TreeToPlain($tree[$key], $line, $output);
			}
			$line = $mem;
		} else {
			$output[] = [
				'tags' => $line,
				'value' => $tree[0]
			];
			return null;
		}
	}

}
