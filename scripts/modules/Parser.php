<?php
class Parser {
	function __construct() {
		$this->tools = new Tools();
		$this->sport_ltree_by_id = [];
		$this->event_ltree_by_id = [];
	}
	
	private function _itemIsUnic($item, &$unic_items) {
		if (!in_array($item, $unic_items)) {
			$unic_items[] = $item;
			return true;
		}
		return false;
	}
	
	private function _fillSports(&$data, $content) {
		$sports = $content['sports'];
		$data = $this->tools->FlatToTree($sports, 'segments', 'parentId', 'id', $this->sport_ltree_by_id);
		return true;
	}
	
	private function _fillEvents(&$data, $content) {
		$events = $content['events'];
		$data = $this->tools->FlatToTree($events, 'events', 'parentId', 'id', $this->event_ltree_by_id);
		return true;
	}
	
	private function _fill_customFactors(&$data, $content) {
		$custom_factors = $content['customFactors'];
		
		return true;
	}
	
	private function _parseFonbetLink($content) {
		$data = [];
		// if ($this->_fillSports($data, $content)) {
			if ($this->_fillEvents($data, $content)) {
				// if ($this->_fill_customFactors($data, $content)) {
					return $data;
				// }
			}
		// }
		
		return [];
	}
	
	public function ParseContent($content, $params) {
		if ($params['response']['parser'] == '_parseFonbetLink') {
			return $this->_parseFonbetLink($content);
		}
	}
	
}