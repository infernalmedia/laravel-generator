<?php

namespace Tests\Utils;

use InfyOm\Generator\Utils\ResponseUtil;
use PHPUnit\Framework\TestCase;

class ResponseUtilTest extends TestCase
{
    public function testMakeResponse(): void
    {
        $message = 'Data Received';
        $data = ['field' => 'value'];

        $response = ResponseUtil::makeResponse($message, $data);

        $this->assertTrue($response['success']);
        $this->assertEquals($message, $response['message']);
        $this->assertEquals($data, $response['data']);
    }

    public function testMakeError(): void
    {
        $message = 'Error Occurred';

        $response = ResponseUtil::makeError($message);

        $this->assertFalse($response['success']);
        $this->assertEquals($message, $response['message']);
        $this->assertArrayNotHasKey('data', $response);
    }

    public function testMakeErrorWithGivenData(): void
    {
        $message = 'Error Occurred';
        $data = ['code' => '404', 'line' => 20];

        $response = ResponseUtil::makeError($message, $data);

        $this->assertFalse($response['success']);
        $this->assertEquals($message, $response['message']);
        $this->assertEquals($data, $response['data']);
    }
}
