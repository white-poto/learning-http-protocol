<?php
/**
 * Created by PhpStorm.
 * User: Jenner
 * Date: 2015/8/11
 * Time: 9:26
 */

$keep_alive = new KeepAlive();
$keep_alive->start();

class KeepAlive {
    public function start(){
        $curl = curl_init("http://www.google.com");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($curl, CURLOPT_TIMEOUT, 600);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        $result = curl_exec($curl);
        echo $result;
    }
}