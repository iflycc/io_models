<?php
/**
 * 多路复用 server
 */

require __DIR__ . "/../../vendor/autoload.php";
use Cc\Io\Multi\Worker;

$socket_address ='tcp://0.0.0.0:9802';
$server = new Worker($socket_address);


// 设置回调函数（连接） -- stream_socket_accept创建的就是 client
$server->onConnect = function (Worker $server,$client){
    $clientId = intval($client);
    echo "有个连接进入：{$clientId}",PHP_EOL;
};

// 设置回调函数（接收数据）
$server->onReceive = function (Worker $server,$client,$data){
    $clientId = intval($client);
    echo "接收到客户端（{$clientId}）的数据：",$data,PHP_EOL;
    sleep(3);
    // 回复客户端
    $server->send($client,"server: hi client {$client}~\n");
};

$server->start(); // 开启服务监听