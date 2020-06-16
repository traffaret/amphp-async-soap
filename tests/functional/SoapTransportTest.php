<?php
/**
 * Created by IntelliJ IDEA.
 *
 * PHP version 7.3
 *
 * @category amphp-async-soap
 * @author   Oleg Tikhonov <to@toro.one>
 */

namespace Traff\Soap\Test\functional;

use Amp\Http\Client\Connection\ConnectionLimitingPool;
use Amp\Http\Client\HttpClientBuilder;
use Amp\PHPUnit\AsyncTestCase;
use Traff\Soap\Message\SoapMessage;
use Traff\Soap\Options;
use Traff\Soap\RequestBuilder\SoapRequestBuilder;
use Traff\Soap\SoapTransport;
use Traff\Soap\Wsdl\WsdlUrl;
use Traff\Soap\RequestBuilder\WsdlRequestBuilder;

class SoapTransportTest extends AsyncTestCase
{
    /** @var SoapTransport */
    private $soap_transport;

    public function setUpAsync()
    {
        $soap_options = (new Options())
            ->withSoapVersion(Options::SOAP_VERSION_1_1);

        $http_pool = ConnectionLimitingPool::byAuthority(5);
        $http_client = (new HttpClientBuilder)
            ->usingPool($http_pool)
            ->followRedirects(0)
            ->build();

        $wsdl = yield (new WsdlUrl(
            'https://cbr.ru/DailyInfoWebServ/DailyInfo.asmx?WSDL',
            new WsdlRequestBuilder($http_client, $soap_options)
        ))->toString();

        $this->soap_transport = new SoapTransport(
            new SoapMessage($wsdl, $soap_options->toArray()),
            new SoapRequestBuilder($http_client, $soap_options)
        );
    }

    public function testItCanSendRequestThroughCall(): \Generator
    {
        $result = yield $this->soap_transport->callAsync('GetCursOnDate', [['On_date' => (new \DateTime('now'))->format('Y-m-d')]]);
        self::assertArrayHasKey('GetCursOnDateResult', (array) $result);
    }

    public function testItCanSendRequestUsingRemoteFunctionName(): \Generator
    {
        $result = yield $this->soap_transport->GetCursOnDate(['On_date' => (new \DateTime('now'))->format('Y-m-d')]);
        self::assertArrayHasKey('GetCursOnDateResult', (array) $result);
    }

    public function testItCanDoRequest(): \Generator
    {
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://web.cbr.ru/"><SOAP-ENV:Body><ns1:GetCursOnDate><ns1:On_date>{{on_date}}</ns1:On_date></ns1:GetCursOnDate></SOAP-ENV:Body></SOAP-ENV:Envelope>
XML;
        $xml = \str_replace('{{on_date}}', (new \DateTime('now'))->format('Y-m-d'), $xml);
        $result = yield $this->soap_transport->doRequest(
            $xml,
            'http://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx',
            'http://web.cbr.ru/GetCursOnDate',
            Options::SOAP_VERSION_1_1
        );

        self::assertStringContainsString('<?xml version="1.0" encoding="utf-8"?>', $result);
    }

    /**
     * testItCanRaiseSoapFault
     *
     * @return \Generator
     */
    public function testItCanRaiseSoapFault(): \Generator
    {
        $this->expectException(\SoapFault::class);
        yield $this->soap_transport->NotExistFunction();
    }
}
