<?php
const CFG = '{
	"db": {
		"dsn": "mysql:host=127.0.0.1;dbname=bets;charset=UTF-8",
		"user": "root",
		"pass": "root"
	}
}';

const BET_SITES = '{
	"bookmakers": [
		{
			"name": "ФОНБЕТ",
			"links": [
				{
					"type": "line",
					"params": {	
						"request": {
							"url": "line12.bkfon-resource.ru\/line\/currentLine\/ru\/",
							"ssl": true,
							"method": "GET",
							"body": [],
							"uagent": "Opera\/9.80 (Windows NT 6.1; WOW64) Presto\/2.12.388 Version\/12.14",
							"cacert_path": "cert\/curl-ca-bundle.crt"
						},
						"response": {
							"type": "json",
							"parser": "_parseFonbetLink"
						}
					}
				}
			]
			
		}
	
	]
}';