<?php
class HTTP {
	public function GetContent($params){
		$data = [];
		$url = $params['url'];
		$method = $params['method'];
		$body = $params['body'];
		$content_type = $params['content_type'];
		
		
		$uagent = "Opera/9.80 (Windows NT 6.1; WOW64) Presto/2.12.388 Version/12.14";

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   // возвращает веб-страницу
		curl_setopt($ch, CURLOPT_HEADER, 0);           // не возвращает заголовки
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);   // переходит по редиректам
		curl_setopt($ch, CURLOPT_ENCODING, "");        // обрабатывает все кодировки
		curl_setopt($ch, CURLOPT_USERAGENT, $uagent);  // useragent
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120); // таймаут соединения
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);        // таймаут ответа
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);       // останавливаться после 10-ого редиректа
		//SSL
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_CAINFO, 'cert/cacert.pem');

		$content = curl_exec($ch);
		$errno = curl_errno($ch);
		$errmsg = curl_error($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		
		if ($content_type == 'json') {
			$content = json_decode($content, true);
		} else {
			$content = [];
		}

		$data['info'] = $info;
		$data['errno'] = $errno;
		$data['errmsg'] = $errmsg;
		$data['content'] = $content;
		
		return $data;
	}
}