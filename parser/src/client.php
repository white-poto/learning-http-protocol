<?php
/**
 * Created by PhpStorm.
 * User: Jenner
 * Date: 2015/8/10
 * Time: 11:58
 */

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if(!socket_connect($socket, "www.baidu.com", 80)){
    echo socket_last_error($socket) . PHP_EOL;
    exit;
}

$request = <<<GLOB_MARK
GET / HTTP/1.1
Host: www.baidu.com
Connection: keep-alive
Cache-Control: max-age=0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8
Upgrade-Insecure-Requests: 1
User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.125 Safari/537.36
Accept-Encoding: gzip, deflate, sdch
Accept-Language: zh-CN,zh;q=0.8,en;q=0.6
GLOB_MARK;


if(!socket_write($socket, $request, strlen($request))){
    echo socket_last_error($socket) . PHP_EOL;
    exit;
}

$response = socket_read($socket, 1024);
echo $response . PHP_EOL;
