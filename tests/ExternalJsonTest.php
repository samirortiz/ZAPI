<?php

use GuzzleHttp\Client;

class ExternalJsonTest extends TestCase
{
    /**
     * Test external source returning 200.
     *
     * @return void
     */
    public function testExternalJson()
    {
        $client = new Client(['base_uri' => 'http://grupozap-code-challenge.s3-website-us-east-1.amazonaws.com']);
        $response = $client->get('/sources/source-2.json');
        
        $this->assertEquals(200, $response->getStatusCode());
    }
}
