<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;

class DomainController extends BaseController
{
    public function create()
    {
        return view('index');
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
            'created_at' => $currentDateTime,
            'updated_at' => $currentDateTime
        ]);

        return redirect()->route('domains.show', ['id' => $id]);
    }
}
