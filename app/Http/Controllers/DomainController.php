<?php

namespace App\Http\Controllers;

use App\Http\HttpClientInterface;
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

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
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

        $id = DB::table('domains')->insertGetId([
            'name' => $url,
            'state' => HttpClientInterface::STATE_INIT,
            'created_at' => $currentDateTime,
            'updated_at' => $currentDateTime
        ]);

        $this->httpClient->send($id);

        return redirect()->route('domains.show', ['id' => $id]);
    }
}
