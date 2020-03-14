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

class DomainsController extends BaseController
{
    /**
     * @var ClientInterface
     */
    private $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function index()
    {
        $domains = DB::table('domains')->paginate(10);

        return view('domains.index', ['domains' => $domains]);
    }

    public function new()
    {
        return view('domains.new');
    }

    public function show($id)
    {
        $domain = DB::table('domains')->find($id);
        if (!$domain) {
            abort(404, 'Domain page is not found');
        }

        return view('domains.show', ['domain' => $domain]);
    }

    public function create(Request $request)
    {
        $url = $request->input('url');
        Validator::make($request->all(), [
            'url' => 'required|url'
        ])->validate();

        $currentDateTime = Carbon::now();

        $domainId = DB::table('domains')->insertGetId([
            'name' => $url,
            'created_at' => $currentDateTime,
            'updated_at' => $currentDateTime
        ]);

        try {
            $response = $this->httpClient->get($url);
        } catch (RequestException $e) {
            DB::table('domains')
                ->where('id', $domainId)
                ->update(['state' => 'failed']);

            Log::emergency($e->getMessage());

            return redirect()->route('domains.show', ['id' => $domainId]);
        }

        $body = $response->getBody();
        $responseData = [
            'state' => 'completed',
            'status' => $response->getStatusCode(),
            'body' => $body->getContents(),
            'content_length' => $body->getSize()
        ];

        $parsedData = $this->parsePage($responseData['body']);

        $updatedData = array_merge($responseData, $parsedData);

        DB::table('domains')
            ->where('id', $domainId)
            ->update($updatedData);

        return redirect()->route('domains.show', ['id' => $domainId]);
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
