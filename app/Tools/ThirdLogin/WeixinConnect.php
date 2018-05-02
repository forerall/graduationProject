<?php
namespace App\Tools\ThirdLogin;

use Illuminate\Support\Facades\Input;

class WeixinConnect {

    protected $appId = 'wx70373e7eb13fd4f8';
    protected $appKey = '205ed28216a8c91fca956093c73a552e';
    protected $callbackUrl = '/thirdCallback?type=wx';
    protected $accessToken;
    protected $openid;



    /**
     * 登陆url
     * @return string
     */
    public function loginUrl(){
        $callback = Input::root().$this->callbackUrl;
        $callback = urlencode($callback);
        $state = md5(uniqid(rand(), TRUE)); //CSRF protection
        $response_type = 'code';
        $scope = 'snsapi_login';
        $url = "https://open.weixin.qq.com/connect/qrconnect?"
            ."appid={$this->appId}&redirect_uri={$callback}&response_type={$response_type}&scope={$scope}&state={$state}#wechat_redirect";
        return $url;
    }
    /**
     * 回调处理返回 uid
     * @param $code
     * @return mixed
     * @throws \Exception
     */
    public function callBack($code){
        $this->getAccessToken($code);
        return $this->getUid();
    }
    /**
     * 获取用户信息
     * @param $uid
     * @return mixed
     * @throws \Exception
     */
    public function getUserInfo($uid){
        if(empty($this->accessToken)){
            throw new \Exception('AccessToken is empty!');
        }
        if(empty($uid)){
            throw new \Exception('Uid is empty!');
        }
        $url = "https://api.weixin.qq.com/sns/userinfo";
        $param = array(
            "access_token"    => $this->accessToken,
            "openid"    => $uid
        );
        $result = $this->get($url,$param);
        $result = json_decode($result,true);
        return $result;
    }

    protected function getAccessToken($code){
        $callback = Input::root().$this->callbackUrl;
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?"
            ."appid={$this->appId}&secret={$this->appKey}&code={$code}&grant_type=authorization_code";
        $result = $this->post($url);
        $result = json_decode($result,true);
        $this->accessToken = $result['access_token'];
        $this->openid = $result['openid'];
    }
    protected function getUid(){
        return $this->openid;
    }

    protected function get($url, $param = null) {
        if($param != null) {
            $query = http_build_query($param);
            $url = $url . '?' . $query;
        }
        $ch = curl_init();
        if(stripos($url, "https://") !== false){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        $content = curl_exec($ch);
        $status = curl_getinfo($ch);
        curl_close($ch);

        if($status["http_code"]==200){
            return $content;
        }else{
            throw new \Exception("Status {$status["http_code"]} Get://{$url} error:".$content);
        }
    }
    protected function post($url, $params = '') {
        $ch = curl_init();
        if(stripos($url, "https://") !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $content = curl_exec($ch);
        $status = curl_getinfo($ch);
        curl_close($ch);

        if($status["http_code"]==200){
            return $content;
        }else{
            throw new \Exception("Status {$status["http_code"]} Post://{$url} error:".$content);
        }


    }


}
