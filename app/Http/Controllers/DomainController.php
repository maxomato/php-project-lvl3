<?php

namespace App\Http\Controllers;

use App\Models\HttpClient;
use App\Models\PageAnalyzer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;

class DomainController extends BaseController
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var PageAnalyzer
     */
    private $pageAnalyzer;

    public function __construct(HttpClient $httpClient, PageAnalyzer $pageAnalyzer)
    {
        $this->httpClient = $httpClient;
        $this->pageAnalyzer = $pageAnalyzer;
    }

    public function index()
    {
        $domains = DB::table('domains')->paginate(10);

        return view('domain.index', ['domains' => $domains]);
    }

    public function create()
    {
        return view('domain.form');
    }

    public function show($id)
    {
        $domains = DB::table('domains')->where(['id' => $id])->get();

        return view('domain.view', ['domain' => $domains->first()]);
    }

    public function store(Request $request)
    {
        $url = $request->input('url');
        Validator::make($request->all(), [
            'url' => 'required|url'
        ])->validate();

        $currentDateTime = date('d/M/Y H:i:s');

        $domainId = DB::table('domains')->insertGetId([
            'name' => $url,
            'state' => HttpClient::STATE_INIT,
            'created_at' => $currentDateTime,
            'updated_at' => $currentDateTime
        ]);

        $responseData = $this->httpClient->send($url);

        if (array_key_exists('body', $responseData)) {
            $parsedData = $this->pageAnalyzer->parsePage($responseData['body']);
        } else {
            $parsedData = [];
        }

        $updatedData = array_merge($responseData, $parsedData);

        DB::table('domains')
            ->where('id', $domainId)
            ->update($updatedData);

        return redirect()->route('domains.show', ['id' => $domainId]);
    }
}
