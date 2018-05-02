<?php
namespace App\Tools\ThirdLogin;

use Illuminate\Support\Facades\Input;

class QQConnect {

    protected $appId = '101373231';
    protected $appKey = '32c64b355f3b41563985dedcdbb816ce';
    protected $callbackUrl = '/thirdCallback?type=qq';
    protected $accessToken;



    /**
     * 登陆url
     * @return string
     */
    public function loginUrl(){
        $callback = Input::root().$this->callbackUrl;
        $callback = urlencode($callback);
        $state = md5(uniqid(rand(), TRUE)); //CSRF protection
        $response_type = 'code';
        $scope = 'get_user_info';
        $url = "https://graph.qq.com/oauth2.0/authorize?"
            ."response_type={$response_type}&client_id={$this->appId}&state={$state}&scope={$scope}&redirect_uri={$callback}";
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
        $url = "https://graph.qq.com/user/get_user_info";
        $param = array(
            "access_token"    => $this->accessToken,
            "oauth_consumer_key"    => $this->appId,
            "openid"    => $uid,
        );
        $result = $this->get($url,$param);
        $result = json_decode($result,true);
        return $result;
    }

    protected function getAccessToken($code){
        $callback = Input::root().$this->callbackUrl;
        $callback = urlencode($callback);
        $url = "https://graph.qq.com/oauth2.0/token?"
            ."client_id={$this->appId}&client_secret={$this->appKey}"
            ."&grant_type=authorization_code&redirect_uri={$callback}&code={$code}";
        $result = $this->get($url);
        // 返回是字符串 access_token=FE04************************CCE2&expires_in=7776000&
        $result = explode('&',$result);
        foreach($result as $val){
            if(strpos($val,'access_token')!==false){
                $val = explode('=',$val);
                $this->accessToken = count($val)==2?$val[1]:'';
            }
        }
    }
    protected function getUid(){
        if(empty($this->accessToken)){
            throw new \Exception('AccessToken is empty!');
        }
        $url = "https://graph.qq.com/oauth2.0/me";
        $param = array(
            "access_token"    => $this->accessToken
        );
        $result = $this->get($url,$param);
        if(preg_match('/({.*})/',$result,$matches)){
            $v = json_decode($matches[1],true);
            return $v['openid'];
        }
        return '';
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
