<?php
/**
 * Created by PhpStorm.
 * User: Jenner
 * Date: 2015/8/10
 * Time: 14:19
 *
 * 简单http socket客户端，实现一次HTTP请求
 */

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if(socket_connect($socket, "127.0.0.1", 1212) === false){
    echo "ERROR:" . socket_strerror(socket_last_error($socket)) . PHP_EOL;
    exit;
}

$request_headers = array(
    "GET / HTTP/1.1",
    "Host: xxx.xxx",
    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
);

$request = implode("\r\n", $request_headers);
// if comment the follow line, you will read nothing. because the request format is error.
$request .= "\r\n\r\n";
var_dump($request);

if(socket_write($socket, $request, strlen($request)) === false){
    echo "ERROR:" . socket_strerror(socket_last_error($socket)) . PHP_EOL;
    exit;
}

echo "the server will not response until time is out" . PHP_EOL;
$response = socket_read($socket, 1024);
if($response === false || empty($response)){
    echo "ERROR: timeout or socket error" . PHP_EOL;
    exit;
}
echo "response:" . PHP_EOL;
var_dump($response);

socket_close($socket);