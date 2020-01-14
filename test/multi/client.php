<?php
/**
 * Created by PhpStorm.
 * User: changchao
 * Date: 2020/1/14
 * Time: 14:42
 */

$client = stream_socket_client("tcp://127.0.0.1:9802",$errNo,$errStr);

if(!$client){
    echo "err: {$errStr} ({$errNo})",PHP_EOL;
    exit;
}

// 设置非阻塞
stream_set_blocking($client,false);

$receiveData = "";

while (!feof($client)){
    $receiveData .= fread($client,65535);
    echo $receiveData,PHP_EOL;
    sleep(1);
}

echo PHP_EOL,PHP_EOL,"接收到的数据：",$receiveData,PHP_EOL;

// 发送数据
fwrite($client,"hello server ~");