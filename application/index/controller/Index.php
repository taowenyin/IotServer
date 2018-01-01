<?php
namespace app\index\controller;

use GatewayClient\Gateway;
use think\Controller;
use think\Request;
use think\Session;

class Index extends Controller
{
    public function index()
    {
        if (!Session::has('uid')) {
            Session::set('uid', uuid());
        }

        return $this->fetch();
    }

    public function bindConnect()
    {
        $res = [
            'res' => 'error'
        ];

        if (!Request::instance()->isPost()) {
            $res['msg'] = '数据提交方式操作';
        } else {
            $res['res'] = 'success';
            $res['client_id'] = Request::instance()->post('client_id');
            $res['uid'] = Request::instance()->session('uid');

//            Gateway::$registerAddress = '127.0.0.1:1236';
        }

        return $res;
    }
}
