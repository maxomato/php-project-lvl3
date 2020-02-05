<?php

namespace App\Http;

interface HttpClientInterface
{
    public const STATE_INIT = 'init';
    public const STATE_COMPLETED = 'completed';
    public const STATE_FAILED = 'failed';

    public function send($domainId);
}
