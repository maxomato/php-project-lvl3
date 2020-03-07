<?php

namespace App\Http\Controllers;

use DiDom\Document;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;

class DomainController extends BaseController
{
    /**
     * @var ClientInterface
     */
    private $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function list()
    {
        $domains = DB::table('domains')->paginate(10);

        return view('domain.list', ['domains' => $domains]);
    }

    public function form()
    {
        return view('domain.form');
    }

    public function view($id)
    {
        $domain = DB::table('domains')->find($id);

        return view('domain.view', ['domain' => $domain]);
    }

    public function create(Request $request)
    {
        $url = $request->input('url');
        Validator::make($request->all(), [
            'url' => 'required|url'
        ])->validate();

        $currentDateTime = Carbon::now()->format('d/M/Y H:i:s');

        $domainId = DB::table('domains')->insertGetId([
            'name' => $url,
            'created_at' => $currentDateTime,
            'updated_at' => $currentDateTime
        ]);

        try {
            $response = $this->httpClient->request('GET', $url);
        } catch (RequestException $e) {
            $response = $e->hasResponse()
                ? $response = $e->getResponse()
                : null;
            Log::emergency($e->getMessage());
        }

        $responseData = [
            'state' => 'failed',
            'body' => '',
            'content_length' => 0
        ];

        if ($response) {
            $body = $response->getBody();
            $responseData = [
                'state' => 'completed',
                'status' => $response->getStatusCode(),
                'body' => $body->getContents(),
                'content_length' => $body->getSize()
            ];
        }

        $parsedData = $this->parsePage($responseData['body']);

        $updatedData = array_merge($responseData, $parsedData);

        DB::table('domains')
            ->where('id', $domainId)
            ->update($updatedData);

        return redirect()->route('domains.view', ['id' => $domainId]);
    }

    private function parsePage($page)
    {
        $document = new Document($page);

        $h1Element = $document->first('h1');
        $h1 = $h1Element ? $h1Element->text() : '';

        $keywordsElement = $document->first('meta[name="keywords"]');
        $keywords = $keywordsElement ? $keywordsElement->attr('content') : '';

        $descriptionElement = $document->first('meta[name="description"]');
        $description = $descriptionElement ? $descriptionElement->attr('content') : '';

        return [
            'h1' => $h1,
            'keywords' => $keywords,
            'description' => $description
        ];
    }
}
