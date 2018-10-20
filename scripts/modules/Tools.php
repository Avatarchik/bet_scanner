<?php
class Tools {
	function __construct() {
	}
	
	public function TreeToPlain($tree, &$output, $buff=[]) {
		if (is_array($tree)) {
			$mem = $buff;
			foreach ($tree as $key => $value) {
				$buff = $mem;
				$buff[] = $key;
				$this->TreeToPlain($tree[$key], $output, $buff);
			}
			$buff = $mem;
		} else {
			$output[] = [
				'tags' => $buff,
				'value' => $tree[0]
			];
		}
	}

}
