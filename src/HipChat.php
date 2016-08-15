<?php

namespace NotificationChannels\HipChat;

use GuzzleHttp\Client as HttpClient;

class HipChat
{
    /** @var string */
    protected $token;

    /** @var HttpClient */
    protected $http;

    /** @var string */
    protected $url;

    /** @var string */
    protected $room;

    /**
     * @param HttpClient $http
     * @param string $token
     * @param string|null $url
     */
    public function __construct(HttpClient $http, $url, $token, $room)
    {
        $this->http = $http;
        $this->url = rtrim($url ?: 'https://api.hipChat.com', '/');
        $this->token = $token;
        $this->room = $room;
    }

    /**
     * Returns default room id or name.
     *
     * @return string
     */
    public function room()
    {
        return $this->room;
    }

    /**
     * Returns HipChat base url.
     *
     * @return string
     */
    public function url()
    {
        return $this->url;
    }

    /**
     * Send a message.
     *
     * @param $to
     * @param $message
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sendMessage($to, $message)
    {
        return $this->request($this->getMessageUrl($to), [
            'headers' => $this->getHeaders(),
            'json' => $message,
        ]);
    }

    /**
     * Make a request.
     *
     * @param string $url
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function request($url, $options)
    {
        return $this->http->post($url, $options);
    }

    /**
     * Get common request headers.
     *
     * @return array
     */
    protected function getHeaders()
    {
        return [
            'Authorization' => 'Bearer '.$this->token,
        ];
    }

    /**
     * Get room notification url.
     *
     * @param string $to
     * @return string
     */
    protected function getMessageUrl($to)
    {
        return $this->url.'/v2/room/'.urlencode($to).'/notification';
    }
}
