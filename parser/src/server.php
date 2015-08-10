<?php

/**
 * Created by PhpStorm.
 * User: Jenner
 * Date: 2015/8/10
 * Time: 14:00
 */


$server = new Server();
$server->registerHandler(function($header, $body){
    echo "get request: " . time() . PHP_EOL;
    echo "header: " . PHP_EOL . $header;
    echo "body: " . PHP_EOL . $body;
});

class Server
{

    protected $cache;

    protected $handler;

    public function start()
    {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_bind($socket, '0.0.0.0', 1212);
        socket_listen($socket);

        while ($connection = socket_accept($socket)) {
            $bytes = socket_read($connection, 1024);
            $this->parse($bytes);
        }
    }

    public function registerHandler($handler)
    {
        $this->handler = $handler;
    }

    protected function parse($data)
    {
        $data = $this->cache . $data;
        $header = $body = "";
        if (strstr("\r\n\r\n", $data) === false) {
            return $data;
        }

        $http_info = explode("\r\n\r\n", $data, 2);
        $header = $http_info[0];
        $body = $http_info[1];

        if (empty($body)) {
            call_user_func($this->handler, $header, null);
        }

        $content_length = $this->getContentLength($header);
        if ($content_length == 0) {
            call_user_func($this->handler, $header, null);
        } else {
            $body = substr($body, 0, $content_length);
            $this->cache = substr($body, $content_length);
            call_user_func($this->handler, $header, $body);
        }
    }

    protected function getContentLength($headers)
    {
        $headers = explode("\r\n", $headers);
        foreach ($headers as $header) {
            if (stristr("content-length", $headers) === false) continue;
            $content_length = intval(explode(":", $header, 2)[1]);
            return $content_length;
        }

        return 0;
    }

}