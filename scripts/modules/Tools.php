<?php
class Tools {

public function tree_plaine($arr, $line){
	if (is_array($arr)) {
		foreach ($arr as $key => $value) {
			$line .= '[' . $key . ']' ;
		  $this->tree_plaine($arr[$key], $line);
		//	$this->tree_plaine($arr[$key], $line);
		$line = '';
		}


	} else {
			print($line . $arr[0] ."\n");
			return $line;

	}

 //print_r ($arr);





}

}
