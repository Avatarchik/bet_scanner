<?php
class Parsers {
	
	private function _parseFonbetLink($content) {
		
		return $content;
	}
	
	public function ParseContent($content, $params) {
		if ($params['response']['parser'] == '_parseFonbetLink') {
			return $this->_parseFonbetLink($content);
		}
	}
	
}