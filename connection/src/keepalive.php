<?php
/**
 * Created by PhpStorm.
 * User: Jenner
 * Date: 2015/8/11
 * Time: 9:26
 *
 * 以下程序在不重新初始化curl的情况下，会保持链接鲜活
 */

$keep_alive = new KeepAlive();
$keep_alive->start();

class KeepAlive {
    public function start(){
        $curl = curl_init("http://www.baidu.com/s?word=xcx");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($curl, CURLOPT_TIMEOUT, 600);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_HEADER, true);

        while(true){
            $url = "http://www.baidu.com/s?word=" . mt_rand(0, 1000);
            curl_setopt($curl, CURLOPT_URL, $url);
            $result = curl_exec($curl);
            echo "get result:" . strlen($result) . PHP_EOL;
        }

    }
}