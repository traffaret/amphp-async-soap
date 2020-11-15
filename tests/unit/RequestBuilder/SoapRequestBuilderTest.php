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

namespace Traff\Soap\Test\Unit\RequestBuilder;

use Amp\Http\Client\DelegateHttpClient;
use Amp\NullCancellationToken;
use Traff\Soap\Options;
use Traff\Soap\RequestBuilder\SoapRequestBuilder;
use Mockery as m;

/**
 * Class SoapRequestBuilderTest.
 *
 * @package Traff\Soap\Test\Unit\RequestBuilder
 */
class SoapRequestBuilderTest extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    /**
     * testItCanConstruct.
     *
     * @covers \Traff\Soap\RequestBuilder\SoapRequestBuilder
     * @uses \Traff\Soap\Options
     *
     * @return void
     */
    public function testItCanConstruct(): void
    {
        self::assertIsObject(new SoapRequestBuilder());
        self::assertIsObject(new SoapRequestBuilder(new Options()));
        self::assertIsObject(new SoapRequestBuilder(null, m::mock(DelegateHttpClient::class)));
    }

    /**
     * build.
     *
     * @covers \Traff\Soap\RequestBuilder\SoapRequestBuilder::build
     * @uses \Traff\Soap\Options
     *
     * @return void
     */
    public function testBuild(): void
    {
        $builder = new SoapRequestBuilder();

        $request = $builder->build('localhost', new Options());

        self::assertSame('POST', $request->getMethod());
        self::assertSame('localhost', (string) $request->getUri());
    }

    /**
     * createRequestCancellationToken.
     *
     * @covers \Traff\Soap\RequestBuilder\SoapRequestBuilder::createRequestCancellationToken
     *
     * @return void
     */
    public function testCreateRequestCancellationToken(): void
    {
        $builder = new SoapRequestBuilder();

        self::assertInstanceOf(NullCancellationToken::class, $builder->createRequestCancellationToken());
    }
}
