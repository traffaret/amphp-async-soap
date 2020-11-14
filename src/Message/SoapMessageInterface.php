<?php

/**
 * Created by IntelliJ IDEA.
 *
 * PHP version 7.3
 *
 * @category amphp-async-soap
 * @author   Oleg Tikhonov <to@toro.one>
 */

namespace Traff\Soap\Message;

/**
 * Interface SoapMessageInterface
 *
 * @package Traff\Soap
 */
interface SoapMessageInterface
{
    /**
     * Perform SOAP request.
     *
     * @param string $func_name Service method.
     * @param array  $arguments Service arguments.
     *
     * @return \Traff\Soap\Message\SoapMessageInterface
     */
    public function request(string $func_name, array $arguments): self;

    /**
     * Get SOAP response.
     *
     * @param string $response  Service response.
     * @param string $func_name Service method.
     *
     * @return mixed
     */
    public function response(string $response, string $func_name);

    /**
     * SOAP location.
     *
     * @return string|null
     */
    public function getLocation(): ?string;

    /**
     * SOAP request.
     *
     * @return string|null
     */
    public function getRequest(): ?string;

    /**
     * SOAP action.
     *
     * @return string|null
     */
    public function getAction(): ?string;

    /**
     * SOAP version.
     *
     * @return int|null
     */
    public function getVersion(): ?int;
}
