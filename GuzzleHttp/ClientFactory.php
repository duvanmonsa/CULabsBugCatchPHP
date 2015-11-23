<?php

/**
 * @author: Renier Ricardo Figueredo
 * @mail: aprezcuba24@gmail.com
 */
namespace CULabs\BugCatch\GuzzleHttp;

use GuzzleHttp\Client;

class ClientFactory
{
    const BASE_URI = 'http://bugcatch.localhost/app_api_dev.php/api/';

    protected $appKey;

    public function __construct($appKey)
    {
        $this->appKey = $appKey;
    }
    public function getClient()
    {
        $client = new Client([
            'base_uri' => self::BASE_URI,
            'headers'  => [
                'Authorization' => $this->appKey,
            ],
        ]);

        return $client;
    }
}