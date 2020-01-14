<?php
/**
 *
 */
namespace Cc\Io\NoBlocking;

class Worker{

	// 自定义服务的时间注册函数
    // 这三个是闭包函数
   public $onReceive = null;
   public $onConnect = null;
   public $onClose = null;
   public $socket = null;

    /**
     * Worker constructor.
     * @param $socket_address
     */
   public function __construct($socket_address){
       $this->socket = stream_socket_server($socket_address);
       echo $socket_address,PHP_EOL;
   }

    /**
     * 接受处理
     */
    private function _accept(){
        // 接收连接和处理使用
        while (true){
            $client = @stream_socket_accept($this->socket);
            if(!$client) continue;
            // 判断是不是回调函数
            if(is_callable($this->onConnect)){
                ($this->onConnect)($this,$client);
            }

            if(!is_resource($client)) continue; // 不是资源 跳出本次循环

            $buffer = fread($client,65535);
            // 接收到了数据，调用回调
            if(is_callable($this->onReceive)){
                ($this->onReceive)($this,$client,$buffer);
            }

            print_r($buffer);
//            fwrite($client,"from server: hi~\r\n");
            fclose($client); // 关闭接受socket
        }

        fclose($this->socket); // 关闭server socket
        echo "stream_socket_server@ 已关闭。\r\n";
    }

    /**
     * socket写数据，发消息
     * @param $client
     * @param $data
     */
    public function send($client,$data){
        fwrite($client,$data);
    }

    /**
     * 启动服务
     */
    public function start(){
        $this->_accept();
    }
}
