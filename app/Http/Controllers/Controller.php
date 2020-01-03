<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function domains(Request $request, $id = null)
    {
        if ($request->isMethod('get')) {
            $domains = DB::table('domains')->where(['id' => $id])->get();

            return view('view', ['domain' => $domains->first()]);
        }

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

        return redirect()->route('domain-view', ['id' => $id]);
    }
}
