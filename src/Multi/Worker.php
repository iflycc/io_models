<?php
/**
 *IO多路复用
 */
namespace Cc\Io\Multi;

class Worker{

	// 自定义服务的时间注册函数
    // 这三个是闭包函数
    public $onReceive = null;
    public $onConnect = null;
    public $onClose = null;
    public $socket = null;

    public $socketReadable = []; //  可读的 socket
    public $socketWritable = []; // 可写的 socket


    /**
     * Worker constructor.
     * @param $socket_address
     */
   public function __construct($socket_address){
       $this->socket = stream_socket_server($socket_address);
       echo $socket_address,PHP_EOL;

       stream_set_blocking($this->socket,0); // 设置创建的socket为非阻塞
       // 添加 socket 到检测队列中
       $this->socketReadable[intval($this->socket)] = $this->socket;
   }

    /**
     * 接受处理
     */
    private function _accept(){
        // 接收连接和处理使用
        while (true){

            $read = $this->socketReadable;
            // 检测可读 / 可写 的socket连接
            var_dump($read);

            echo PHP_EOL,"开始检测：ing...",PHP_EOL;

            stream_select($read,$write,$except,0);
            var_dump($read);
            var_dump($write);
            var_dump($except);

            foreach ($read as $_socketReadable){
                if($_socketReadable === $this->socket){ // 表示当前创建的服务端socket可用
                    $this->_createSocket();
                }
                else{
                    $this->_sendMsg($_socketReadable); // 如果不是当前资源 那就是监听的已接受的资源，则向接受的套接字里面写数据
                }
            }
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

    /**
     * 阻塞
     */
    private function _createSocket(){
        $client = @stream_socket_accept($this->socket); // 接受server创建的套接字连接
        // 判断是不是回调函数
        if(is_callable($this->onConnect)){
            ($this->onConnect)($this,$client);
        }
        $this->socketReadable[intval($client)] = $client; // 把接受的套接字资源也加入到监听列表中


    }

    /**
     * @param $client
     * @return
     */
    private function _sendMsg($client){
        $buffer = fread($client,65535);
        if(!$buffer) return null;
        print_r($buffer);

        // 接收到了数据，调用回调
        if(is_callable($this->onReceive)){
            ($this->onReceive)($this,$client,$buffer);
        }
    }
}
