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

namespace Traff\Soap\Message;

/**
 * SOAP client wrapper.
 *
 * @category amphp-async-soap
 * @package  Traff\Soap
 * @author   Oleg Tikhonov <to@toro.one>
 */
final class SoapMessage extends \SoapClient implements SoapMessageInterface
{
    /** @var string|null */
    private ?string $response = null;

    /** @var string|null */
    private ?string $request = null;

    /** @var string|null */
    private ?string $action = null;

    /** @var int|null */
    private ?int $version = null;

    /** @var string|null */
    private ?string $soap_location = null;

    /** @inheritDoc */
    public function __doRequest($request, $location, $action, $version, $one_way = 0): string
    {
        $result = '';

        if (null === $this->response) {
            $this->soap_location = (string) $location;
            $this->request = (string) $request;
            $this->action = (string) $action;
            $this->version = (int) $version;
        } else {
            $result = $this->response;
        }

        return $result;
    }

    /** @inheritDoc */
    public function getLocation(): ?string
    {
        return $this->soap_location;
    }

    /** @inheritDoc */
    public function getAction(): ?string
    {
        return $this->action;
    }

    /** @inheritDoc */
    public function getRequest(): ?string
    {
        return $this->request;
    }

    /** @inheritDoc */
    public function getVersion(): ?int
    {
        return $this->version;
    }

    /**
     * @inheritDoc
     *
     * @throws \SoapFault
     */
    public function request(string $func_name, array $arguments): SoapMessageInterface
    {
        $this->__soapCall($func_name, $arguments);

        if (isset($this->__soap_fault) && $this->__soap_fault instanceof \SoapFault) {
            throw $this->__soap_fault;
        }

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @noinspection PhpExceptionImmediatelyRethrownInspection
     */
    public function response(string $response, string $func_name): mixed
    {
        $this->response = $response;

        try {
            $response = $this->__soapCall($func_name, []);

            if ($response instanceof \SoapFault) {
                throw $response;
            }
        } catch (\SoapFault $e) {
            throw $e;
        } finally {
            $this->response = null;
        }

        return $response;
    }
}
