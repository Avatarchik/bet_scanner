<?php
class Tools {
	function __construct() {
		$this->buff = [];
	}
	
	public function TreeToPlain($tree, &$output){
		if (is_array($tree)) {
			$mem = $this->buff;
			foreach ($tree as $key => $value) {
				$this->buff = $mem;
				$this->buff[] = $key;
				$this->TreeToPlain($tree[$key], $output);
			}
			$this->buff = $mem;
		} else {
			$output[] = [
				'tags' => $this->buff,
				'value' => $tree[0]
			];
			return null;
		}
	}

}
