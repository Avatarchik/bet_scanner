<?php
class HTTP {
	private function _request($params) {
		$response = [];
		
		$ch = curl_init();
		//---- set curl options
		if ($params['ssl']) { //set SSL options
			curl_setopt($ch, CURLOPT_URL, 'https://' . $params['url']); //установка URL
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_CAINFO, $params['cacert_path']);
		} else {
			curl_setopt($ch, CURLOPT_URL, 'http://' . $params['url']); //установка URL
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   				// возвращает веб-страницу
		curl_setopt($ch, CURLOPT_HEADER, 0);           				// не возвращает заголовки
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);   				// переходит по редиректам
		curl_setopt($ch, CURLOPT_ENCODING, '');        				// обрабатывает все кодировки
		curl_setopt($ch, CURLOPT_USERAGENT, $params['uagent']);  	// useragent
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120); 				// таймаут соединения
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);        				// таймаут ответа
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);       				// останавливаться после 10-ого редиректа
		if ($params['method'] == 'POST')  {
			//set POST options
		}
		
		$content = curl_exec($ch);
		$response['content'] = $content;
		$response['errno'] = curl_errno($ch);
		$response['errmsg'] = curl_error($ch);
		//$response['info'] = curl_getinfo($ch);
		curl_close($ch);
		
		return $response;
	}
	
	public function GetLinkContent($params) { 
		$content = [];
		
		$response = $this->_request($params['request']);

		if ($params['response']['type'] == 'json') {
			$response['content'] = json_decode($response['content'], true);
		}
		
		if ($response['errno'] == 0) {
			$content = $response['content'];
		} else {
			//error: site_request_error
			print('site' . $params['url'] . 'request_error' . "\n");
		}

		return $content;
	}
}