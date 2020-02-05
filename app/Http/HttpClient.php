<?php

namespace App\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

class HttpClient implements HttpClientInterface
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function send($domainId)
    {
        $domain = DB::table('domains')
            ->where(['id' => $domainId])
            ->get()
            ->first();

        $promise = $this->client->requestAsync('GET', $domain->name);
        $promise->then(
            function (ResponseInterface $response) use ($domainId) {
                try {
                    $status = $response->getStatusCode();
                    $body = $response->getBody();

                    $contentLength = strlen($body);
                    self::saveResponseData($domainId, [
                        'state' => self::STATE_COMPLETED,
                        'status' => $status,
                        'content_length' => $contentLength,
                        'body' => $body
                    ]);
                } catch (\Exception $e) {
                    Log::debug($e->getMessage());
                }
            },
            function (RequestException $e) use ($domainId) {
                if ($e->hasResponse()) {
                    $response = $e->getResponse();
                    $status = $response->getStatusCode();
                    $body = $response->getBody();

                    $contentLength = strlen($body);
                    self::saveResponseData($domainId, [
                        'state' => self::STATE_FAILED,
                        'status' => $status,
                        'content_length' => $contentLength,
                        'body' => $body
                    ]);
                } else {
                    self::saveResponseData($domainId, [
                        'state' => self::STATE_FAILED,
                    ]);
                }
            }
        );
    }

    private function saveResponseData($domainId, $responseData)
    {
        $updatedCount = DB::table('domains')
            ->where('id', $domainId)
            ->update($responseData);
        if (!$updatedCount) {
            throw new \Exception('zero rows updated');
        }
    }
}
