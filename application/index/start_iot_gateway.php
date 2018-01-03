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
$iotGateway = new Gateway("tcp://0.0.0.0:7280");
// 设置名称，方便status时查看
$iotGateway->name = "DeviceIotGateway";
// 设置进程数，gateway进程数建议与cpu核数相同
$iotGateway->count = 4;
// gateway内部通讯起始端口，起始端口不要重复
$iotGateway->startPort = 2900;
// 心跳间隔
$iotGateway->pingInterval = 10;
// 心跳数据
$iotGateway->pingData = '{"type":"ping"}';
// 服务注册地址
$iotGateway->registerAddress = '127.0.0.1:1236';

// 如果不是在根目录启动，则运行runAll方法
if(!defined('GLOBAL_START'))
{
    Worker::runAll();
}