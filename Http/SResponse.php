<?php


namespace Perry\LaravelSwoole\Http;

use Illuminate\Http\Response as LaravelResponse;
use Swoole\Http\Response as SwooleResponse;

class SResponse
{
    protected $laravelResponse;

    protected $swooleResponse;

    public function __construct($laravelResponse, $swooleResponse)
    {
        $this->laravelResponse = $laravelResponse;
        $this->swooleResponse = $swooleResponse;
    }

    // 解析swoole的请求
    public static function make(LaravelResponse $laravelResponse, SwooleResponse $swooleResponse)
    {
        return new static($laravelResponse, $swooleResponse);
    }
    // 输出响应的结果
    public function send()
    {
        $this->sendHandler();
        $this->sendContent();
    }
    // 输出头部
    public function sendHandler()
    {
        $laravelResponse = $this->laravelResponse;
        // 1. 设置头
        $headers = $laravelResponse->headers->allPreserveCase();
        // 因为swoole有自己的设置token的方式所以这里需要删除
        if (isset($headers['Set-Cookie'])) {
            unset($headers['Set-Cookie']);
        }
        foreach ($headers as $name => $values) {
            foreach ($values as $value) {
                $this->swooleResponse->header($name, $value);
            }
        }
        // 2. 设置状态
        $this->swooleResponse->status($laravelResponse->getStatusCode());
        // 3. 设置cookie
        // $illuminateResponse->headers : Symfony\Component\HttpFoundation\ResponseHeaderBag
        foreach ($laravelResponse->headers->getCookies() as $cookie) {
            // $cookie : Symfony\Component\HttpFoundation\Cookie;
            $this->swooleResponse->cookie(
                $cookie->getName(),
                $cookie->getValue(),
                $cookie->getExpiresTime(),
                $cookie->getPath(),
                $cookie->getDomain(),
                $cookie->isSecure(),
                $cookie->isHttpOnly()
            );
        }
    }

    // 输出主要内容
    public function sendContent()
    {
        $this->swooleResponse->end($this->laravelResponse->getContent());
    }
}
?>