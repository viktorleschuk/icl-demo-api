<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use InvalidArgumentException;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected function assertAuthenticationRequired($uri, $method = 'get')
    {
        $method = strtolower($method);
        if (!in_array($method, ['get', 'post', 'put', 'update', 'delete'])) {
            throw new InvalidArgumentException('Invalid method: ' . $method);
        }

        // Json check
        $method .= 'Json';
        $response = $this->$method($uri);
        $response->assertStatus(401);
    }
}
