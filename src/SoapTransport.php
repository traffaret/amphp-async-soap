<?php

declare(strict_types=1);

/**
 * Created by IntelliJ IDEA.
 *
 * PHP version 7.3
 *
 * @category amphp-async-soap
 * @author   Oleg Tikhonov <to@toro.one>
 */

namespace Traff\Soap;

use Amp\Promise;
use Amp\Http\Rfc7230;
use Traff\Soap\Message\SoapMessageInterface;
use Traff\Soap\RequestBuilder\RequestBuilder;
use function Amp\call;

/**
 * Class SoapRequest
 *
 * @category amphp-async-soap
 * @package  Traff\Soap
 * @author   Oleg Tikhonov <to@toro.one>
 */
final class SoapTransport
{
    private $soap_message;
    private $builder;

    public function __construct(SoapMessageInterface $soap_message, RequestBuilder $builder)
    {
        $this->soap_message = $soap_message;
        $this->builder = $builder;
    }

    public function __call($name, $arguments)
    {
        return $this->callAsync($name, $arguments);
    }

    public function getRawHeaders(): string
    {
        $request = $this->builder->getRequest();
        return Rfc7230::formatRawHeaders($request->getRawHeaders());
    }

    public function doRequest(string $request, string $location, string $action, int $version = null): Promise
    {
        return $this->builder->request($location, $request, $this->getSoapHeaders($version, $action));
    }

    public function callAsync(string $function_name, array $arguments): Promise
    {
        return call(
            function () use ($function_name, $arguments): \Generator {
                $this->soap_message->request($function_name, $arguments);

                $response = yield $this->builder->request(
                    $this->soap_message->getLocation(),
                    $this->soap_message->getRequest(),
                    $this->getSoapHeaders($this->soap_message->getVersion(), $this->soap_message->getAction())
                );

                return $this->soap_message->response($response, $function_name);
            }
        );
    }

    private function getSoapHeaders(int $soap_version, string $action): array {
        $headers = [];

        if (Options::SOAP_VERSION_1_1 === $soap_version) {
            $headers[] = ['Content-Type', 'text/xml; charset="utf-8";'];
            $headers[] = ['SOAPAction', sprintf('"%s"', $action)];
        } elseif (Options::SOAP_VERSION_1_2 === $soap_version) {
            $headers[] = ['Content-Type', sprintf('application/soap+xml; charset="utf-8"; action="%s"', $action)];
        }

        return $headers;
    }
}
