<?php
/**
 * Created by PhpStorm.
 * User: changchao
 * Date: 2020/1/13
 * Time: 15:32
 */
$client = stream_socket_client("tcp://127.0.0.1:9800",$errNo,$errStr);
if(!$client){ // 连接失败，提醒报错
    echo "err: {$errStr} ({$errNo})",PHP_EOL;
    exit;
}

// 向服务端发送数据
fwrite($client,"hi:server~");

// 接收服务端消息
$response = fread($client,65535);

echo "serverResponse:",$response,PHP_EOL;
