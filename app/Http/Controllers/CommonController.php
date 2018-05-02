<?php

namespace App\Http\Controllers;

use App\Services\SmsService;
use App\Services\UploadService;
use App\Tools\Captcha\Captcha;
use App\Tools\Output;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    protected $captcha;
    protected $uploadService;
    protected $smsService;
    public function __construct(Captcha $captcha,UploadService $uploadService,SmsService $smsService){
        //$this->middleware('auth');
        $this->captcha = $captcha;
        $this->uploadService = $uploadService;
        $this->smsService = $smsService;
    }

    /**
     * 上传
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function upload(Request $request){
        set_time_limit(60*10);
        $file = $this->uploadService->saveFiles($request);
        return response()->json($file);
    }

    public function sendCode(Request $request){
        return $this->smsService->sendRegisterCode($request->get('phone'));
    }
}
