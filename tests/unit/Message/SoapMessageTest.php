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

use Traff\Soap\Message\SoapMessage;
use Traff\Soap\Message\SoapMessageInterface;

const XML_REQUEST = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="uri:unittest" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><SOAP-ENV:Body><ns1:call/></SOAP-ENV:Body></SOAP-ENV:Envelope>

EOT;


/**
 * Class SoapMessageTest.
 *
 * @package Traff\Soap\Test\Unit\Message
 */
class SoapMessageTest extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    private const OPTIONS = [
        'soap_version' => \SOAP_1_1,
        'uri' => 'uri:unittest',
        'location' => 'http://localhost'
    ];

    /**
     * testRequest.
     *
     * @covers \Traff\Soap\Message\SoapMessage::request
     *
     * @throws \SoapFault
     * @return void
     */
    public function testRequest(): void
    {
        $message = new SoapMessage(null, self::OPTIONS);

        self::assertInstanceOf(SoapMessageInterface::class, $message->request('call', []));
    }

    /**
     * testResponse.
     *
     * @covers \Traff\Soap\Message\SoapMessage::response
     *
     * @throws \SoapFault
     * @return void
     */
    public function testResponse(): void
    {
        $message = new SoapMessage(null, self::OPTIONS);

        $message->request('call', []);

        self::assertNull($message->response(XML_REQUEST, 'call'));

        $this->expectException(\SoapFault::class);
        $message->response('response', 'call');
    }

    /**
     * request.
     *
     * @covers \Traff\Soap\Message\SoapMessage::getVersion
     *
     * @return void
     */
    public function testGetVersion(): void
    {
        $message = new SoapMessage(null, self::OPTIONS);

        $message->request('call', []);

        self::assertSame(\SOAP_1_1, $message->getVersion());
    }

    /**
     * testGetRequest.
     *
     * @covers \Traff\Soap\Message\SoapMessage::getRequest
     *
     * @throws \SoapFault
     * @return void
     */
    public function testGetRequest(): void
    {
        $message = new SoapMessage(null, self::OPTIONS);

        $message->request('call', []);

        self::assertSame(XML_REQUEST, $message->getRequest());
    }

    /**
     * testGetAction.
     *
     * @covers \Traff\Soap\Message\SoapMessage::getAction
     *
     * @throws \SoapFault
     * @return void
     */
    public function testGetAction(): void
    {
        $message = new SoapMessage(null, self::OPTIONS);

        $message->request('call', []);

        self::assertSame('uri:unittest#call', $message->getAction());
    }

    /**
     * testGetLocation.
     *
     * @covers \Traff\Soap\Message\SoapMessage::getLocation
     *
     * @throws \SoapFault
     * @return void
     */
    public function testGetLocation(): void
    {
        $message = new SoapMessage(null, self::OPTIONS);

        $message->request('call', []);

        self::assertSame('http://localhost', $message->getLocation());
    }

    /**
     * testDoRequest.
     *
     * @covers \Traff\Soap\Message\SoapMessage::__doRequest
     *
     * @return void
     */
    public function testDoRequest(): void
    {
        $message = new SoapMessage(null, self::OPTIONS);

        $message->__doRequest(XML_REQUEST, 'http://localhost', 'uri:unittest#call', \SOAP_1_1);

        self::assertSame(\SOAP_1_1, $message->getVersion());
    }
}
