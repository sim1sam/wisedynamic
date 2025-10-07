<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class PaymentCallbackTest extends TestCase
{
    /**
     * Test SSL callback without CSRF token.
     *
     * @return void
     */
    public function testSslCallbackWithoutCsrf()
    {
        // Test the generic SSL callback endpoint
        $response = $this->post('/customer/payment/ssl/success', [
            'tran_id' => 'TEST_TRAN_' . time(),
            'value_a' => 'package',
            'value_b' => '1',
            'status' => 'VALID',
        ]);

        // Should redirect without 419 error
        $response->assertStatus(302);
        $this->assertNotEquals(419, $response->getStatusCode());
    }

    /**
     * Test fallback route for SSL callbacks.
     *
     * @return void
     */
    public function testSslFallbackRoute()
    {
        // Test the fallback route
        $response = $this->post('/ssl-callback', [
            'tran_id' => 'UNKNOWN_TRAN_' . time(),
            'status' => 'VALID',
        ]);

        // Should redirect without 419 error
        $response->assertStatus(302);
        $this->assertNotEquals(419, $response->getStatusCode());
    }
}
