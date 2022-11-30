<?php

declare(strict_types=1);

namespace MessageNotification\Driver;

/**
 * 企业微信应用消息.
 */
class QyWechat extends Driver
{
    private $api = 'https://qyapi.weixin.qq.com/cgi-bin/';

    public function send(array $userId, string $message)
    {
        $api = $this->api . 'message/send?access_token=' . $this->getAccessToken();

        $client = $this->getClient();
        $response = $client->post($api, [
            'json' => [
                'agentid' => $this->config['app']['agent_id'],
                'msgtype' => 'markdown',
                'touser' => join('|', $userId),
                'markdown' => [
                    'content' => $message,
                ],
            ],
        ]);
        $response = json_decode($response->getBody()->getContents(), true);
        if (! isset($response['errcode']) || $response['errcode'] != 0) {
            throw new \InvalidArgumentException('You get error: ' . json_encode($response, JSON_UNESCAPED_UNICODE));
        }
        return $response;
    }

    public function getUserIdByMobile(string $mobile)
    {
        $url = $this->api . 'user/getuserid?access_token=' . $this->getAccessToken();
        $client = $this->getClient();

        $response = $client->post($url, ['json' => [
            'mobile' => $mobile,
        ]]);

        $response = json_decode($response->getBody()->getContents(), true);
        if (! isset($response['errcode']) || $response['errcode'] != 0) {
            throw new \InvalidArgumentException('You get error : ' . json_encode($response, JSON_UNESCAPED_UNICODE));
        }
        return isset($response['userid']) ? $response['userid'] : '';
    }

    public function getAccessToken()
    {
        if (! is_array($this->config['store'])) {
            throw new \InvalidArgumentException('The message notification config store must be an array ');
        }
        if (! isset($this->config['store']['driver']) && empty($this->config['store']['driver'])) {
            throw new \InvalidArgumentException('The message notification config store.driver is invalid ');
        }

        $store = $this->getStore();

        $token = $store->get($this->config['app']);

        if (empty($token) || empty($token['access_token']) || time() - $token['token_update_time'] > $token['expires_in']) {
            $client = $this->getClient();
            $url = $this->api . 'user/gettoken';
            $response = $client->get($this->api . 'gettoken', ['query' => [
                'corpid' => $this->config['app']['appkey'],
                'corpsecret' => $this->config['app']['appsecret'],
            ]]);
            $response = json_decode($response->getBody()->getContents(), true);
            if (! isset($response['errcode']) || $response['errcode'] != 0) {
                throw new \InvalidArgumentException('You get error : ' . json_encode($response, JSON_UNESCAPED_UNICODE));
            }
            $store->set($this->config['app'], [
                'access_token' => $response['access_token'],
                'expires_in' => $response['expires_in'],
            ]);

            return $response['access_token'];
        }
        return $token['access_token'];
    }
}
