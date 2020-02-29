<?php

namespace App\Models;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class HttpClient
{
    public const STATE_INIT = 'init';
    public const STATE_COMPLETED = 'completed';
    public const STATE_FAILED = 'failed';

    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function send(string $url)
    {
        try {
            $response = $this->client->request('GET', $url);
            $status = $response->getStatusCode();
            $body = $response->getBody();

            $responseData = [
                'state' => self::STATE_COMPLETED,
                'status' => $status,
                'body' => $body->getContents(),
                'content_length' => $body->getSize()
            ];
        } catch (RequestException $e) {
            $responseData = [
                'state' => self::STATE_FAILED,
            ];
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $body = $response->getBody();

                $responseData = array_merge($responseData, [
                    'status' => $response->getStatusCode(),
                    'body' => $body->getContents(),
                    'content_length' => $body->getSize()
                ]);
            }

            Log::emergency($e->getMessage());
        }

        return $responseData;
    }
}
