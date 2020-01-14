<?php
/**
 * Created by PhpStorm.
 * User: changchao
 * Date: 2020/1/13
 * Time: 17:33
 */


$socket_address = "tcp://127.0.0.1:9801";
$client = stream_socket_client($socket_address,$errNo,$errStr);

if(!$client){
    echo "err：{$errStr} （{$errNo}）",PHP_EOL;
    exit;
}

// 设置为异步客户端
stream_set_blocking($client,false);

// 向服务端发送数据
fwrite($client,"hello world~");
echo PHP_EOL,"做其他事情...",PHP_EOL,PHP_EOL;


$i = 0;
while (!feof($client)){
    $data = fread($client,65535);
    echo $i++,PHP_EOL;
    sleep(1);
    var_dump($data);
}