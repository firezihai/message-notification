<?php

declare(strict_types=1);

namespace MessageNotification\Driver;

/**
 * 钉钉应用消息通知.
 */
class DingTalk extends Driver
{
    private $api = 'https://oapi.dingtalk.com/';

    public function send(array $userId, string $message)
    {
        $url = $this->api . 'topapi/message/corpconversation/asyncsend_v2?access_token=' . $this->getAccessToken();

        $client = $this->getClient();
        $response = $client->post($url, [
            'json' => [
                'agent_id' => $this->config['app']['agent_id'],
                'userid_list' => join(',', $userId),
                'msg' => [
                    'msgtype' => 'markdown',
                    'markdown' => [
                        'title' => $this->config['app']['app_name'],
                        'text' => $message,
                    ],
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
        $url = $this->api . 'topapi/v2/user/getbymobile?access_token=' . $this->getAccessToken();
        $client = $this->getClient();

        $response = $client->post($url, ['json' => [
            'mobile' => $mobile,
        ]]);

        $response = json_decode($response->getBody()->getContents(), true);
        if (! isset($response['errcode']) || $response['errcode'] != 0) {
            throw new \InvalidArgumentException('You get error : ' . json_encode($response, JSON_UNESCAPED_UNICODE));
        }
        return isset($response['result']['userid']) ? $response['result']['userid'] : '';
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
        $token = $store->get();

        if (empty($token) || empty($token['access_token']) || time() - $token['last_update_time'] > $token['expires_in']) {
            $client = $this->getClient();
            $url = $this->api . 'gettoken';
            $response = $client->get($url, ['query' => [
                'appkey' => $this->config['app']['appkey'],
                'appsecret' => $this->config['app']['appsecret'],
            ]]);

            $response = json_decode($response->getBody()->getContents(), true);
            if (! isset($response['errcode']) || $response['errcode'] != 0) {
                throw new \InvalidArgumentException('You get error : ' . json_encode($response, JSON_UNESCAPED_UNICODE));
            }
            $store->set([
                'access_token' => $response['access_token'],
                'expires_in' => $response['expires_in'],
            ]);

            return $response['access_token'];
        }
        return $token['access_token'];
    }
}
