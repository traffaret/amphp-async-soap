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

namespace Traff\Soap\Test\Unit\Wsdl;

use Amp\Http\Client\DelegateHttpClient;
use Traff\Soap\Options;
use Traff\Soap\Wsdl\Wsdl;
use Traff\Soap\Wsdl\WsdlUrlFactory;
use Mockery as m;

/**
 * Class WsdlUrlFactoryTest.
 *
 * @package Traff\Soap\Test\Unit\Wsdl
 */
class WsdlUrlFactoryTest extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    /**
     * createWsdl.
     *
     * @covers \Traff\Soap\Wsdl\WsdlUrlFactory::createWsdl
     *
     * @return void
     * @noinspection UnnecessaryAssertionInspection
     */
    public function testCreateWsdl(): void
    {
        $factory = new WsdlUrlFactory();

        self::assertInstanceOf(Wsdl::class, $factory->createWsdl('localhost'));
        self::assertInstanceOf(Wsdl::class, $factory->createWsdl('localhost', new Options()));
        self::assertInstanceOf(Wsdl::class, $factory->createWsdl('localhost', null, m::mock(DelegateHttpClient::class)));
    }
}
