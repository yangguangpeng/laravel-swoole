<?php
namespace Perry\LaravelSwoole;


use Illuminate\Support\ServiceProvider;
use Perry\LaravelSwoole\Server\Manager;
use Swoole\Http\Server as SwooleHttpServer;
use Swoole\WebSocket\Server as SwooleWebSocketServer;

class SwooleServiceProvider extends ServiceProvider
{
    protected $command = [
        Console\HttpServerCommand::class,
    ];

    protected static $server;

    public function register()
    {
        $this->registerConfig();
        $this->registerSwooleServer();
        $this->registerSwooleManager();
        $this->commands($this->command);
    }

    /**
     * 注册配置文件
     */
    public function registerConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/Config/swoole.php', 'extend.swoole'
        );
    }

    public function boot()
    {

    }

    protected function registerSwooleManager()
    {
        $this->app->singleton('extend.swoole_manager',function($app){
            return new Manager($app);

        });
    }

    protected function registerSwooleServer()
    {
        $this->app->singleton('extend.swoole_server',function(){
            //把swoole绑定到ioc
            if(is_null(static::$server)){
                //创建swoole服务
                $this->createSwooleServer();
                $this->configureSwooleServer();
            }
            return static::$server;

        });
    }

    /**
     * 1.读取配置文件
     * 2.确定要创建服务类型
     * 3.创建swoole\
     * @return \Swoole\Http\Server
     *
     */
    protected function createSwooleServer()
    {
        $server = config('extend.swoole.socket_type')
            ?SwooleHttpServer::class:SwooleWebSocketServer::class;
        static::$server = new $server(config('extend.swoole.listen.host'),config('extend.swoole.listen.port'));
    }

    /**
     * 因为swoole有不同的服务不同的配置，所以需要设置
     */
    protected function configureSwooleServer()
    {

    }
}