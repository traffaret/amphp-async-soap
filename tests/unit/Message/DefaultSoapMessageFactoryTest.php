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

namespace Traff\Soap\Test\Unit\Message;

use Traff\Soap\Message\DefaultSoapMessageFactory;
use Traff\Soap\Message\SoapMessageInterface;
use Traff\Soap\Options;

/**
 * Class DefaultSoapMessageFactoryTest.
 *
 * @package Traff\Soap\Test\Unit\Message
 */
class DefaultSoapMessageFactoryTest extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    /**
     * createMessage.
     *
     * @covers \Traff\Soap\Message\DefaultSoapMessageFactory::createMessage
     * @uses \Traff\Soap\Options::withUri()
     * @uses \Traff\Soap\Options::withLocation()
     *
     * @throws \SoapFault
     * @return void
     */
    public function testCreateMessage(): void
    {
        $factory = new DefaultSoapMessageFactory();
        $options = (new Options())->withUri('uri:unittest')->withLocation('http://location');

        /** @noinspection UnnecessaryAssertionInspection */
        self::assertInstanceOf(SoapMessageInterface::class, $factory->createMessage(null, $options));
    }
}
