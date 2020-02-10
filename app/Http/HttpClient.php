<?php

namespace App\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        try {
            $response = $this->client->request('GET', $domain->name);
            $status = $response->getStatusCode();
            $body = $response->getBody();
            $contentLength = strlen($body);

            $responseData = [
                'state' => self::STATE_COMPLETED,
                'status' => $status,
                'body' => $body,
                'content_length' => $contentLength
            ];
        } catch (RequestException $e) {
            $responseData = [
                'state' => self::STATE_FAILED,
            ];
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $body = $response->getBody();
                $contentLength = strlen($body);

                $responseData = array_merge($responseData, [
                    'status' => $response->getStatusCode(),
                    'body' => $body,
                    'content_length' => $contentLength
                ]);
            }

            Log::emergency($e->getMessage());
        }

        DB::table('domains')
            ->where('id', $domainId)
            ->update($responseData);
    }
}