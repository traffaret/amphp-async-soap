<?php

/**
 * Created by IntelliJ IDEA.
 *
 * PHP version 7.3
 *
 * @category amphp-async-soap
 * @author   Oleg Tikhonov <to@toro.one>
 */

declare(strict_types=1);

namespace Traff\Soap\Test\Unit;

use Amp\Http\Client\Request;
use Amp\PHPUnit\AsyncTestCase;
use Amp\Promise;
use Amp\Success;
use Traff\Soap\Message\SoapMessageInterface;
use Traff\Soap\Options;
use Traff\Soap\RequestBuilder\RequestBuilder;
use Traff\Soap\SoapTransport;
use Mockery as m;
use Traff\Soap\Test\MockeryTrait;

/**
 * Class SoapTransportTest.
 *
 * @package Traff\Soap\Test\Unit
 */
class SoapTransportTest extends AsyncTestCase
{
    use MockeryTrait;

    /**
     * getRequest.
     *
     * @covers \Traff\Soap\SoapTransport::getRequest
     *
     * @return void
     */
    public function testGetRequest(): void
    {
        $request = new Request('localhost');

        $builder_mock = m::mock(RequestBuilder::class);
        $soap_transport = new SoapTransport(m::mock(SoapMessageInterface::class), $builder_mock);

        $builder_mock->expects('getRequest')->andReturn($request);

        self::assertSame($request, $soap_transport->getRequest());
    }

    /**
     * getRawHeaders.
     *
     * @covers \Traff\Soap\SoapTransport::getRawHeaders
     *
     * @throws \Amp\Http\InvalidHeaderException
     * @return void
     */
    public function testGetRawHeaders(): void
    {
        $request = new Request('localhost');
        $request->addHeader('Content-Type', 'text/xml; charset=utf-8;');
        $request->addHeader('Authorization', 'basic bas64string');

        $builder_mock = m::mock(RequestBuilder::class);
        $soap_transport = new SoapTransport(m::mock(SoapMessageInterface::class), $builder_mock);

        $builder_mock->expects('getRequest')->andReturn($request);

        $raw = "Content-Type: text/xml; charset=utf-8;\r\n";
        $raw .= "Authorization: basic bas64string\r\n";

        self::assertSame($raw, $soap_transport->getRawHeaders());
    }

    /**
     * getOptions.
     *
     * @covers \Traff\Soap\SoapTransport::getOptions
     * @uses \Traff\Soap\Options
     *
     * @return void
     */
    public function testGetOptions(): void
    {
        $options = new Options();
        $builder_mock = m::mock(RequestBuilder::class);

        $builder_mock->expects('getOptions')->andReturn($options);

        $soap_transport = new SoapTransport(m::mock(SoapMessageInterface::class), $builder_mock);

        self::assertSame($options, $soap_transport->getOptions());
    }

    /**
     * doRequest.
     *
     * @covers \Traff\Soap\SoapTransport::doRequest
     * @covers \Traff\Soap\SoapTransport::getSoapHeaders
     *
     * @return void
     */
    public function testDoRequest(): void
    {
        $request = '<request />';
        $builder_mock = m::mock(RequestBuilder::class);

        $builder_mock
            ->expects('request')
            ->with('localhost', $request, [['Content-Type', 'text/xml; charset="utf-8";'], ['SOAPAction', '"action"']])
            ->andReturn(m::mock(Promise::class));
        $builder_mock
            ->expects('request')
            ->with('localhost', $request, [['Content-Type', 'application/soap+xml; charset="utf-8"; action="action"']])
            ->andReturn(m::mock(Promise::class));

        $soap_transport = new SoapTransport(m::mock(SoapMessageInterface::class), $builder_mock);

        $soap_transport->doRequest($request, 'localhost', 'action');
        $soap_transport->doRequest($request, 'localhost', 'action', \SOAP_1_2);

        $this->expectException(\InvalidArgumentException::class);
        $soap_transport->doRequest($request, 'localhost', 'action', 1000);
    }

    /**
     * callAsync.
     *
     * @covers \Traff\Soap\SoapTransport::callAsync
     * @covers \Traff\Soap\SoapTransport::getSoapHeaders
     *
     * @return \Generator
     */
    public function testCallAsync(): \Generator
    {
        $function_name = 'unit';
        $args = [1, 2, 3];

        $builder_mock = m::mock(RequestBuilder::class);
        $message_mock = m::mock(SoapMessageInterface::class);

        $message_mock->expects('request')->with($function_name, $args)->andReturnSelf();
        $message_mock->expects('getLocation')->andReturn('localhost');
        $message_mock->expects('getRequest')->andReturn('<request />');
        $message_mock->expects('getVersion')->andReturn(\SOAP_1_2);
        $message_mock->expects('getAction')->andReturn('action');
        $message_mock->expects('response')->with('<response />', $function_name)->andReturn('tested');

        $builder_mock
            ->expects('request')
            ->with('localhost', '<request />', [['Content-Type', 'application/soap+xml; charset="utf-8"; action="action"']])
            ->andReturn(new Success('<response />'));

        $soap_transport = new SoapTransport($message_mock, $builder_mock);

        self::assertSame('tested', yield $soap_transport->callAsync($function_name, $args));
    }

    /**
     * testItCanDestruct.
     *
     * @return void
     */
    public function testItCanDestruct(): void
    {
        $soap_transport = new SoapTransport(m::mock(SoapMessageInterface::class), m::mock(RequestBuilder::class));

        unset($soap_transport);

        self::assertNull($soap_transport ?? null);
    }
}
