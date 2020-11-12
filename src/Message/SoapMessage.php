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
 * Class SoapMessage
 *
 * @category amphp-async-soap
 * @package  Traff\Soap
 * @author   Oleg Tikhonov <to@toro.one>
 */
final class SoapMessage extends \SoapClient implements SoapMessageInterface
{
    private $response;
    private $request;
    private $action;
    private $version;
    private $soap_location;

    public function __doRequest($request, $location, $action, $version, $one_way = 0)
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

    public function getLocation(): ?string
    {
        return $this->soap_location;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function getRequest(): ?string
    {
        return $this->request;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function request(string $func_name, array $arguments): SoapMessageInterface
    {
        $this->__soapCall($func_name, $arguments);
        if (isset($this->__soap_fault) && $this->__soap_fault instanceof \SoapFault) {
            throw $this->__soap_fault;
        }

        return $this;
    }

    public function response(string $response, string $func_name)
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
