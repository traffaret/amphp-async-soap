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
use Mockery as m;
use Traff\Soap\Message\SoapMessage;
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
     * @uses \Traff\Soap\Options
     *
     * @return void
     */
    public function testCreateMessage(): void
    {
        $factory = new DefaultSoapMessageFactory();
        $options = new Options();

        $message_mock = m::mock('overload:' . SoapMessage::class, SoapMessageInterface::class);

        $message_mock->expects('__construct')->with(null, m::type('array'));

        $factory->createMessage(null, $options);
    }
}
