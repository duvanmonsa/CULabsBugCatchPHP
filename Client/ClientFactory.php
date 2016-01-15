<?php

/**
 * @author: Renier Ricardo Figueredo
 * @mail: aprezcuba24@gmail.com
 */
namespace CULabs\BugCatch\Client;

class ClientFactory
{
    const URI = 'http://bugcatches.com/app_api.php/api/errors.json';

    protected $appKey;

    public function __construct($appKey)
    {
        $this->appKey = $appKey;
    }
    public function getClient()
    {
        return new Client(self::URI, $this->appKey);
    }
}