<?php
/**
 * Created by PhpStorm.
 * User: Jenner
 * Date: 2015/8/10
 * Time: 11:58
 */

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if(socket_connect($socket, "www.sina.com", 80) === false){
    echo "ERROR:" . socket_last_error($socket) . PHP_EOL;
    exit;
}

$request_headers = array(
    "GET / HTTP/1.1",
    "Host: www.sina.com",
    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
);

$request = implode("\r\n", $request_headers);
$request .= "\r\n\r\n";

if(socket_write($socket, $request, strlen($request)) === false){
    echo "ERROR:" . socket_last_error($socket) . PHP_EOL;
    exit;
}

echo "we will get a error response because the request string is error." . PHP_EOL;
$response = socket_read($socket, 1024);
if($response === false){
    echo "ERROR:" . socket_last_error($socket);
    exit;
}
echo "response:" . PHP_EOL;
var_dump($response);



