<?php
/**
 * Created by PhpStorm.
 * User: taowenyin
 * Date: 18-1-1
 * Time: 上午7:37
 */

use Workerman\Worker;
use GatewayWorker\Gateway;
use Workerman\Autoloader;

// gateway 进程
$webGateway = new Gateway("websocket://0.0.0.0:7272");
// 设置名称，方便status时查看
$webGateway->name = "WebIotGateway";
// 设置进程数，gateway进程数建议与cpu核数相同
$webGateway->count = 4;
// 内部通讯起始端口，假如$gateway->count=4，起始端口为4000
// 则一般会使用4001 4002 4003 4004 4个端口作为内部通讯端口
$webGateway->startPort = 2700;
// 心跳间隔
$webGateway->pingInterval = 10;
// 心跳数据
$webGateway->pingData = '{"type":"ping"}';
// 服务注册地址
$webGateway->registerAddress = '127.0.0.1:1236';

// 如果不是在根目录启动，则运行runAll方法
if(!defined('GLOBAL_START'))
{
    Worker::runAll();
}