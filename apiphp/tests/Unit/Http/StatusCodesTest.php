<?php

declare(strict_types=1);

namespace Tests\Unit\Http;

use Meetingg\Http\StatusCodes;
use Tests\Unit\AbstractUnitTest;
use Phalcon\Mvc\Controller;

class StatusCodesTest extends AbstractUnitTest
{

    /**
     * @dataProvider providerMessageForCode
     */

    public function testGetMessageForCode($code, $expected)
    {
        $instanceStatusCode = new StatusCodes();

        $this->assertSame($expected, $instanceStatusCode->getMessageForCode($code));
    }

    /**
     * @dataProvider providerIsError
     */
    public function testIsError($code, $expected)
    {
        $instanceStatusCode = new StatusCodes();
        $this->assertSame($expected, $instanceStatusCode->isError($code));
    }

    /**
     * @dataProvider providerHaveBody
     */
    public function testCanHaveBody($code, $expected)
    {
        $instanceStatusCode = new StatusCodes();
        $this->assertSame($expected, $instanceStatusCode->canHaveBody($code));
    }

    public function testParseCode() : void
    {
        $this->assertSame(StatusCodes::parseCode(200), 200);
        
        $this->assertSame(StatusCodes::parseCode(8888), StatusCodes::parseCode(9999));
    }

    public static function providerMessageForCode(): array
    {
        return [
            '100' => [100,'Continue'],
            '101' => [101,'Switching Protocols'],
            '200' => [200,'OK'],
        ];
    }

    public static function providerIsError(): array
    {
        return [
            '100 Continue' => [100, false],
            '101 Switching Protocols' => [101, false],
            '400 Bad Request' => [400, true],
            '401 Unauthorized' => [401, true],
            '400 Bad Request' => [400, true],
            '401 Unauthorized' => [401, true],
            '400 Bad Request' => [400, true],
            '401 Unauthorized' => [401, true],
            '402 Payment Required' => [402, true],
            '403 Forbidden' => [403, true],
            '404 Not Found' => [404, true],
            '405 Method Not Allowed' => [405, true],
            '406 Not Acceptable' => [406, true],
        ];
    }

    public static function providerHaveBody(): array
    {
        return [
            '100 Continue' => [100, false],
            '101 Switching Protocols' => [101, false],
            '400 Bad Request' => [400, true],
            '401 Unauthorized' => [401, true],
            '400 Bad Request' => [400, true],
            '401 Unauthorized' => [401, true],
            '400 Bad Request' => [400, true],
            '401 Unauthorized' => [401, true],
            '402 Payment Required' => [402, true],
            '403 Forbidden' => [403, true],
            '404 Not Found' => [404, true],
            '405 Method Not Allowed' => [405, true],
            '406 Not Acceptable' => [406, true],
        ];
    }
}
