<?php
/**
 * Created by PhpStorm.
 * User: taowenyin
 * Date: 18-1-1
 * Time: 上午7:36
 */

use GatewayWorker\Lib\Gateway;

class Events
{
    public static $clientSocketGroup = 1;
    public static $iotSocketGroup = 2;
//    public static $webSocketGroup = 3;

    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id)
    {
        Gateway::sendToClient($client_id, json_encode(array(
            'type' => 'onConnect',
            'client_id' => $client_id
        )));

        if ($_SERVER['GATEWAY_PORT'] == 7272) {
            print "$client_id Web Socket Connect\n";
        }

        if ($_SERVER['GATEWAY_PORT'] == 7276) {
            print "$client_id Client Socket Connect\n";

            \GatewayClient\Gateway::bindUid($client_id, self::uuid());
            \GatewayClient\Gateway::joinGroup($client_id, self::$clientSocketGroup);
        }

        if ($_SERVER['GATEWAY_PORT'] == 7280) {
            print "$client_id Iot Socket Connect\n";

            \GatewayClient\Gateway::bindUid($client_id, self::uuid());
            \GatewayClient\Gateway::joinGroup($client_id, self::$iotSocketGroup);
        }

//        // 向当前client_id发送数据
//        Gateway::sendToClient($client_id, "Hello $client_id");
//
//        // 向所有人发送
//        Gateway::sendToAll("$client_id login");
    }

    /**
     * 当客户端发来消息时触发
     * @param int $client_id 连接id
     * @param string $message 具体消息
     */
    public static function onMessage($client_id, $message)
    {
        // 向所有人发送
//        Gateway::sendToAll("$client_id said $message");

        Gateway::sendToClient($client_id, json_encode(array(
            'type' => 'message',
            'client_id' => $client_id,
            'message' => $message
        )));
    }

    /**
     * 当用户断开连接时触发
     * @param int $client_id 连接id
     */
    public static function onClose($client_id)
    {
        if ($_SERVER['GATEWAY_PORT'] == 7272) {
            print "$client_id Web Socket Close\n";
        }

        if ($_SERVER['GATEWAY_PORT'] == 7276) {
            print "$client_id Client Socket Close\n";
        }

        if ($_SERVER['GATEWAY_PORT'] == 7280) {
            print "$client_id Iot Socket Close\n";
        }

        // 向所有人发送
//        GateWay::sendToAll("$client_id logout");
    }

    public static function uuid() {
        if (function_exists ( 'com_create_guid' )) {
            return com_create_guid ();
        } else {
            mt_srand ( ( double ) microtime () * 10000 ); //optional for php 4.2.0 and up.随便数播种，4.2.0以后不需要了。
            $charid = strtoupper ( md5 ( uniqid ( rand (), true ) ) ); //根据当前时间（微秒计）生成唯一id.
            $hyphen = chr ( 45 ); // "-"
            $uuid = '' . //chr(123)// "{"
                substr ( $charid, 0, 8 ) . $hyphen . substr ( $charid, 8, 4 ) . $hyphen . substr ( $charid, 12, 4 ) . $hyphen . substr ( $charid, 16, 4 ) . $hyphen . substr ( $charid, 20, 12 );
            //.chr(125);// "}"
            return $uuid;
        }
    }
}