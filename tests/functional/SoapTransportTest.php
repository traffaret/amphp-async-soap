<?php

/**
 * Created by IntelliJ IDEA.
 *
 * PHP version 7.3
 *
 * @category amphp-async-soap
 * @author   Oleg Tikhonov <to@toro.one>
 */

namespace Traff\Soap\Test\Functional;

use Amp\Http\Client\EventListener\RecordHarAttributes;
use Amp\Http\Client\HttpClientBuilder;
use Amp\Http\Client\Internal\HarAttributes;
use Amp\PHPUnit\AsyncTestCase;
use Traff\Soap\Options;
use Traff\Soap\SoapTransportBuilder;
use Traff\Soap\Wsdl\WsdlUrlFactory;

/**
 * Class SoapTransportTest.
 *
 * @package Traff\Soap\Test\Functional
 */
class SoapTransportTest extends AsyncTestCase
{
    /** @var \Traff\Soap\SoapTransport */
    private $soap_transport;

    /** @inheritDoc */
    protected function setUpAsync()
    {
        $http_client = HttpClientBuilder::buildDefault();

        $wsdl = yield (new WsdlUrlFactory())
            ->createWsdl('https://cbr.ru/DailyInfoWebServ/DailyInfo.asmx?WSDL', null, $http_client)
            ->toString();

        $this->soap_transport = (new SoapTransportBuilder())
            ->withHttpClient($http_client)
            ->withEventListeners(new RecordHarAttributes())
            ->withWsdl($wsdl)
            ->build();
    }

    /**
     * testItCanSendRequestThroughCall.
     *
     * @return \Generator
     */
    public function testItCanSendRequestThroughCall(): \Generator
    {
        $result = yield $this->soap_transport->callAsync('GetCursOnDate',
            [
                ['On_date' => (new \DateTime('now'))->format('Y-m-d')]
            ]
        );

        $request = $this->soap_transport->getRequest();

        // Check event listener was performed
        $timing = $request->getAttribute(HarAttributes::TIME_COMPLETE) - $request->getAttribute(HarAttributes::TIME_START);

        self::assertTrue(0 < $timing);
        self::assertArrayHasKey('GetCursOnDateResult', (array) $result);
    }

    /**
     * testItCanSendRequestUsingRemoteFunctionName.
     *
     * @return \Generator
     * @noinspection PhpUndefinedMethodInspection
     */
    public function testItCanSendRequestUsingRemoteFunctionName(): \Generator
    {
        $result = yield $this->soap_transport->GetCursOnDate(['On_date' => (new \DateTime('now'))->format('Y-m-d')]);

        self::assertArrayHasKey('GetCursOnDateResult', (array) $result);
    }

    /**
     * testItCanDoRequest.
     *
     * @return \Generator
     */
    public function testItCanDoRequest(): \Generator
    {
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://web.cbr.ru/">
    <SOAP-ENV:Body><ns1:GetCursOnDate><ns1:On_date>{{on_date}}</ns1:On_date></ns1:GetCursOnDate></SOAP-ENV:Body>
</SOAP-ENV:Envelope>
XML;
        $xml = \str_replace('{{on_date}}', (new \DateTime('now'))->format('Y-m-d'), $xml);
        $result = yield $this->soap_transport->doRequest(
            $xml,
            'http://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx',
            'http://web.cbr.ru/GetCursOnDate',
            Options::SOAP_VERSION_1_1
        );

        self::assertStringContainsString('<GetCursOnDateResult>', $result);
    }

    /**
     * testItCanRaiseSoapFault
     *
     * @return \Generator
     * @noinspection PhpUndefinedMethodInspection
     */
    public function testItCanRaiseSoapFault(): \Generator
    {
        $this->expectException(\SoapFault::class);
        yield $this->soap_transport->NotExistFunction();
    }
}
