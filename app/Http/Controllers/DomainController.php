<?php

namespace App\Http\Controllers;

use App\Interfaces\HttpClientInterface;
use App\Models\PageAnalyzer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;

class DomainController extends BaseController
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var PageAnalyzer
     */
    private $pageAnalyzer;

    public function __construct(HttpClientInterface $httpClient, PageAnalyzer $pageAnalyzer)
    {
        $this->httpClient = $httpClient;
        $this->pageAnalyzer = $pageAnalyzer;
    }

    public function index()
    {
        $domains = DB::table('domains')->paginate(10);

        return view('index', ['domains' => $domains]);
    }

    public function create()
    {
        return view('form');
    }

    public function show($id)
    {
        $domains = DB::table('domains')->where(['id' => $id])->get();

        return view('view', ['domain' => $domains->first()]);
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
            'state' => HttpClientInterface::STATE_INIT,
            'created_at' => $currentDateTime,
            'updated_at' => $currentDateTime
        ]);

        $domain = DB::table('domains')
            ->where(['id' => $domainId])
            ->get()
            ->first();

        $responseData = $this->httpClient->send($domain->name);

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
