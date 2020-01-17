<?php

use GuzzleHttp\Client;

class ImoveisTest extends TestCase
{
    /**
     * Test response from ZAP.
     *
     * @return void
     */
    public function testImoveisZap()
    {
        $response = $this->json('GET', '/portal/zap/')
            ->seeJsonStructure([ 
                'pageNumber',
                'pageSize',
                'totalCount',
                'listings'
                ]);

    }

        /**
     * Test response from ZAP.
     *
     * @return void
     */
    public function testImoveisVivareal()
    {
        $response = $this->json('GET', '/portal/vivareal/')
            ->seeJsonStructure([ 
                'pageNumber',
                'pageSize',
                'totalCount',
                'listings'
                ]);

    }
}
