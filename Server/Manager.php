<?php
namespace Perry\LaravelSwoole\Server;
//swoole服务的管理
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Http\Kernel;
use Perry\LaravelSwoole\Http\SRequest;
use Perry\LaravelSwoole\Http\SResponse;

class Manager
{
    /**
     * @var swoole服务
     */
    protected $server;
    /**
     * laravel的应用程序Application
     * @var [type]
     */
    protected $laravel;

    /**
     * swoole事件
     * @var [type]
     */
    protected $events = [
        'http' => [
            'request'=>'onRequest'
        ],
        'websocket'=>[],
    ];

    public function __construct(Container $laravel)
    {
        $this->laravel = $laravel;
        //获取swoole服务
        $this->server = $this->laravel->make('extend.swoole_server');
        $this->setSwooleServerEvent();
    }

    protected function setSwooleServerEvent()
    {
        $type = config('extend.swoole.socket_type')?'http':'websocket';

        foreach ($this->events[$type] as $event=>$func) {
            $this->server->on($event,[$this,$func]);
        }
    }

    public function run()
    {
        $this->server->start();
    }

    public function onRequest($swooleRequest,$swooleResponse)
    {
        try{
            $laravelRequest = SRequest::make($swooleRequest);
            $laravelResponse = $this->laravel->make(Kernel::class)
                ->handle($laravelRequest);

            //$swooleResponse->header('Content-Type','text/html;charset=utf-8');
            //$swooleResponse->end($laravelResponse->getContent());
            SResponse::make($laravelResponse,$swooleResponse)->send();
        }catch (\Exception $e){
            $swooleResponse->end($e);
        }

    }
}