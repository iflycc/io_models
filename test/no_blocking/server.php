<?php
/**
 * Created by PhpStorm.
 * User: changchao
 * Date: 2020/1/13
 * Time: 17:10
 *
 * 非阻塞 服务端
 */

require __DIR__ . "/../../vendor/autoload.php";
use Cc\Io\NoBlocking\Worker;

$socket_address ='tcp://0.0.0.0:9801';
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
