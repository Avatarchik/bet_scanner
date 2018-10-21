<?php
class Parser {
	
	
	private function _itemIsUnic($item, &$unic_items) {
		if (!in_array($item, $unic_items)) {
			$unic_items[] = $item;
			return true;
		}
		return false;
	}

	private function _fillSports(&$data, $content) {
		if (!isset($content['sports'])) {
			//error: invalid FonbetLink content[\'sport\']'
			print('error: invalid FonbetLink content[\'sports\']' . "\n");
			return false;
		}
		
		$sport_sports = $content['sports'];
		$segment_sports = $content['sports'];
		$unic_sports = [];
		foreach ($sport_sports as $sport_sport) {
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
								//error: non unic id into sport[\'kind\']=\'segment\''
								print('error: non unic id into sport[\'kind\']=\'segment\'' . "\n");
								return [];
							}
						}
					}
					$data['sports'][] = [
						'id' => $sport_sport['id'],
						'name' => $sport_sport['name'],
						'segments' => $segments
					];
					
				} else {
					//error: non unic id into sport[\'kind\']=\'sport\''
					print('error: non unic id into sport[\'kind\']=\'sport\'' . "\n");
					return [];
				}
			}
		}

		return true;
	}
	
	private function _fillEvents(&$data, $content) {
		if (!isset($content['events'])) {
			//error: invalid FonbetLink content[\'sport\']'
			print('error: invalid FonbetLink content[\'events\']' . "\n");
			return false;
		}
		
		
		$all_event_keys = [];
		$events = $content['events'];
		foreach ($data['sports'] as &$sport) {
			$all_event_keys[$sport['name']] = []; ////
			$unic_event_keys = []; /////
			foreach ($sport['segments'] as &$segment) {
				foreach ($events as $event) {
					if ($event['sportId'] == $segment['id']) {
						$segment['events'][] = [
							'id' => $event['id'],
							'sportId' => $event['sportId'],
							'startTime' => date('D, d F Y, H:i:s', $event['startTime']),
						];
						
						foreach ($event as $key=>$value) { ////
							if ($this->_itemIsUnic($key, $unic_event_keys)) { ////
								$all_event_keys[$sport['name']][] = $key; /////
							} ////
						} ////
					}
				}
				
				//print($segment['id'] . "\n");
			}
			sort($all_event_keys[$sport['name']]); ////
		}
		print_r($all_event_keys);
		
		return true;
	}
	
	private function _parseFonbetLink($content) {

		/*if (!isset($content['eventBlocks'])) {
			//error: invalid FonbetLink content[\'sport\']'
			print('error: invalid FonbetLink content[\'eventBlocks\']' . "\n");
			return null;
		}
		if (!isset($content['customFactors'])) {
			//error: invalid FonbetLink content[\'sport\']'
			print('error: invalid FonbetLink content[\'customFactors\']' . "\n");
			return null;
		}*/
		
		// $eventBlocks = $content['eventBlocks'];
		// $customFactors = $content['customFactors'];*/
		
		$data = [];
		if ($this->_fillSports($data, $content)) {
			if ($this->_fillEvents($data, $content)) {
				return $data;
			}
		}
		
		return [];
		
	}
	
	public function ParseContent($content, $params) {
		if ($params['response']['parser'] == '_parseFonbetLink') {
			return $this->_parseFonbetLink($content);
		}
	}
	
}