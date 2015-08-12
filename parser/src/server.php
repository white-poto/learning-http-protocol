<?php

/**
 * Created by PhpStorm.
 * User: Jenner
 * Date: 2015/8/10
 * Time: 14:00
 *
 * 简单http server端协议解析，实现粘包、缺包，拆包
 */


$server = new Server();
//注册响应回调函数
$server->registerHandler(function($connection, $header, $body){
    echo "get request: " . time() . PHP_EOL;
    echo "header: " . PHP_EOL . $header . PHP_EOL;
    echo "body: " . PHP_EOL . $body . PHP_EOL;
    $response = "HTTP/1.1 200 OK\r\n";
    $response .= "Date: Mon, 10 Aug 2015 06:22:08 GMT\r\n";
    $response .= "Content-Type: text/html;charset=utf-8\r\n\r\n";

    socket_write($connection, $response, strlen($response));
});

//启动server
$server->start();


/**
 * 简易http server 支持http协议解析
 * Class Server
 */
class Server
{

    /**
     * @var string 字符流缓存
     */
    protected $cache = "";

    /**
     * @var http请求处理器
     */
    protected $handler;

    /**
     * 启动server
     */
    public function start()
    {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_bind($socket, '0.0.0.0', 1212);
        socket_listen($socket);

        while ($connection = socket_accept($socket)) {
            echo "connected" . PHP_EOL;
            while(true){
                $bytes = socket_read($connection, 1);
                echo "read: " . $bytes . PHP_EOL;
                if(empty($bytes)) {
                    sleep(1);
                    continue;
                }
                if($this->parse($connection, $bytes)){
                    break;
                }
                echo "parse" . PHP_EOL;
                usleep(100);
            }
        }
    }

    /**
     * 注册处理器
     * @param $handler
     */
    public function registerHandler($handler)
    {
        $this->handler = $handler;
    }

    /**
     * http协议解析
     * @param $connection
     * @param $data
     * @return bool
     */
    protected function parse($connection, $data)
    {
        $data = $this->cache . $data;
        var_dump($data);
        $header = $body = "";
        if (strstr($data, "\r\n\r\n") === false) {
            $this->cache = $data;
            echo "cached" . PHP_EOL;
            return false;
        }

        $http_info = explode("\r\n\r\n", $data, 2);
        $header = $http_info[0];
        $body = count($http_info) > 1 ? $http_info[1] : 0;

        $content_length = $this->getContentLength($header);


        if ($content_length == 0) { // 正好是一个http帧
            call_user_func($this->handler, $connection, $header, null);
            socket_close($connection);
            return true;
        } elseif($content_length > strlen($body)){ // 缺少body部分
            $this->cache = $data;
            return false;
        } else { // 发生粘包，摘出当前包，缓存剩余部分
            $body = substr($body, 0, $content_length);
            $this->cache = substr($body, $content_length);
            call_user_func($this->handler, $connection, $header, $body);
            return false;
        }
    }

    /**
     * 获取content-length
     * @param $headers
     * @return int
     */
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
