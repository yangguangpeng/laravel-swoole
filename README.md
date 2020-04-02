<h1 align="center"> laravel-swoole </h1>

<p align="center"> laravel-swoole.</p>


## Installing

```shell
$ composer require perry/laravel-swoole -vvv
```

## Usage
在laravel的config/app.php的providers下加上服务提供者
Perry\LaravelSwoole\SwooleServiceProvider::class

控制台
启动服务
php artisan extend:swoole start

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/perry/laravel-swoole/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/perry/laravel-swoole/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT
