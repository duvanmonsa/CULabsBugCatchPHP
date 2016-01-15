<?php

/**
 * @author: Renier Ricardo Figueredo
 * @mail: aprezcuba24@gmail.com
 */
namespace CULabs\BugCatch\Client;

class Client
{
	protected $url;
	protected $appKey;

	public function __construct($url, $appKey)
	{
		$this->url    = $url;
		$this->appKey = $appKey;
	}

	public function send(array $data)
	{
		$fields_string = http_build_query($data);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch,CURLOPT_URL, $this->url);
		curl_setopt($ch,CURLOPT_POST, count($data));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			sprintf('AUTHORIZATION: %s', $this->appKey),
		));
		curl_exec($ch);

		curl_close($ch);
	}
}