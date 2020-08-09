<?php


namespace app\api\lib\exception;



use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $errorCode;

    public function render(\Exception $e)
    {
        if ($e instanceof BaseException) {
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        } else {
            if (config('app_debug')) {
                return parent::render($e);
            }else{
                $this->code = 500;
                $this->errorCode = 999;
                $this->msg = '服务器内部错误，不想告诉你';
                $this->recoedErrorLog($e);
            }
        }
        $request = Request::instance();
        $result = [
            'msg' => $this->msg,
            'errorCode' => $this->errorCode,
            'request_url' => $request->url(),
        ];
        return json($result, $this->code);
    }

    private function recoedErrorLog(\Exception $e)
    {
        Log::init([
            'type' => 'File',
            // 日志保存目录
            'path' => LOG_PATH,
            // 日志记录级别
            'level' => ['error'],
        ]);
        Log::record($e->getMessage(), 'error');
    }
}