<?php

/**
 * Created by IntelliJ IDEA.
 *
 * PHP version 7.4
 *
 * @category amphp-async-soap
 * @author   Oleg Tikhonov <o.tikhonov@nexta.pro>
 */

declare(strict_types=1);

namespace Traff\Soap\Test\Unit\Wsdl;

use Amp\PHPUnit\AsyncTestCase;
use Amp\Success;
use Traff\Soap\RequestBuilder\RequestBuilder;
use Traff\Soap\Test\MockeryTrait;
use Traff\Soap\Wsdl\WsdlUrl;
use Mockery as m;

/**
 * Class WsdlUrlTest.
 *
 * @package Traff\Soap\Test\Unit\Wsdl
 */
class WsdlUrlTest extends AsyncTestCase
{
    use MockeryTrait;

    /**
     * testToString.
     *
     * @covers \Traff\Soap\Wsdl\WsdlUrl::toString
     *
     * @return \Generator
     */
    public function testToString(): \Generator
    {
        $builder_mock = m::mock(RequestBuilder::class);
        $wsdl = new WsdlUrl('localhost', $builder_mock);

        $builder_mock->expects('request')->with('localhost')->andReturn(new Success('wsdl'));

        self::assertSame('data://text/plain;base64,d3NkbA==', yield $wsdl->toString());
    }

    /**
     * testItCanDestruct.
     *
     * @return void
     */
    public function testItCanDestruct(): void
    {
        $wsdl = new WsdlUrl('localhost', m::mock(RequestBuilder::class));

        unset($wsdl);

        self::assertNull($wsdl ?? null);
    }
}
