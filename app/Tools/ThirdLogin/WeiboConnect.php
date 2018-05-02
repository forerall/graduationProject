<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://jizhihuwai.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: @zhiwei
// +----------------------------------------------------------------------
namespace App\Tools\ThirdLogin;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class WeiboConnect {
	protected $appId = '4122577885';
	protected $appKey = 'b52fec563619476ecd2c006635f929f3';
	protected $callbackUrl = '/thirdCallback?type=wb';
	protected $accessToken;

	/**
	 * 登陆url
	 * @return string
	 */
	public function loginUrl(){
		$callback = Input::root().$this->callbackUrl;
		$callback = urlencode($callback);
		$response_type = 'code';
		$scope = '';
		//$url = "https://api.weibo.com/oauth2/authorize?client_id=YOUR_CLIENT_ID&response_type=code&redirect_uri=YOUR_REGISTERED_REDIRECT_URI";
		$url = "https://api.weibo.com/oauth2/authorize?".
			"response_type={$response_type}&client_id={$this->appId}&scope={$scope}&redirect_uri={$callback}";
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
		$url = "https://api.weibo.com/2/users/show.json";
		$param = array(
			"access_token"    => $this->accessToken,
			"uid"    => $uid
		);
		$result = $this->get($url,$param);
		$result = json_decode($result,true);
		return $result;
	}

	protected function getAccessToken($code){
		$callback = Input::root().$this->callbackUrl;
		$callback = urlencode($callback);
		$url = "https://api.weibo.com/oauth2/access_token?"
			."client_id={$this->appId}&client_secret={$this->appKey}"
			."&grant_type=authorization_code&redirect_uri={$callback}&code={$code}";
		$result = $this->post($url);
		$result = json_decode($result,true);
		$this->accessToken = $result['access_token'];
	}
	protected function getUid(){
		if(empty($this->accessToken)){
			throw new \Exception('AccessToken is empty!');
		}
		$url = "https://api.weibo.com/oauth2/get_token_info";
		$param = array(
			"access_token"    => $this->accessToken
		);
		$param = http_build_query($param);
		$result = $this->post($url,$param);
		$result = json_decode($result,true);
		return $result['uid'];
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
