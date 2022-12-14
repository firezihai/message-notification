# message-notification

## 功能

- 用于发送应用异常、工作等通知
- 支持多平台,如钉钉、企业微信
- 支持自定义发送平台

## 环境要求

- hyperf >= 2.0

## 安装

```
composer require firezihai/message-notification -vvv

```

## 配置文件

发布配置文件 config/autload/message_notification.php

```
php bin/hyperf.php vendor:publish firezihai/message-notification

```

## 使用

```
$message = make(MessageInterface::class);
message->send(['xxxxx']);

```

## 配置多个消息通知平台

当你需要多个平台发送不同的消息通知时，可以配置多个平台

1. 配置

```

  'default' => [
        'driver' => MessageNotification\Driver\DingTalk::class,
        'store' => [
            'driver' => MessageNotification\Store\Cache::class,
        ],
        'app' => [
            'appkey' => '',
            'appsecret' => '',
            'agent_id' => '',
        ],
    ],
    'qywechat' => [
        'driver' => MessageNotification\Driver\Qywechat::class,
        'store' => [
            'driver' => MessageNotification\Store\Cache::class,
        ],
        'app' => [
            'appkey' => '',
            'appsecret' => '',
            'agent_id' => '',
        ],
    ],
    'dingTalkRobot' => [
        'driver' => MessageNotification\Driver\DingTalkRobot::class,
        'store' => [
            'access_token' => '',
        ],
        'app' => [
            'app_name' => '系统通知',
            //如需加签配置此参数
            'appsecret' => '',
        ],
    ],

```

2. 使用

```
$message = make(MessageManager::class)->getDriver('qywechat');
message->send(['xxxxx']);

```

## 自定义消息通知平台

实现 `PlatformInterface` 接口

1. 编写平台驱动类

```
class FeiShu implements PlatformInterface
{

     /**
     * 发送消息.
     */
    public function send(array $userId, string $message)
    {

    }

    /**
     *  根据手机号码获取userid.
     */
    public function getUserIdByMobile(string $mobile)
    {

    }

    /**
     * 获取token.
     */
    public function getAccessToken()
    {

    }

}


```

2. 配置驱动类

在`config/autoload/message_notification.php`文件配置新的驱动类

```


    'default' => [
        'driver' => FeiShu::class,
    ],


```

## 自定义 token 储存方式

默认使用 redis 储存,如果你想使用数据库储存,可自定义储存驱动,只要实现 `StoreInterface` 类即可

1. 编写储存类

```

Db implements StoreInterface
{
	
	// $app 配置文件中的app配置项
    public function set(array $app,array $token)
    {
		....
		$update['access_token'] = $token['access_token'];
		$update['expires_in'] = $token['expires_in'];
		....
    }

    public function get(array $app)
    {
	  ....
	  
	  return [
		   'access_token' => $token['access_token'],
           'expires_in' => $token['expires_in'],
		   'token_update_time'=>$token['token_update_time'],
	  ]
    }
}


```

2. 配置

在`config/autoload/message_notification.php`文件中配置新的储存驱动类

```


    'store' => [
        'driver' => DB::class,
    ],


```
