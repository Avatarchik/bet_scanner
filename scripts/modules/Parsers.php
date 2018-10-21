<?php
class Parsers {
	
	
	private function _itemIsUnic($item, &$unic_items) {
		if (!in_array($item, $unic_items)) {
			$unic_items[] = $item;
			return true;
		}
		return false;
	
	}
	
	private function _parseFonbetLink($content) {
 		// foreach ($content as $key=>$item) {
			// print_r($key . "\n");
		// }
		
		$unic_items = [];

		if (!isset($content['sports'])) {
			//error: invalid FonbetLink content[\'sport\']'
			print('error: invalid FonbetLink content[\'sports\']' . "\n");
			return null;
		}
		if (!isset($content['events'])) {
			//error: invalid FonbetLink content[\'sport\']'
			print('error: invalid FonbetLink content[\'events\']' . "\n");
			return null;
		}
		if (!isset($content['eventBlocks'])) {
			//error: invalid FonbetLink content[\'sport\']'
			print('error: invalid FonbetLink content[\'eventBlocks\']' . "\n");
			return null;
		}
		if (!isset($content['customFactors'])) {
			//error: invalid FonbetLink content[\'sport\']'
			print('error: invalid FonbetLink content[\'customFactors\']' . "\n");
			return null;
		}
		
		$data = [];

		$sport_sports = $content['sports'];
		$segment_sports = $content['sports'];
		$unic_sports = [];
		foreach ($sport_sports as $sport_sport) {
			// if ($this->_itemIsUnic($sport['kind'], $unic_items)) {
				// print($sport['kind'] . "\n");
			// }
			
			if ($sport_sport['kind'] == 'sport') {
				if ($this->_itemIsUnic($sport_sport['id'], $unic_sports)) {
					$segments = [];
					$unic_segments = [];
					foreach ($segment_sports as $segment_sport) {
						if ($segment_sport['kind'] == 'segment') {	
							if ($this->_itemIsUnic($segment_sport['id'], $unic_segments)) {
								if ($segment_sport['parentId'] == $sport_sport['id']) {
									$segments[] = [
										'id' => $segment_sport['id'],
										'parentId' => $segment_sport['parentId'],
										'name' => $segment_sport['name']
									];
								}
							} else {
								//error: invalid FonbetLink content[\'sport\']'
								print('error: invalid non unic id into sport[\'kind\']=\'segment\'' . "\n");
								return null;
							}
						}
					}
					$data['sports'][] = [
						'id' => $sport_sport['id'],
						'name' => $sport_sport['name'],
						'segments' => $segments
					];
					
				} else {
					//error: invalid FonbetLink content[\'sport\']'
					print('error: invalid non unic id into sport[\'kind\']=\'sport\'' . "\n");
					return null;
				}
			}
		}
		
		$events = $content['events'];
		$eventBlocks = $content['eventBlocks'];
		$customFactors = $content['customFactors'];
		
		
		print_r($data);
		return $data;
	}
	
	public function ParseContent($content, $params) {
		if ($params['response']['parser'] == '_parseFonbetLink') {
			return $this->_parseFonbetLink($content);
		}
	}
	
}