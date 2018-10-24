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
		
		$master_sports = $content['sports'];
		$sport_segments = $content['sports'];
		$unic_sports = [];
		foreach ($master_sports as $master_sport) {
			if ($master_sport['kind'] == 'sport') {
				$segments = [];
				$unic_segments = [];
				foreach ($sport_segments as $sport_segment) {
					if ($sport_segment['kind'] == 'segment') {	
						if ($sport_segment['parentId'] == $master_sport['id']) {
							$segments[] = [
								'id' => $sport_segment['id'],
								'parentId' => $sport_segment['parentId'],
								'name' => $sport_segment['name']
							];
						}
					}
				}
				$data['sports'][] = [
					'id' => $master_sport['id'],
					'name' => $master_sport['name'],
					'segments' => $segments
				];
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
		
		$master_events = $content['events'];
		$slave_events = $content['events'];
		foreach ($data['sports'] as &$sport) {
			foreach ($sport['segments'] as &$segment) {
				foreach ($master_events as $master_event) {
					if ($master_event['sportId'] == $segment['id']) {
						if (!isset($master_event['parentId'])) {
							$events = [];
							foreach ($slave_events as $slave_event) {
								if (isset($slave_event['parentId']) && $slave_event['parentId'] == $master_event['id']) {
									$events[] =  [
										'id' => $slave_event['id'],
										'sportId' => $slave_event['sportId'],
										'parentId' => $slave_event['parentId'],
										'startTime' => date('D, d F Y, H:i:s', $slave_event['startTime']),
									];
								}
							}
							$segment['events'][] = [
								'id' => $master_event['id'],
								'sportId' => $master_event['sportId'],
								'startTime' => date('D, d F Y, H:i:s', $master_event['startTime']),
								'events' => $events
							];
						}
					}
				}
			}
		}
		
		return true;
	}
	
	private function _fill_customFactors(&$data, $content) {
		if (!isset($content['customFactors'])) {
			//error: invalid FonbetLink content[\'sport\']'
			print('error: invalid FonbetLink content[\'customFactors\']' . "\n");
			return false;
		}
		
		$custom_factors = $content['customFactors'];
		
		foreach ($data['sports'] as &$sport) {
			// if ($sport['name'] == 'Теннис') {	
				foreach ($sport['segments'] as &$segment) {
					foreach ($segment['events'] as &$master_event) {
						foreach ($master_event['events'] as &$slave_event) {
							$customFactors = [];
							foreach ($custom_factors as $custom_factor){
								if ($slave_event['id'] == $custom_factor['e']) {
									$customFactors[] = [
										'e' => $custom_factor['e'],
										'f' => $custom_factor['f'],
										'v' => $custom_factor['v']
									];
								}
							}
							// print_r($customFactors);
							$slave_event['customFactors'] = $customFactors;
						}
					}
				}
			// }
		}
		
		return true;
	}
	
	
	
	private function _parseFonbetLink($content) {

		/*if (!isset($content['eventBlocks'])) {
			//error: invalid FonbetLink content[\'sport\']'
			print('error: invalid FonbetLink content[\'eventBlocks\']' . "\n");
			return null;
		}*/
		
		
		// $eventBlocks = $content['eventBlocks'];
		
		
		$data = [];
		if ($this->_fillSports($data, $content)) {
			if ($this->_fillEvents($data, $content)) {
				if ($this->_fill_customFactors($data, $content)) {
					return $data;
				}
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