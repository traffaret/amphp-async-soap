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

use Amp\Http\Client\DelegateHttpClient;
use Amp\Http\Client\EventListener;
use Traff\Soap\Message\SoapMessageFactoryInterface;
use Traff\Soap\Message\SoapMessageInterface;
use Traff\Soap\Options;
use Traff\Soap\RequestBuilder\RequestBuilder;
use Traff\Soap\SoapTransportBuilder;
use Mockery as m;

use function Traff\Soap\plainWsdl;

/**
 * Class SoapTransportBuilderTest.
 *
 * @package Traff\Soap\Test\Unit
 */
class SoapTransportBuilderTest extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    /**
     * testItCanConstruct.
     *
     * @covers \Traff\Soap\SoapTransportBuilder
     *
     * @return void
     */
    public function testItCanConstruct(): void
    {
        self::assertIsObject(new SoapTransportBuilder());
        self::assertIsObject(new SoapTransportBuilder(m::mock(RequestBuilder::class)));
        self::assertIsObject(new SoapTransportBuilder(null, m::mock(SoapMessageFactoryInterface::class)));
    }

    /**
     * testItCanDestruct.
     *
     * @covers \Traff\Soap\SoapTransportBuilder
     *
     * @return void
     */
    public function testItCanDestruct(): void
    {
        $builder = new SoapTransportBuilder(m::mock(RequestBuilder::class), m::mock(SoapMessageFactoryInterface::class));

        unset($builder);

        self::assertNull($builder ?? null);
    }

    /**
     * withWsdl.
     *
     * @covers \Traff\Soap\SoapTransportBuilder::withWsdl
     *
     * @return void
     */
    public function testWithWsdl(): void
    {
        $transport_builder = new SoapTransportBuilder(m::mock(RequestBuilder::class), m::mock(SoapMessageFactoryInterface::class));

        self::assertNotSame($transport_builder, $transport_builder->withWsdl('wsdl'));

        $this->expectException(\InvalidArgumentException::class);
        $transport_builder->withWsdl('');
    }

    /**
     * withoutWsdl.
     *
     * @covers \Traff\Soap\SoapTransportBuilder::withoutWsdl
     *
     * @return void
     */
    public function testWithoutWsdl(): void
    {
        $transport_builder = new SoapTransportBuilder(m::mock(RequestBuilder::class), m::mock(SoapMessageFactoryInterface::class));

        self::assertNotSame($transport_builder, $transport_builder->withoutWsdl());
    }

    /**
     * withEventListeners.
     *
     * @covers \Traff\Soap\SoapTransportBuilder::withEventListeners
     *
     * @return void
     */
    public function testWithEventListeners(): void
    {
        $transport_builder = new SoapTransportBuilder(m::mock(RequestBuilder::class), m::mock(SoapMessageFactoryInterface::class));

        self::assertNotSame($transport_builder, $transport_builder->withEventListeners(m::mock(EventListener::class), m::mock(EventListener::class)));
    }

    /**
     * withOptions.
     *
     * @covers \Traff\Soap\SoapTransportBuilder::withOptions
     * @uses \Traff\Soap\Options
     *
     * @return void
     */
    public function testWithOptions(): void
    {
        $transport_builder = new SoapTransportBuilder(m::mock(RequestBuilder::class), m::mock(SoapMessageFactoryInterface::class));

        self::assertNotSame($transport_builder, $transport_builder->withOptions(new Options()));
    }

    /**
     * withHttpClient.
     *
     * @covers \Traff\Soap\SoapTransportBuilder::withHttpClient
     *
     * @return void
     */
    public function testWithHttpClient(): void
    {
        $transport_builder = new SoapTransportBuilder(m::mock(RequestBuilder::class), m::mock(SoapMessageFactoryInterface::class));

        self::assertNotSame($transport_builder, $transport_builder->withHttpClient(m::mock(DelegateHttpClient::class)));
    }

    /**
     * build.
     *
     * @covers \Traff\Soap\SoapTransportBuilder::build
     * @uses \Traff\Soap\plainWsdl()
     *
     * @return void
     */
    public function testBuild(): void
    {
        $wsdl = plainWsdl('wsdl');
        $options = new Options();
        $http_client_mock = m::mock(DelegateHttpClient::class);
        $request_builder_mock = m::mock(RequestBuilder::class);
        $message_factory_mock = m::mock(SoapMessageFactoryInterface::class);
        $event_listeners = [m::mock(EventListener::class), m::mock(EventListener::class)];

        $request_builder_mock->expects('withHttpClient')->with($http_client_mock)->andReturnSelf();
        $request_builder_mock->expects('withEventListeners')->with(...$event_listeners)->andReturnSelf();
        $request_builder_mock->expects('withOptions')->with($options)->andReturnSelf();
        $request_builder_mock->shouldReceive('getOptions')->andReturn($options);

        $message_factory_mock->expects('createMessage')->with($wsdl, $options)->andReturn(m::mock(SoapMessageInterface::class));
        $message_factory_mock->expects('createMessage')->with(null, $options)->andReturn(m::mock(SoapMessageInterface::class));

        (new SoapTransportBuilder($request_builder_mock, $message_factory_mock))
            ->withWsdl($wsdl)
            ->withHttpClient($http_client_mock)
            ->withEventListeners(...$event_listeners)
            ->withOptions($options)
            ->build();

        (new SoapTransportBuilder($request_builder_mock, $message_factory_mock))
            ->withoutWsdl()
            ->build();
    }
}
