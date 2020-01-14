<?php
/**
 * Created by PhpStorm.
 * User: changchao
 * Date: 2020/1/10
 * Time: 16:18
 */
include __DIR__ ."./../../vendor/autoload.php";

use Cc\Io\Blocking\Worker;

$worker = new Worker("tcp://0.0.0.0:9800");
$worker->onConnect = function ($socket,$client){
    echo "有一个连接进来了：";
    var_dump($client);
    echo PHP_EOL;
};
$worker->onReceive = function (Worker $socket,$client,$data){

    $content = "from server: hello world client~ \r\n";
    echo "给连接" . intval($client) . "发送数据:{$client}";

    $http_response = "HTTP/1.1 200 OK\r\n";
    $http_response.= "Content-Type:text/html;charset=utf-8;\r\n";
    $http_response.= "Connection: keep-alive\r\n";
    $http_response.= "Server: php socket server\r\n";
    $http_response.= "Content-length: " . strlen($content) ."\r\n\r\n";
    $http_response.= $content;

    $socket->send($client,$http_response);
};



$worker->start();