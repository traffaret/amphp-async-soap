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

namespace Traff\Soap\Test\Unit\RequestBuilder;

use Amp\Http\Client\DelegateHttpClient;
use Amp\NullCancellationToken;
use Traff\Soap\Options;
use Traff\Soap\RequestBuilder\WsdlRequestBuilder;
use Mockery as m;

/**
 * Class WsdlRequestBuilderTest.
 *
 * @package Traff\Soap\Test\Unit\RequestBuilder
 */
class WsdlRequestBuilderTest extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    /**
     * testItCanConstruct.
     *
     * @covers \Traff\Soap\RequestBuilder\WsdlRequestBuilder
     * @uses \Traff\Soap\Options
     *
     * @return void
     */
    public function testItCanConstruct(): void
    {
        self::assertIsObject(new WsdlRequestBuilder());
        self::assertIsObject(new WsdlRequestBuilder(new Options()));
        self::assertIsObject(new WsdlRequestBuilder(null, m::mock(DelegateHttpClient::class)));
    }

    /**
     * build.
     *
     * @covers \Traff\Soap\RequestBuilder\WsdlRequestBuilder::build
     * @uses \Traff\Soap\Options
     *
     * @return void
     */
    public function testBuild(): void
    {
        $builder = new WsdlRequestBuilder();

        $request = $builder->build('localhost', new Options());

        self::assertSame('GET', $request->getMethod());
    }

    /**
     * createRequestCancellationToken.
     *
     * @covers \Traff\Soap\RequestBuilder\WsdlRequestBuilder::createRequestCancellationToken
     *
     * @return void
     */
    public function testCreateRequestCancellationToken(): void
    {
        $builder = new WsdlRequestBuilder();

        self::assertInstanceOf(NullCancellationToken::class, $builder->createRequestCancellationToken());
    }
}
