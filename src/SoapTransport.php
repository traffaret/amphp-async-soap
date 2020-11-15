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

namespace Traff\Soap;

use Amp\Http\Client\Request;
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
    /** @var \Traff\Soap\Message\SoapMessageInterface */
    private $soap_message;

    /** @var \Traff\Soap\RequestBuilder\RequestBuilder */
    private $builder;

    /**
     * SoapTransport constructor.
     *
     * @param \Traff\Soap\Message\SoapMessageInterface  $soap_message SOAP client wrapper.
     * @param \Traff\Soap\RequestBuilder\RequestBuilder $builder      Request builder.
     */
    public function __construct(
        SoapMessageInterface $soap_message,
        RequestBuilder $builder
    ) {
        $this->soap_message = $soap_message;
        $this->builder = $builder;
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        unset(
            $this->soap_message,
            $this->builder
        );
    }

    /**
     * Explicitly call SOAP service function.
     *
     * @param $name
     * @param $arguments
     *
     * @return \Amp\Promise
     */
    public function __call($name, $arguments)
    {
        return $this->callAsync($name, $arguments);
    }

    /**
     * Return raw request headers.
     *
     * @throws \Amp\Http\InvalidHeaderException
     * @return string
     */
    public function getRawHeaders(): string
    {
        return Rfc7230::formatRawHeaders($this->getRequest()->getRawHeaders());
    }

    /**
     * Return request instance.
     *
     * Return instance that was performed in the provided request builder.
     *
     * @return \Amp\Http\Client\Request
     */
    public function getRequest(): Request
    {
        return $this->builder->getRequest();
    }

    public function getOptions(): Options
    {
        return $this->builder->getOptions();
    }

    /**
     * Perform SOAP request.
     *
     * @param string   $request  Request string.
     * @param string   $location SOAP location.
     * @param string   $action   SOAP action.
     * @param int|null $version  SOAP version.
     *
     * @return \Amp\Promise
     */
    public function doRequest(string $request, string $location, string $action, int $version = null): Promise
    {
        return $this->builder->request($location, $request, $this->getSoapHeaders($version, $action));
    }

    /**
     * Implicitly call SOAP service function.
     *
     * @param string $function_name SOAP service function.
     * @param array  $arguments     SOAP service function arguments.
     *
     * @return \Amp\Promise
     */
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

    /**
     * Get SOAP headers to request.
     *
     * @param int    $soap_version SOAP version.
     * @param string $action       SOAP action.
     *
     * @return array
     */
    private function getSoapHeaders(int $soap_version, string $action): array
    {
        $headers = [];

        if (Options::SOAP_VERSION_1_1 === $soap_version) {
            $headers[] = ['Content-Type', 'text/xml; charset="utf-8";'];
            $headers[] = ['SOAPAction', sprintf('"%s"', $action)];
        } elseif (Options::SOAP_VERSION_1_2 === $soap_version) {
            $headers[] = ['Content-Type', sprintf('application/soap+xml; charset="utf-8"; action="%s"', $action)];
        } else {
            throw new \InvalidArgumentException(\sprintf('Invalid SOAP version %s', $soap_version));
        }

        return $headers;
    }
}
