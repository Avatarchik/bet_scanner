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
				'value' =>  $tree
			];
		}
	}
	
	public function PlainToCSV($data){
		$content = '';
		foreach ($data as $rec) {
			$line = implode(',', $rec['tags']) . ',:' . $rec['value'] . "\n";
			$content .= $line;
		}
		return $content;
	}
	
	public function TreeToFlat(&$tree, $childe_key, $parent_index, $index, $parent_id=null) {
		foreach ($tree as &$item) {
			$leaf = [];
			foreach ($item as $key=>$value) {
				if ($key != $childe_key) {	
					$leaf[$key] = $value;
				}
			}
			if (isset($parent_id)) {	
				$leaf[$parent_index] = $parent_id;
			}
			$flat[] = $leaf;
			
			if (isset($item[$childe_key])) {
				$leaves = $this->TreeToFlat($item[$childe_key], $childe_key, $parent_index, $index, $item[$index]);
				foreach ($leaves as $leaf) {
					$flat[] = $leaf;			
				}
			}	
		}
		return $flat;
	}
	
	public function FlatToTree(&$flat, $childe_key, $parent_index, $index, &$ltree_by_index, $id=null, $path=[], $layer=0) {
		$tree = null;
		$cnt = 0;
		foreach ($flat as &$item) {
			if (!(isset($id) || isset($item[$parent_index])) || (isset($item[$parent_index] ) && $item[$parent_index] == $id)) { 
				$branch = [];
				foreach ($item as $key=>$value) {
					if ($key != $parent_index) {	
						$branch[$key] = $value;
					}
				}
				
				$path[$layer] = $cnt;
				$ltree_by_index[$item[$index]] = $path;
				
				$childe = $this->FlatToTree($flat, $childe_key, $parent_index, $index, $ltree_by_index, $item[$index], $path, $layer+1);
				if (isset($childe)) {	
					$branch[$childe_key] = $childe;
				}
				$tree[] = $branch;
				
				unset($path[$layer]);
				$cnt += 1;
			}
		}
		return $tree;
	}
	
	public function GetByLtree(&$tree, $ltree, $child_key) {
		$l = null;
		$k = $ltree[0];
		if (count($ltree) > 1) {
			for ($i=0; $i<count($ltree)-1; $i++) {
				$ltree[$i] = $ltree[$i + 1];
			}
			unset($ltree[$i]);
			
			$l = $this->GetByLtree($tree[$k][$child_key], $ltree, $child_key);
		} else if (count($ltree) == 1) {
			return $tree[$k];
		}
		return $l;
	}

	public function SetByLtree($set_key, $value, &$tree, $ltree, $child_key) {
		$l = null;
		$k = $ltree[0];
		if (count($ltree) > 1) {
			for ($i=0; $i<count($ltree)-1; $i++) {
				$ltree[$i] = $ltree[$i + 1];
			}
			unset($ltree[$i]);
			
			$this->SetByLtree($set_key, $value, $tree[$k][$child_key], $ltree, $child_key);
		} else if (count($ltree) == 1) {
			$tree[$k][$set_key] = $value;
		}
	}
	
	

}
