<?php

/**
 * @author: Renier Ricardo Figueredo
 * @mail: aprezcuba24@gmail.com
 */
namespace CULabs\BugCatch\ErrorHandler;

use GuzzleHttp\Client;

class ErrorHandler
{
    protected $client;
    protected $post;
    protected $get;
    protected $cookie;
    protected $filesPost;
    protected $server;
    protected $userData = [];
    protected $activate;

    public function __construct(Client $client, $activate = true)
    {
        $this->client   = $client;
        $this->activate = $activate;
    }

    public function notifyException(\Exception $exception)
    {
        $this->createFromGlobals();
        $exception = FlattenException::create($exception);
        $this->sendRequest([
          'form_params' => [
            'method'    => isset($this->server['REQUEST_METHOD'])? $this->server['REQUEST_METHOD']: '',
            'host'      => isset($this->server['HTTP_HOST'])? $this->server['HTTP_HOST']: '',
            'uri'       => isset($this->server['REQUEST_URI'])? $this->server['REQUEST_URI']: '',
            'scheme'    => isset($this->server['REQUEST_SCHEME'])? $this->server['REQUEST_SCHEME']: '',
            'userData'  => json_encode($this->userData),
            'post'      => json_encode($this->post),
            'get'       => json_encode($this->get),
            'cookie'    => json_encode($this->cookie),
            'filesPost' => json_encode($this->filesPost),
            'server'    => json_encode($this->server),
            'errors'    => $exception->toArray(),
          ],
        ]);
    }

    public function notifyCommandException(\Exception $exception)
    {
        $this->createFromGlobals();
        $exception = FlattenException::create($exception);
        $uri = '';
        foreach ($this->server['argv'] as $key => $item) {
            if ($key == 0) {
                continue;
            }
            $uri .= ' '.$item;
        }
        $this->sendRequest([
          'form_params' => [
            'host'   => isset($this->server['argv'][0])? $this->server['argv'][0]: '',
            'uri'    => $uri,
            'errors' => $exception->toArray(),
          ],
        ]);
    }

    protected function sendRequest($data)
    {
        if (!$this->activate) {
            return;
        }
        $this->client->request('POST', 'errors.json', $data);
    }

    protected function createFromGlobals()
    {
        if (!$this->post) {
            $this->post = $_POST;
        }
        if (!$this->get) {
            $this->get = $_GET;
        }
        if (!$this->cookie) {
            $this->cookie = $_COOKIE;
        }
        if (!$this->filesPost) {
            $this->filesPost = $_FILES;
        }
        if (!$this->server) {
            $this->server = $_SERVER;
        }
    }

    /**
     * @return mixed
     */
    public function getUserData()
    {
        return $this->userData;
    }

    /**
     * @param mixed $userData
     */
    public function setUserData($userData)
    {
        $this->userData = $userData;
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param mixed $post
     */
    public function setPost($post)
    {
        $this->post = $post;
    }

    /**
     * @return mixed
     */
    public function getGet()
    {
        return $this->get;
    }

    /**
     * @param mixed $get
     */
    public function setGet($get)
    {
        $this->get = $get;
    }

    /**
     * @return mixed
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * @param mixed $cookie
     */
    public function setCookie($cookie)
    {
        $this->cookie = $cookie;
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param mixed $files
     */
    public function setFiles($files)
    {
        $this->filesPost = $files;
    }

    /**
     * @return mixed
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @param mixed $server
     */
    public function setServer($server)
    {
        $this->server = $server;
    }
}