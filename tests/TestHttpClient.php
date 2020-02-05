<?php

namespace App\Tests;

use App\Http\HttpClientInterface;
use Illuminate\Support\Facades\DB;

class TestHttpClient implements HttpClientInterface
{
    public function send($domainId)
    {
        DB::table('domains')
            ->where('id', $domainId)
            ->update(['state' => self::STATE_COMPLETED]);
    }
}
