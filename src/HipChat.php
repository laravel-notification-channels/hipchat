<?php

namespace NotificationChannels\HipChat;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\MultipartStream;
use function GuzzleHttp\Psr7\stream_for;
use function GuzzleHttp\Psr7\modify_request;

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
        $this->url = rtrim($url ?: 'https://api.hipchat.com', '/');
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
     * @param string|int $to
     * @param array $message
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sendMessage($to, $message)
    {
        $url = $this->url.'/v2/room/'.urlencode($to).'/notification';

        return $this->post($url, [
            'headers' => $this->getHeaders(),
            'json' => $message,
        ]);
    }

    /**
     * Share a file.
     *
     * @param string|int $to
     * @param array $file
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function shareFile($to, $file)
    {
        $parts[] = [
            'headers' => [
                'Content-Type' => $file['file_type'] ?: 'application/octet-stream',
            ],
            'name' => 'file',
            'contents' => stream_for($file['content']),
            'filename' => $file['filename'] ?: 'untitled',
        ];

        if (! empty($file['message'])) {
            $parts[] = [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'name' => 'metadata',
                'contents' => json_encode(['message' => $file['message']]),
            ];
        }

        $url = $this->url.'/v2/room/'.urlencode($to).'/share/file';

        return $this->postMultipartRelated($url, [
            'headers' => $this->getHeaders(),
            'multipart' => $parts,
        ]);
    }

    /**
     * Make a simple post request.
     *
     * @param string $url
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function post($url, $options)
    {
        return $this->http->post($url, $options);
    }

    /**
     * Make a multipart/related request.
     * Unfortunately Guzzle doesn't support multipart/related requests out of the box.
     *
     * @param $url
     * @param $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function postMultipartRelated($url, $options)
    {
        $headers = isset($options['headers']) ? $options['headers'] : [];

        $body = new MultipartStream($options['multipart']);

        $version = isset($options['version']) ? $options['version'] : '1.1';

        $request = new Request('POST', $url, $headers, $body, $version);

        $changeContentType['set_headers']['Content-Type'] = 'multipart/related; boundary='.$request->getBody()->getBoundary();

        $request = modify_request($request, $changeContentType);

        return $this->http->send($request);
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
}
