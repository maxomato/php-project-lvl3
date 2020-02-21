<?php

namespace App\Interfaces;

interface HttpClientInterface
{
    public const STATE_INIT = 'init';
    public const STATE_COMPLETED = 'completed';
    public const STATE_FAILED = 'failed';

    public function send(string $url);
}
