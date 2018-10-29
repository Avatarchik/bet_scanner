<?php
class Parser {
	function __construct() {
		$this->sport_index_by_id = [];
		$this->segment_index_by_id = [];
		$this->m_events_index_by_id = [];
		$this->s_events_index_by_id = [];
		
		$this->sport_index_by_segment_index = [];
		$this->segment_index_by_m_events_index = [];
		$this->m_events_index_by_s_events_index = [];
		
	}
	
	private function _itemIsUnic($item, &$unic_items) {
		if (!in_array($item, $unic_items)) {
			$unic_items[] = $item;
			return true;
		}
		return false;
	}
	
	private function _fillSportsBkp(&$data, $content) {
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
	
	private function _fillEventsBkp(&$data, $content) {
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
								'team1' => $master_event['team1'] ?? null,
								'team2' => $master_event['team2'] ?? null,
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
	
	
	private function _fillSports(&$data, $content) {
		if (!isset($content['sports'])) {
			//error: invalid FonbetLink content[\'sport\']'
			print('error: invalid FonbetLink content[\'sports\']' . "\n");
			return false;
		}
	
		$this->sport_index_by_id = [];
		$this->segment_index_by_id = [];
		$this->sport_index_by_segment_index  = [];
		
		$sports = $content['sports'];
		foreach ($sports as $_index=>$sport) {
			if ($sport['kind'] == 'sport') {
				$this->sport_index_by_id[$sport['id']] = $_index;
				
				$data['sports'][$_index] = [
					'id' => $sport['id'],
					'_index' => $_index,
					'name' => $sport['name'],
					'segments' => []
				];
			}
		}	
		
		$sports = $content['sports'];
		foreach ($sports as $_index=>$sport) {	
			if ($sport['kind'] == 'segment') {
				$sport_index = $this->sport_index_by_id[$sport['parentId']];
				$this->segment_index_by_id[$sport['id']] = $_index;
				$data['sports'][$sport_index]['segments'][$_index] = [
					'id' => $sport['id'],
					'_index' => $_index,
					'parentId' => $sport['parentId'],
					'name' => $sport['name'],
					'events' => []
				];
				
				$this->sport_index_by_segment_index[$_index] = $sport_index;
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
		
		$this->m_events_index_by_id = [];
		$this->s_events_index_by_id = [];
		$this->segment_index_by_m_events_index = [];
		$this->m_events_index_by_s_events_index = [];
		
		$events = $content['events'];
		foreach ($events as $_index=>$event) {
			if (!isset($event['parentId'])) {
				$segment_index = $this->segment_index_by_id[$event['sportId']];
				$this->m_events_index_by_id[$event['id']] = $_index;
				$sport_index = $this->sport_index_by_segment_index[$segment_index];
				
				$data['sports'][$sport_index]['segments'][$segment_index]['events'][$_index] = [
					'id' => $event['id'],
					'_index' => $_index,
					'sportId' => $event['sportId'],
					'startTime' => date('D, d F Y, H:i:s', $event['startTime']),
					'team1' => $event['team1'],
					'team2' => $event['team2'] ?? null,
					'events' => []
				];
				
				$this->segment_index_by_m_events_index[$_index] = $segment_index;
			}
		}
		
		
		$events = $content['events'];
		foreach ($events as $_index=>$event) {
			if (isset($event['parentId'])) {
				if (isset($this->m_events_index_by_id[$event['parentId']])) {
					
					$m_events_index = $this->m_events_index_by_id[$event['parentId']];
					$this->s_events_index_by_id[$event['id']] = $_index;
					$segment_index = $this->segment_index_by_m_events_index[$m_events_index];
					$sport_index = $this->sport_index_by_segment_index[$segment_index];
					
					$data['sports'][$sport_index]['segments'][$segment_index]['events'][$m_events_index]['events'][] = [
						'id' => $event['id'],
						'_index' => $_index,
						'parentId' => $event['parentId']
											
					];
				} else {
					print_r($event);
				}
					
			}
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
				// if ($this->_fill_customFactors($data, $content)) {
					return $data;
				// }
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