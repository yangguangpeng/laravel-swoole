<?php

namespace Perry\LaravelSwoole\Http;

use Illuminate\Http\Request as LaravelRequest;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Swoole\Http\Request as SwooleRequest;

class SRequest
{
    // 获取到laravel的请求对象
    public static function createLaravelRequest($get,$post,$cookie,$files,$server)
    {
        LaravelRequest::enableHttpMethodParameterOverride();
        // 创建
        $request = new SymfonyRequest($get, $post, [], $cookie, $files, $server, null);

        if (0 === strpos($request->headers->get('CONTENT_TYPE'), 'application/x-www-form-urlencoded')
            && \in_array(strtoupper($request->server->get('REQUEST_METHOD', 'GET')), ['PUT', 'DELETE', 'PATCH'])
        ) {
            parse_str($request->getContent(), $data);
            $request->request = new ParameterBag($data);
        }
        return LaravelRequest::createFromBase($request);
    }
    // 设置请求的超全局变量
    public static function toLaravelParametes(SwooleRequest $swooleRequest){
        $server = [];
        if (isset($swooleRequest->server)) {
            foreach ($swooleRequest->server as $k => $v) {
                $server[strtoupper($k)] = $v;
            }
        }
        $get = $swooleRequest->get ?? [];
        $post = $swooleRequest->post ?? [];
        $files = $swooleRequest->files ?? [];
        $cookie = $swooleRequest->cookie ?? [];

        return [$get, $post, $cookie, $files, $server];
    }

    public static function make(SwooleRequest $swooleRequest)
    {
        return static::createLaravelRequest(...(static::toLaravelParametes($swooleRequest)));

    }
}
?>