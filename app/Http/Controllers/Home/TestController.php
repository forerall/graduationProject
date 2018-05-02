<?php

namespace App\Http\Controllers\Home;

use App\Exceptions\ErrorException;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Article;
use App\Models\Game;
use App\Models\M\Beizhu;
use App\Models\Room;
use App\Services\GameService;
use App\Services\Ser\PushService;
use App\Tools\Pay\PayService;
use App\Tools\Push\Push;
use App\Tools\Sms\Wangyi;
use App\Tools\Vendor\Easemob;
use App\Tools\Vendor\Pinyin;
use App\User;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class TestController extends Controller
{

    protected $test = [
        [
            'desc' => '已注册',
            'multi' => true,//可多次发送
            'url' => 'http://api.dingdingyijian.com/sms/add/0',
            'method' => 'post',
            'params' => '{"mobile":"15280086732","type":"2","request":"2","time":"180"}',
            'result' => '{"code":0,"msg":"\u9a8c\u8bc1\u7801\u53d1\u9001\u6210\u529f"}',
            'header' => [
                'APP_ID: A6997184909726',
                'APP_KEY: 251C6B31-8BC8-B6AE-D663-11B68FB3B23B',
                'DEVICE_ID: D3F0FFDF-9676-4E44-9841-8DE3FF1DC47D',
                'APP_OS: ios#11.1.2',
                'APP_VERSION: 1',
            ]
        ],

    ];

    protected $postString = [
        [
            'desc' => '没注册过的手机号可以使用',
            'url' => 'http://www.hailanxiang.cn/frontoffice/step1',
            'method' => 'post',
            'params' => '{"phone":"15280086732","state":1,"code":"888888"}',
            'result' => ''
        ],
    ];
    protected $arr = [
        [
            'desc' => '可多次发送',
            'multi' => true,//可多次发送
            'url' => 'http://www.hailanxiang.cn/frontoffice/fetchcode',
            'method' => 'post',
            'params' => '{"phone":"15280086732","state":0}',
            'result' => 'json:{"success":true}'
        ],
        [
            'desc' => '可多次发送',
            'multi' => true,//可多次发送
            'url' => 'http://www.qiaotongniao.com/api/sendRegisterCode',
            'method' => 'post',
            'params' => '{"phone":"15280086732"}',
            'result' => '{"errCode":0,"errMsg":"\u53d1\u9001\u6210\u529f","status":0,"data":{}}'
        ],
        [
            'desc' => '可多次发送',
            'multi' => true,//可多次发送
            'url' => 'http://www.yuxiusi.com/api/sendLoginCode',
            'method' => 'post',
            'params' => '{"phone":"15280086732"}',
            'result' => '{"errCode":0,"errMsg":"\u53d1\u9001\u6210\u529f","status":0,"data":{}}'
        ],
        [
            'desc' => '可多次发送',
            'multi' => true,//可多次发送
            'url' => 'http://www.2yuanyou.cn/sendLoginCode',
            'method' => 'post',
            'params' => '{"phone":"15280086732"}',
            'result' => '{"errCode":0,"errMsg":"\u53d1\u9001\u6210\u529f","status":0,"data":{}}'
        ],
        [
            'desc' => '可多次发送',
            'multi' => true,//可多次发送
            'url' => 'http://api.jwxba.com/index.php?m=API&c=User&a=getLoginSms',
            'method' => 'post',
            'params' => '{"mobile":"15280086732","app_version":"2.0.4","app_channel":"iOS"}',
            'result' => '{"statusCode":200,"message":"\u77ed\u4fe1\u53d1\u9001\u6210\u529f"}',
            'resend' => 60,
        ],
        [
            'desc' => '未注册',
            'multi' => true,//可多次发送
            'url' => 'http://api.dingdingyijian.com/sms/add/0',
            'method' => 'post',
            'params' => '{"mobile":"15280086732","type":"1","request":"2","time":"180"}',
            'result' => '{"code":0,"msg":"\u9a8c\u8bc1\u7801\u53d1\u9001\u6210\u529f"}',
            'header' => [
                'APP_ID: A6997184909726',
                'APP_KEY: 251C6B31-8BC8-B6AE-D663-11B68FB3B23B',
                'DEVICE_ID: D3F0FFDF-9676-4E44-9841-8DE3FF1DC47D',
                'APP_OS: ios#11.1.2',
                'APP_VERSION: 1',
            ]
        ],
        [
            'desc' => '注册接口',
            'multi' => true,//可多次发送
            'url' => 'http://hz.exiuzhuangxiu.net.cn:8080/repair/exiu/sendSMS',
            'method' => 'post',
            'params' => '{"signature":"55D29B64B6244789A1E840FF8054049B","userName":"15280086732"}',
            'result' => '{"status":true,"info":"\u6210\u529f","data":null}',
            'resend' => 60,
        ]
    ];

    public function test()
    {
        $url = 'http://tuku-wap.m.jia.com/v1.2.4/api/user/registry/sendMobileCode';
        $params = json_decode('{"mobile":"15280086732","deviceid":"785EBB9E-3B28-47F6-A466-312C2AE68A4E","appkey":"afe1253c85413fe377b054a8e7d79c99","client_ip":"192.168.1.141","platform":"ios"}');
        $headers = [
            'Content-Type: application/json',
            'deviceid: 785EBB9E-3B28-47F6-A466-312C2AE68A4E',
            'appkey: afe1253c85413fe377b054a8e7d79c99',
            'platform: ios',
        ];
        $result = $this->curlPost($url, $params, $headers);
        echo json_encode($result) . '<br>';
        return;

        foreach ($this->arr as $val) {
            $url = $val['url'];
            $params = json_decode($val['params']);
            $headers = null;
            if (isset($val['header']) && $val['header']) {
                $headers = $val['header'];
            }
            $result = $this->curlPost($url, $params,$headers);
            echo json_encode($result) . '<br>';

        }


    }

    protected function curlPost($url, $params = '', $headers = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    // 要求结果为字符串且输出到屏幕上

        if (empty($headers)) {
            curl_setopt($ch, CURLOPT_HEADER, 0); // 不要http header 加快效率
        } else {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);    // post 提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $output = curl_exec($ch);
        //dd($output);
        curl_close($ch);
        return json_decode($output, true);
    }
}