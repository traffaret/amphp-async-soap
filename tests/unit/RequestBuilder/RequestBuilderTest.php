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

use Amp\ByteStream\InputStream;
use Amp\Http\Client\DelegateHttpClient;
use Amp\Http\Client\EventListener;
use Amp\Http\Client\Request;
use Amp\Http\Message;
use Amp\NullCancellationToken;
use Amp\PHPUnit\AsyncTestCase;
use Amp\Success;
use Traff\Soap\Options;
use Traff\Soap\RequestBuilder\RequestBuilder;
use Mockery as m;
use Traff\Soap\Test\MockeryTrait;

/**
 * Class RequestBuilderTest.
 *
 * @package Traff\Soap\Test\Unit\RequestBuilder
 */
class RequestBuilderTest extends AsyncTestCase
{
    use MockeryTrait;

    /** @var \Amp\Http\Client\DelegateHttpClient|\Mockery\MockInterface */
    private $http_client;

    /** @inheritDoc */
    protected function setUpAsync(): void
    {
        $this->http_client = m::mock(DelegateHttpClient::class);
    }

    /**
     * getOptions.
     *
     * @covers \Traff\Soap\RequestBuilder\RequestBuilder::getOptions
     * @uses \Traff\Soap\Options
     *
     * @return void
     * @noinspection PhpUndefinedMethodInspection
     */
    public function testGetOptions(): void
    {
        $builder = m::mock(RequestBuilder::class, [null, $this->http_client])->makePartial();

        self::assertInstanceOf(Options::class, $builder->getOptions());

        $options = new Options();
        $builder = m::mock(RequestBuilder::class, [$options, $this->http_client])->makePartial();

        self::assertSame($options, $builder->getOptions());
    }

    /**
     * withEventListeners.
     *
     * @covers \Traff\Soap\RequestBuilder\RequestBuilder::withEventListeners
     *
     * @return void
     */
    public function testWithEventListeners(): void
    {
        $builder = m::mock(RequestBuilder::class, [null, $this->http_client])->makePartial();

        /** @noinspection PhpUndefinedMethodInspection */
        self::assertNotSame($builder, $builder->withEventListeners(m::mock(EventListener::class)));
    }

    /**
     * withHttpClient.
     *
     * @covers \Traff\Soap\RequestBuilder\RequestBuilder::withHttpClient
     *
     * @return void
     */
    public function testWithHttpClient(): void
    {
        $builder = m::mock(RequestBuilder::class, [null, $this->http_client])->makePartial();

        /** @noinspection PhpUndefinedMethodInspection */
        self::assertNotSame($builder, $builder->withHttpClient($this->http_client));
    }

    /**
     * withOptions.
     *
     * @covers \Traff\Soap\RequestBuilder\RequestBuilder::withOptions
     * @uses \Traff\Soap\Options
     *
     * @return void
     */
    public function testWithOptions(): void
    {
        $options = new Options();
        $builder = m::mock(RequestBuilder::class, [$options, $this->http_client])->makePartial();

        /** @noinspection PhpUndefinedMethodInspection */
        self::assertNotSame($builder, $builder->withOptions($options));
    }

    /**
     * testRequest.
     *
     * @covers \Traff\Soap\RequestBuilder\RequestBuilder::request
     * @covers \Traff\Soap\RequestBuilder\RequestBuilder::setRequestTimeout
     * @covers \Traff\Soap\RequestBuilder\RequestBuilder::getAuthenticationHeaders
     * @covers \Traff\Soap\RequestBuilder\RequestBuilder::getRequest
     * @covers \Traff\Soap\RequestBuilder\RequestBuilder::withEventListeners
     * @uses \Traff\Soap\Options::withLogin()
     * @uses \Traff\Soap\Options::withConnectionTimeout()
     * @uses \Traff\Soap\Options::withPassword()
     *
     * @return \Generator
     * @noinspection PhpUndefinedMethodInspection
     */
    public function testRequest(): \Generator
    {
        $options = (new Options())
            ->withLogin('login')
            ->withPassword('password')
            ->withConnectionTimeout(50);

        $builder = m::mock(RequestBuilder::class, [$options, $this->http_client])
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $builder = $builder->withEventListeners(m::mock(EventListener::class));

        $uri = 'http://localhost';
        $body = '<body>string</body>';
        $request_message = new Request($uri);
        $response_message = m::mock(Message::class);
        $payload = m::mock(InputStream::class);

        $builder->expects('build')->with($uri, $options)->andReturn($request_message);
        $builder->expects('getHeaders')->with($options)->andReturn([['Content-Type', 'text/xml; charset=utf-8;']]);
        $builder->expects('createRequestCancellationToken')->andReturn(new NullCancellationToken());

        $this->http_client
            ->expects('request')
            ->with($request_message, m::type(NullCancellationToken::class))
            ->andReturn(new Success($response_message));

        $response_message->expects('getRequest')->andReturn($request_message);
        $response_message->expects('getBody')->andReturn($payload);

        $payload->expects('buffer')->andReturn(new Success('response string'));

        yield $builder->request($uri, $body);

        $request = $builder->getRequest();

        self::assertSame(50000, $request->getTcpConnectTimeout());
        self::assertSame(50000, $request->getTransferTimeout());
        self::assertSame(50000, $request->getInactivityTimeout());
        self::assertSame(50000, $request->getTlsHandshakeTimeout());
        self::assertSame(
            [
                'authorization' => ['Basic bG9naW46cGFzc3dvcmQ='],
                'content-type' => ['text/xml; charset=utf-8;'],
            ],
            $request->getHeaders()
        );
    }

    /**
     * testItCanNotGetRequestBeforeItPerforms.
     *
     * @covers \Traff\Soap\RequestBuilder\RequestBuilder::getRequest
     *
     * @return void
     */
    public function testItCanNotGetRequestBeforeItPerforms(): void
    {
        $builder = m::mock(RequestBuilder::class, [null, $this->http_client])->makePartial();

        $this->expectException(\Error::class);
        /** @noinspection PhpUndefinedMethodInspection */
        $builder->getRequest();
    }

    /**
     * testItCanDestruct.
     *
     * @return void
     */
    public function testItCanDestruct(): void
    {
        $builder = m::mock(RequestBuilder::class, [null, $this->http_client])->makePartial();

        unset($builder);

        self::assertNull($builder ?? null);
    }
}
