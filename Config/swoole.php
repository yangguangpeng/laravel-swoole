<?php
return [
    'listen'=>[
        'host'=>env('SWOOLE_LISTEN_HOST','0.0.0.0'),
        'port'=>env('SWOOLE_LISTEN_PORT',9501),
    ],
    //true:http类型 | false:websocket类型
    'socket_type'=>true,
    'http'=>[

    ],
    'websocket'=>[

    ]
];